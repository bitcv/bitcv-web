<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\Cookie;
use App\Utils\BaseUtil;

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

        $whereArr = array(array('deposit_box.status', 1));

        if ($lockTime) {
            $whereArr[] = array('lock_time', $lockTime);
        }

        $depositBoxModel = Model\DepositBox::join('project', 'deposit_box.proj_id', '=', 'project.id')
            ->join('token', 'project.token_id', '=', 'token.id')
            ->where($whereArr);

        $dataCount = $depositBoxModel->count();
        $offset = $perpage * ($pageno - 1);
        $dataList = $depositBoxModel->select('token.name as tokenName', 'token.symbol as tokenSymbol', 'project.logo_url', 'deposit_box.id', 'deposit_box.min_amount', 'deposit_box.lock_time', 'deposit_box.remain_amount', 'deposit_box.interest_rate', 'deposit_box.to_addr')
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
        //$userId = $_COOKIE['userId'];
        // temp
        $userId = 1;

        // 检查剩余额度是否充足
        $depositBoxData = Model\DepositBox::find($depositBoxId);
        if (!$depositBoxData) {
            return $this->error(304);
        }
        if ($depositBoxData['remain_amount'] < $orderAmount) {
            return $this->error(305);
        }

        // 创建订单
        Model\DepositBox::where('id', $depositBoxId)->decrement('remain_amount', $orderAmount);
        $orderData = [
            'user_id' => $userId,
            'deposit_box_id' => $depositBoxId,
            'order_amount' => $orderAmount,
            'from_addr' => $fromAddr,
            'to_addr' => $depositBoxData['to_addr'],
            'contract_addr' => $depositBoxData['contract_addr'],
            'status' => 0,
        ];
        $depositOrderModel = Model\DepositOrder::create($orderData);
        $orderData['id'] = $depositOrderModel->id;

        return $this->output($orderData);
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
        $resJson = BaseUtil::curlPost('localhost:9999/api/getTxRecordList', $postData);
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
            //'txRecordIdList' => 'required|array',
            'txRecordIdList' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // temp
        $txRecordIdList = json_decode($txRecordIdList, true);

        // 获取订单信息
        $depositOrderData = Model\DepositOrder::find($depositOrderId);
        if (!$depositOrderData) {
            return $this->error(306);
        }

        $postData = array(
            'fromAddr' => $depositOrderData->from_addr,
            'toAddr' => $depositOrderData->to_addr,
            'contractAddr' => $depositOrderData->contract_addr,
            //'orderAmount' => $depositOrderData->order_amount . '',
            // temp
            'orderAmount' => '10000',
            'txRecordIdList' => $txRecordIdList,
        );
        $resJson = BaseUtil::curlPost('localhost:9999/api/confirmTx', $postData);
        var_dump($resJson);
        $resArr = json_decode($resJson, true);
        if (!$resArr || $resArr['errcode'] !== 0) {
            return $this->error(307);
        }

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
        Model\DepositOrder::where('id', $depositOrderId)->update([
            'status' => 1,
            'finish_time' => date('Y-m-d H:i:s'),
        ]);

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
        //$userId = $_COOKIE['userId'];
        //temp
        $userId = 1;

        $whereArr = array(array('user_id', $userId));

        if ($status) {
            $whereArr[] = array('status', $status);
        }

        $depositOrderModel = Model\DepositOrder::join('deposit_box', 'deposit_order.deposit_box_id', '=', 'deposit_box.id')
            ->join('project', 'deposit_box.proj_id', '=', 'project.id')
            ->join('token', 'project.token_id', '=', 'token.id')
            ->where($whereArr);

        $dataCount = $depositOrderModel->count();
        $offset = $perpage * ($pageno - 1);
        $depositOrderList = $depositOrderModel->select('token.name as tokenName', 'token.symbol as tokenSymbol', 'project.logo_url', 'deposit_box.lock_time', 'deposit_box.interest_rate', 'deposit_order.id', 'deposit_order.created_at as orderTime', 'deposit_order.order_amount')
            ->offset($offset)->limit($perpage)->get()->toArray();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $depositOrderList,
        ]);
    }
}
