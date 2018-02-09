<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\Cookie;

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

        $whereArr = array(array('status', 2));

        if ($lockTime) {
            $whereArr[] = array('lock_time', $lockTime);
        }

        $depositBoxModel = Model\DepositBox::join('project', 'deposit_box.proj_id', '=', 'project.id')
            ->join('token', 'project.token_id', '=', 'token.id')
            ->where($whereArr);

        $dataCount = $depositBoxModel->count();
        $offset = $perpage * ($pageno - 1);
        $dataList = $depositBoxModel->select('token.name as tokenName', 'token.symbol as tokenSymbol', 'project.logo_url', 'deposit_box.id', 'deposit_box.min_amount', 'deposit_box.lock_time', 'deposit_box.remain_amount', 'deposit_box.interest_rate')
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
            'orderAmount' => 'required|numeric',
            'userAddr' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $userId = $_COOKIE['userId'];

        $depositBoxModel = Model\DepositBox::where('deposit_box.id', $depositBoxId)
            ->join('proj_wallet', 'deposit_box.proj_id', '=', 'proj_wallet.proj_id');
        $depositBoxData = $depositBoxModel->select('proj_wallet.addr as wallet_addr, deposit_box.remain_amount')->first();
        if (!$depositBoxData) {
            return $this->error(304);
        }
        if ($depositBoxData['remain_amount'] < $orderAmount) {
            return $this->error(305);
        }

        $depositBoxModel->decrement('remain_amount', $orderAmount);

        $depositOrderData = [
            'user_id' => $userId,
            'deposit_box_id' => $depositBoxId,
            'order_amount' => $orderAmount,
            'from_addr' => $userAddr,
            'to_addr' => $depositBoxData['wallet_addr'],
            'status' => 1,
        ];
        $depositOrderModel = Model\DepositOrder::create($orderData);

        return $this->output(['depositOrderId' => $depositOrderModel->id]);
    }

    public function getOrderTxRecordList (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'orderId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 获取订单信息
        $orderData = Model\Order::where('id', $orderId)->first();
        if (!$orderData) {
            return $this->error(306);
        }

        // 扫描钱包

        // 获取未确认的交易记录
        $tradeRecordList = Model\TxRecord::where(array(
            ['order_id', 0],
            ['from_addr', $orderData->from_addr],
            ['to_addr', $orderData->to_addr],
        ))->get()->toArray();

        return $this->output(['dataList' => $tradeRecordList]);
    }

    public function confirmOrder (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'orderId' => 'required|numeric',
            'txRecordIdList' => 'required|array',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 获取订单信息
        $orderModel = Model\order::where('id', $orderId);
        $orderData = $orderModel->first();
        if (!$orderData) {
            return $this->error(306);
        }

        $txRecordModel = Model\TxRecord::where([
            ['id', 'in', $txRecordIdList],
            ['order_id', 0],
            ['from_addr', $orderData->from_addr],
            ['to_addr', $orderData->to_addr],
        ]);

        $txAmountSum = $txRecordModel->sum('tx_amount');
        if ($txAmountSum !== $orderData->order_amount) {
            return $this->error(307);
        }

        // 更新交易记录信息
        $txRecordModel->update(['order_id' => $orderId]);
        $orderModel->update(['status' => 2]);
        if ($orderData->order_type === 1) {
            $targetId = $orderData->target_id;
            Model\DepositBox::where('id', $targetId)->update(['status' => 2]);
        }

        return $this->output();
    }

    public function getUserOrderList (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'orderType' => 'nullable|numeric',
            'status' => 'nullable|numeric',
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $userId = $_COOKIE['userId'];

        $whereArr = array(array('user_id', $userId));

        if ($status) {
            $whereArr[] = array('status', $status);
        }

        if ($orderType) {
            $whereArr[] = array('order_type', $orderType);
        }

        $orderModel = Model\Order::join('deposit_box', 'order.target_id', '=', 'deposit_box.id')
            ->join('project', 'deposit_box.proj_id', '=', 'project.id')
            ->join('token', 'project.token_id', '=', 'token.id')
            ->where($whereArr);

        $dataCount = $candyOrderModel->count();
        $offset = $perpage * ($pageno - 1);
        $candyOrderList = $candyOrderModel->select('token.name as tokenName', 'token.symbol as tokenSymbol', 'project.logo_url', 'deposit_box.lock_time', 'deposit_box.interest_rate', 'order.id', 'order.created_at as orderTime', 'order.order_amount')
            ->offset($offset)->limit($perpage)->get()->toArray();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $candyBoxList,
        ]);
    }
}
