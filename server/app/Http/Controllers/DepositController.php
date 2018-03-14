<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\Utils\BaseUtil;
use App\Utils\DictUtil;
use App\Utils\Auth;

class DepositController extends Controller
{

    public function getDepositBoxList (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'lockTime' => 'nullable|numeric',
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $whereArr = [
            ['deposit_box.status', 1],
            ['project.status', 1],
        ];

        if ($lockTime) {
            $whereArr[] = array('lock_time', $lockTime);
        }

        $depositBoxModel = Model\DepositBox::join('project', 'deposit_box.proj_id', '=', 'project.id')
            ->join('token', 'project.token_id', '=', 'token.id')
            ->where($whereArr);

        $dataCount = $depositBoxModel->count();
        $offset = $perpage * ($pageno - 1);
        $dataList = $depositBoxModel->select('token.name as tokenName', 'token.symbol as tokenSymbol', 'project.name_cn', 'project.logo_url', 'deposit_box.id', 'deposit_box.min_amount', 'deposit_box.lock_time', 'deposit_box.remain_amount', 'deposit_box.interest_rate', 'deposit_box.to_addr')
            ->offset($offset)->limit($perpage)->get()->toArray();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $dataList,
        ]);
    }

    public function addDepositOrder (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'depositBoxId' => 'required|numeric',
            'orderAmount' => 'required|string',
            'fromAddr' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 正则匹配fromAddr
        if (strpos($fromAddr, '0x') !== 0 || strlen($fromAddr) !== 42) {
            return $this->error(100);
        }

        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        // 检查剩余额度是否充足
        $depositBoxData = Model\DepositBox::find($depositBoxId);
        if (!$depositBoxData) {
            return $this->error(304);
        }
        if ($depositBoxData['remain_amount'] < $orderAmount) {
            return $this->error(305);
        }
        if ($orderAmount < $depositBoxData['min_amount']) {
            return $this->error(403);
        }

        // 创建订单
        Model\DepositBox::where('id', $depositBoxId)->decrement('remain_amount', $orderAmount);
        $orderData = [
            'user_id' => $userId,
            'deposit_box_id' => $depositBoxId,
            'order_amount' => $orderAmount,
            'from_addr' => strtolower($fromAddr),
            'to_addr' => $depositBoxData['to_addr'],
            'contract_addr' => $depositBoxData['contract_addr'],
            'status' => 0,
        ];
        $depositOrderModel = Model\DepositOrder::create($orderData);
        $orderData['id'] = $depositOrderModel->id;

        return $this->output($orderData);
    }

    public function getOrderDetail (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'depositOrderId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 验证用户是否登录
        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        // 查找订单
        $depositOrderData = Model\DepositOrder::where([
            ['id', $depositOrderId],
            ['user_id', $userId],
        ])->first();
        if (!$depositOrderData) {
            return $this->error(401);
        }

        $depositBoxData = Model\DepositBox::find($depositOrderData['deposit_box_id']);
        $depositOrderData['lockTime'] = $depositBoxData['lock_time'];
        $depositOrderData['interestRate'] = $depositBoxData['interest_rate'];

        $projId = $depositBoxData['proj_id'];
        $projData = Model\Project::join('token', 'project.token_id', '=', 'token.id')
            ->where('project.id', $projId)
            ->select('project.logo_url', 'token.name as tokenName', 'token.symbol as tokenSymbol')
            ->first();
        if (!$projData) {
            return $this->error();
        }
        $depositOrderData['projData'] = $projData->toArray();

        return $this->output($depositOrderData);
    }

    public function getOrderTxRecordList (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'depositOrderId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 获取订单信息
        $depositOrderData = Model\DepositOrder::find($depositOrderId);
        if (!$depositOrderData) {
            return $this->error(306);
        }

        $postData = array(
            'fromAddr' => $depositOrderData->from_addr,
            'toAddr' => $depositOrderData->to_addr,
            'contractAddr' => $depositOrderData->contract_addr,
        );
        $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/getTxRecordList', $postData);
        $resArr = json_decode($resJson, true);
        if (!$resArr || $resArr['errcode'] !== 0) {
            return $this->error();
        }
        $txRecordList = $resArr['data']['dataList'];

        return $this->output(['dataList' => $txRecordList]);
    }

    public function confirmDepositTx (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'depositOrderId' => 'required|numeric',
            'txRecordIdList' => 'nullable|array',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 获取用户ID
        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        // 获取订单信息
        $depositOrderData = Model\DepositOrder::find($depositOrderId);
        if (!$depositOrderData) {
            return $this->error(306);
        }

        if ($txRecordIdList == null) {
            return $this->error(309);
        }

        // 调用接口确认交易
        $postData = array(
            'fromAddr' => $depositOrderData->from_addr,
            'toAddr' => $depositOrderData->to_addr,
            'contractAddr' => $depositOrderData->contract_addr,
            'orderAmount' => $depositOrderData->order_amount . '',
            'txRecordIdList' => $txRecordIdList,
        );
        $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/confirmTx', $postData);
        $resArr = json_decode($resJson, true);
        if (!$resArr || $resArr['errcode'] !== 0) {
            return $this->error(307);
        }

        // 存储交易记录
        $txRecordList = $resArr['data']['dataList'];
        foreach ($txRecordList as $txRecord) {
            Model\OrderTxRecord::create([
                'tx_record_id' => $txRecord['id'],
                'tx_hash' => $txRecord['tx_hash'],
                'tx_type' => 2,
                'target_id' => $depositOrderId,
            ]);
        }

        // 更新订单状态
        $flag = Model\DepositOrder::where('id', $depositOrderId)->update([
            'status' => 1,
            'finish_time' => date('Y-m-d H:i:s'),
        ]);

        // 获取余币宝信息
        $depositBoxId = $depositOrderData->deposit_box_id;
        $depositBoxModel = Model\DepositBox::find($depositBoxId);
        $lockTime = $depositBoxModel->lock_time;
        $amount = $depositOrderData->order_amount;

        // 添加用户余币宝
        $startTime = date('Y-m-d 00:00:00', time() + 24 * 3600); // 从下一天凌晨0点开始计算利息
        $endTime = date('Y-m-d 00:00:00', time() + ($lockTime + 1) * 24 * 3600);
        Model\UserDepositBox::create([
            'deposit_box_id' => $depositBoxId,
            'user_id' => $userId,
            'amount' => $amount,
            'lock_start_time' => $startTime,
            'lock_end_time' => $endTime,
            'status' => 1, // 未提取
        ]);

        // 添加余币宝收支
        $flag = Model\DepositFinance::create([
            'user_id' => $userId,
            'type' => 1, // 购买余币宝
            'deposit_box_id' => $depositBoxId,
            'amount' => $amount,
        ]);

        return $this->output();
    }

    public function cancelDepositOrder (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'depositOrderId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 验证用户是否登录
        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        // 查找订单
        $depositOrderData = Model\DepositOrder::where([
            ['id', $depositOrderId],
            ['user_id', $userId],
        ])->first();
        if (!$depositOrderData) {
            return $this->error(401);
        }

        if ($depositOrderData->status !== 0) {
            return $this->error(402);
        }

        Model\DepositOrder::where('id', $depositOrderId)->update(['status' => 2]);

        $depositBoxId = $depositOrderData->deposit_box_id;
        $orderAmount = $depositOrderData->order_amount;
        Model\DepositBox::where('id', $depositBoxId)->increment('remain_amount', $orderAmount);

        return $this->output();
    }

    public function getUserOrderList (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'status' => 'nullable|numeric',
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        $whereArr = array(array('user_id', $userId));

        if ($status >= 0) {
            $whereArr[] = array('deposit_order.status', $status);
        }

        $depositOrderModel = Model\DepositOrder::join('deposit_box', 'deposit_order.deposit_box_id', '=', 'deposit_box.id')
            ->join('project', 'deposit_box.proj_id', '=', 'project.id')
            ->join('token', 'project.token_id', '=', 'token.id')
            ->where($whereArr);

        $dataCount = $depositOrderModel->count();
        $offset = $perpage * ($pageno - 1);
        $depositOrderList = $depositOrderModel->select('token.name as tokenName', 'token.symbol as tokenSymbol', 'project.name_cn', 'project.logo_url', 'deposit_box.lock_time', 'deposit_box.interest_rate', 'deposit_order.id', 'deposit_order.created_at as orderTime', 'deposit_order.order_amount', 'deposit_order.status')
            ->orderBy('deposit_order.created_at', 'desc')->offset($offset)->limit($perpage)->get()->toArray();

        foreach ($depositOrderList as &$depositOrder) {
            $depositOrderId = $depositOrder['id'];
            $depositOrder['txHashList'] = Model\OrderTxRecord::where([
                ['tx_type', 2],
                ['target_id', $depositOrderId]
            ])->pluck('tx_hash');
        }

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $depositOrderList,
        ]);
    }

    public function getUserDepositBoxList (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        // 更新到期的余币宝状态
        Model\UserDepositBox::where([
            ['user_id', 'user_id'],
            ['lock_end_time', '<=', date('Y-m-d H:i:s', time())],
        ])->update(['status' => 2]);

        $offset = $perpage * ($pageno - 1);
        $userDepositModel = Model\UserDepositBox::from('user_deposit_box as A')
            ->join('deposit_box as B', 'A.deposit_box_id', '=', 'B.id')
            ->join('project as C', 'B.proj_id', '=', 'C.id')
            ->join('token as D', 'C.token_id', '=', 'D.id')
            ->where('user_id', $userId);
        $dataCount = $userDepositModel->count();
        $dataList = $userDepositModel
            ->select('C.logo_url', 'C.name_cn', 'D.symbol', 'B.interest_rate', 'B.lock_time', 'A.amount', 'A.lock_start_time', 'A.lock_end_time', 'A.created_at as buyTime', 'A.status')
            ->offset($offset)->limit($perpage)->get()->toArray();

        // 换算剩余时间
        foreach ($dataList as &$data) {
            // 换算剩余时间
            $remainTime= strtotime($data['lock_end_time']) - strtotime(date('Y-m-d 00:00:00', time() + 24 * 3600));
            $data['remainTime'] = $remainTime >= 0 ? $remainTime / (24 * 3600) : 0;
        }

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $dataList,
        ]);
    }

    public function getUserDepositAsset (Request $request) {

        // 获取请求参数
        //$params = $this->validation($request, [
            //'projId' => 'required|numeric',
        //]);
        //if ($params === false) {
            //return $this->error(100);
        //}
        //extract($params);

        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        // 获取项目余币宝id数组
        // $boxIdArr = Model\DepositBox::where('proj_id', $projId)->pluck('id');

        // 获取用户余币宝资产(美元)
        $tokenAssetList = Model\DepositFinance::from('deposit_finance as A')
            ->join('deposit_box as B', 'A.deposit_box_id', '=', 'B.id')
            ->join('project as C', 'B.proj_id', '=', 'C.id')
            ->join('token as D', 'C.token_id', '=', 'D.id')
            ->select('C.token_id as tokenId', DB::raw('SUM(A.amount) * D.price as tokenAsset'))
            ->where('user_id', $userId)
            ->groupBy('C.token_id')
            ->get()->toArray();
        $totalAsset = 0;
        foreach ($tokenAssetList as $tokenAsset) {
            $totalAsset += $tokenAsset['tokenAsset'];
        }

        // 获取用户总收益
        $tokenProfitList = Model\DepositFinance::from('deposit_finance as A')
            ->join('deposit_box as B', 'A.deposit_box_id', '=', 'B.id')
            ->join('project as C', 'B.proj_id', '=', 'C.id')
            ->join('token as D', 'C.token_id', '=', 'D.id')
            ->select('C.token_id as tokenId', DB::raw('SUM(A.amount) * D.price as tokenProfit'))
            ->where([['user_id', $userId], ['type', 2]])
            ->groupBy('C.token_id')
            ->get()->toArray();
        $totalProfit = 0;
        foreach ($tokenProfitList as $tokenProfit) {
            $totalProfit += $tokenProfit['tokenProfit'];
        }

        // 获取用户昨日总收益
        $lastDayTime = Date('Y-m-d 00:00:00', time());
        $tokenProfitList = Model\DepositFinance::from('deposit_finance as A')
            ->join('deposit_box as B', 'A.deposit_box_id', '=', 'B.id')
            ->join('project as C', 'B.proj_id', '=', 'C.id')
            ->join('token as D', 'C.token_id', '=', 'D.id')
            ->select('C.token_id as tokenId', DB::raw('SUM(A.amount) * D.price as tokenProfit'))
            ->where([['user_id', $userId], ['type', 2], ['A.created_at', '>=', $lastDayTime]])
            ->groupBy('C.token_id')
            ->get()->toArray();
        $lastDayProfit = 0;
        foreach ($tokenProfitList as $tokenProfit) {
            $lastDayProfit += $tokenProfit['tokenProfit'];
        }

        return $this->output([
            'totalAsset' => $totalAsset,
            'totalProfit' => $totalProfit,
            'lastDayProfit' => $lastDayProfit,
        ]);
    }

    public function getUserDepositFinanceList (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'perpage' => 'required|numeric',
            'pageno' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 获取用户ID
        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        $depositFinanceModel = Model\DepositFinance::from('deposit_finance as A')
            ->join('deposit_box as B', 'A.deposit_box_id', '=', 'B.id')
            ->join('project as C', 'B.proj_id', '=', 'C.id')
            ->join('token as D', 'C.token_id', '=', 'D.id')
            ->select('C.logo_url', 'A.type', 'A.created_at', 'A.amount', 'D.price')
            ->where('user_id', $userId);

        $offset = $perpage * ($pageno - 1);
        $dataCount = $depositFinanceModel->count();
        $dataList = $depositFinanceModel->orderBy('created_at', 'desc')
            ->offset($offset)->limit($perpage)->get()->toArray();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $dataList,
            'typeDict' => DictUtil::DepositFinance_Type,
        ]);
    }

    public function getUserDepositProfitList (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'perpage' => 'required|numeric',
            'pageno' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 获取用户ID
        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        $DepositFinanceModel = Model\DepositFinance::from('deposit_finance as A')
            ->join('deposit_box as B', 'A.deposit_box_id', '=', 'B.id')
            ->join('project as C', 'B.proj_id', '=', 'C.id')
            ->join('token as D', 'C.token_id', '=', 'D.id')
            ->select('C.logo_url', 'D.symbol', 'D.price', 'A.amount', 'A.created_at')
            ->where([['user_id', $userId], ['type', 2]]);

        $dataCount = $DepositFinanceModel->count();
        $offset = $perpage * ($pageno - 1);
        $dataList = $DepositFinanceModel->orderBy('A.created_at', 'desc')
            ->offset($offset)->limit($perpage)->get()->toArray();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $dataList
        ]);
    }
}
