<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\Cookie;

class CandyBoxController extends Controller
{
    public function getCandyBoxList (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'lockTime' => 'numeric|nullable',
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $whereArr = array(array('status', '=', 2));

        if ($lockTime) {
            $whereArr[] = array('lock_time', '=', $lockTime);
        }

        $candyBoxModel = Model\CandyBox::join('project', 'candy_box.proj_id', '=', 'project.id')
            ->join('token', 'project.token_id', '=', 'token.id')
            ->where($whereArr);

        $dataCount = $candyBoxModel->count();
        $offset = $perpage * ($pageno - 1);
        $candyBoxList = $candyBoxModel->select('token.name as tokenName', 'token.symbol as tokenSymbol', 'project.logo_url', 'candy_box.id', 'candy_box.min_amount', 'candy_box.lock_time', 'candy_box.remain_amount', 'candy_box.interest_rate')
            ->offset($offset)->limit($perpage)->get()->toArray();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $candyBoxList,
        ]);
    }

    public function addCandyOrder (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'candyBoxId' => 'required|numeric',
            'userAddr' => 'required|string',
            'orderAmount' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $userId = $_COOKIE['userId'];

        $candyBoxData = Model\CandyBox::join('project', 'candy_box.proj_id', '=', 'project.id')
            ->select('project.token_addr')
            ->where('candy_box.id', $candyBoxId)->first();
        if (!$candyBoxData) {
            return $this->error(304);
        }
        if ($candyBoxData['remain_amount'] < $orderAmount) {
            return $this->error(305);
        }

        Model\CandyBox::where('id', $candyBoxId)->decrement('remain_amount', $orderAmount);

        $candyOrderData = [
            'user_id' => $userId,
            'candy_box_id' => $candyBoxId,
            'order_amount' => $orderAmount,
            'user_wallet_addr' => $userAddr,
            'proj_wallet_addr' => $candyBoxData['token_addr'],
            'status' => 1,
        ];
        $candyOrderModel = Model\CandyOrder::create($candyOrderData);

        return $this->output(['candyOrderId' => $candyOrderModel->id]);
    }

    public function getTradeRecordList (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'candyOrderId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $userId = $_COOKIE['userId'];

        // 获取订单信息
        $candyOrderData = Model\CandyOrder::where([
            ['id', $candyOrderId],
            ['user_id', $userId],
        ])->first();
        if (!$candyOrderData) {
            return $this->error(306);
        }

        // 扫描钱包

        // 获取未确认的用户交易记录
        $tradeRecordList = Model\TradeRecord::where(array(
            ['candy_order_id', 0],
            ['user_addr', $candyOrderData->user_addr],
            ['proj_addr', $candyOrderData->proj_addr],
        ))->get()->toArray();

        return $this->output(['dataList' => $tradeRecordList]);
    }

    public function confirmCandyOrder (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'candyOrderId' => 'required|numeric',
            'tradeRecordIdList' => 'required|array',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $userId = $_COOKIE['userId'];

        // 获取订单信息
        $candyOrderModel = Model\CandyOrder::where([
            ['user_id', $userId],
            ['id', $candyOrderId],
        ]);
        $candyOrderData = $candyOrderModel->first();
        if (!$candyOrderData) {
            return $this->error(306);
        }

        $tradeRecordModel = Model\TradeRecord::where([
            ['id', 'in', $tradeRecordIdList],
            ['candy_order_id', 0],
            ['from_addr', $candyOrderData->user_addr],
            ['to_addr', $candyOrderData->proj_addr],
        ]);

        $recordAmountSum = $tradeRecordModel->sum('trade_amount');
        if ($recordAmountSum !== $candyOrderData->order_amount) {
            return $this->error(307);
        }

        // 更新交易记录信息
        $tradeRecordModel->update(['candy_order_id' => $candyOrderId]);
        $candyOrderModel->update(['status' => 2]);

        return $this->output();
    }

    public function getMyCandyOrderList (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'status' => 'numeric|nullable',
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $userId = $_COOKIE['userId'];

        $whereArr = array(array('user_id', '=', $userId));

        if ($status) {
            $whereArr[] = array('status', '=', $status);
        }

        $candyOrderModel = Model\CandyOrder::join('candy_box', 'candy_order.candy_box_id', '=', 'candy_box.id')
            ->join('project', 'candy_box.proj_id', '=', 'project.id')
            ->join('token', 'project.token_id', '=', 'token.id')
            ->where($whereArr);

        $dataCount = $candyOrderModel->count();
        $offset = $perpage * ($pageno - 1);
        $candyOrderList = $candyOrderModel->select('token.name as tokenName', 'token.symbol as tokenSymbol', 'project.logo_url', 'candy_box.lock_time', 'candy_box.interest_rate', 'candy_order.id', 'candy_order.created_at as orderTime', 'candy_order.order_amount')
            ->offset($offset)->limit($perpage)->get()->toArray();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $candyBoxList,
        ]);
    }
}
