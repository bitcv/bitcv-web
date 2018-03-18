<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\Utils\BaseUtil;
use App\Utils\DictUtil;
use App\Utils\Auth;

class DispenseController extends Controller
{
    public function getTokenInfo (Request $request) {
        $params = $this->validation($request, [
            'contractAddr' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $contractAddr = strtolower($contractAddr);
        // 查询token数据库
        $tokenObj = Model\Token::where('contract_addr', $contractAddr)->first();
        if (!$tokenObj) {
            // 数据库中没有则抓取
            $url = "https://etherscan.io/searchHandler?t=t&term=$contractAddr";
            $result = file_get_contents($url);
            $result = json_decode($result, true);
            $isExist = false;
            foreach ($result as $item) {
                preg_match('/(0x.*?)\\s.*TOKEN: (.*) \\((.*)\\)/is', $item, $match);
                $resContractAddr = $match[1];
                if ($contractAddr === strtolower($resContractAddr)) {
                    $tokenName = $match[2];
                    $tokenSymbol = $match[3];
                    $isExist = true;
                    break;
                }
            }
            if (!$isExist) {
                return $this->error(213);
            }
            // 抓取到后填充值数据库
            $tokenObj = Model\Token::create([
                'name' => $tokenName,
                'symbol' => $tokenSymbol,
                'protocol' => 1,
                'contract_addr' => $contractAddr,
            ]);
        }

        return $this->output([
            'tokenSymbol' => $tokenObj->symbol,
            'tokenId' => $tokenObj->id,
        ]);
    }

    public function getDispenseWallet (Request $request) {
        $params = $this->validation($request, [
            'tokenProtocol' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 暂只支持ERC20和ETH
        if ($tokenProtocol != 1) {
            return $this->error(100);
        }

        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        $walletData = Model\UserDispenseWallet::where([
            ['user_id', $userId],
            ['token_protocol', $tokenProtocol],
        ])->first();
        if (!$walletData) {
            // 如果没有钱包则创建钱包
            $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/createWallet', array(
                'symbol' => 'eth',
            ));
            $resArr = json_decode($resJson, true);
            if (!$resArr || $resArr['errcode'] !== 0) {
                return $this->error();
            }

            $walletId = $resArr['data']['walletId'];
            $walletAddr = $resArr['data']['walletAddr'];

            $walletData = Model\UserDispenseWallet::create([
                'user_id' => $userId,
                'token_protocol' => $tokenProtocol,
                'wallet_id' => $walletId,
                'addr' => $walletAddr,
            ]);
        }

        return $this->output([
            'walletAddr' => $walletData->addr,
        ]);
    }


    public function addUserDispenseAsset (Request $request) {
        $params = $this->validation($request, [
            'tokenId' => 'required|numeric',
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

        // 验证tokenId是否存在
        $tokenObj = Model\Token::find($tokenId);
        if (!$tokenObj) {
            return $this->error(100);
        }
        if ($tokenObj->protocol != 1) {
            // 暂时只支持ERC20的token
            return $this->error(100);
        }

        Model\UserDispenseAsset::firstOrCreate([
            'user_id' => $userId,
            'token_id' => $tokenId,
        ]);

        // 获取以太坊的tokenId
        $tokenObj = Model\Token::where(['symbol' => 'ETH'])->first();
        $ethTokenId = $tokenObj->id;

        if ($tokenId != $ethTokenId) {
            // 添加手续费以太坊资产
            Model\UserDispenseAsset::firstOrCreate([
                'user_id' => $userId,
                'token_id' => $ethTokenId,
            ]);
        }

        return $this->output();
    }

    public function getDispenseBalance (Request $request) {

        $params = $this->validation($request, [
            'tokenProtocol' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        if ($tokenProtocol != 1) {
            // 暂时只支持ETH和ERC20的钱包
            return $this->error(100);
        }

        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        $walletData = Model\UserDispenseWallet::where([['user_id', $userId], ['token_protocol', $tokenProtocol]])
            ->first();
        if ($walletData != null) {
            // 更新用户资产信息
            $this->updateDispenseBalance($userId);
        }

        // 获取数据库中用户资产
        $assetList = Model\UserDispenseAsset::from('user_dispense_asset as A')
            ->join('token as B', 'A.token_id', '=', 'B.id')
            ->select('B.logo_url', 'B.symbol', 'A.amount', 'A.token_id')
            ->where('A.user_id', $userId)
            ->get()->toArray();

        return $this->output([
            'dataList' => $assetList,
        ]);
    }

    public function confirmDispense (Request $request) {

        // 获取请求参数
        //$params = $this->validation($request, [
            //'perpage' => 'required|numeric',
            //'pageno' => 'required|numeric',
        //]);
        //if ($params === false) {
            //return $this->error(100);
        //}
        //extract($params);

        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        // 获取session中代发列表
        session_start();
        $dispenseData = isset($_SESSION['dispenseData']) ? $_SESSION['dispenseData'] : null;
        if (!$dispenseData) {
            return $this->error(501);
        }
        $tokenId = $dispenseData['tokenId'];
        $dispenseList = $dispenseData['dispenseList'];

        // 统计所需的手续费和代币数
        $totalAmount = 0;
        $totalEth = 0;
        foreach ($dispenseList as $index => $data) {
            if ($data['status'] === 0) {
                $totalAmount += $data['amount'] * pow(10, 8);
                $totalEth += 0.0016;
            } else {
                unset($dispenseList[$index]);
            }
        }
        $totalAmount /= pow(10, 8);

        // 更新用户余额表
        $this->updateDispenseBalance($userId);

        // 确认用户手续费是否充足
        $tokenData = Model\Token::where('symbol', 'ETH')->first();
        $ethTokenId = $tokenData->id;
        $dispenseAssetData = Model\UserDispenseAsset::where([['user_id', $userId], ['token_id', $ethTokenId]])
            ->first();
        $ethBalance = $dispenseAssetData->amount;
        if ($ethBalance < $totalEth) {
            return $this->error(502);
        }

        // 确认用户代发币是否充足
        if ($tokenId == $ethTokenId) {
            // 如果代发的是以太坊
            if ($ethBalance < $totalEth + $totalAmount) {
                return $this->error(502);
            }
        } else {
            $dispenseAssetData = Model\UserDispenseAsset::where([['user_id', $userId], ['token_id', $tokenId]])
                ->first();
            $ethBalance = $dispenseAssetData->amount;
            if ($ethBalance < $totalEth) {
                return $this->error(502);
            }
        }

        // 获取代发通证信息
        $tokenData = Model\Token::find($tokenId);
        if (!$tokenData) {
            return $this->error();
        }
        $tokenSymbol = $tokenData->symbol;
        $tokenProtocol = $tokenData->protocol;
        $contractAddr = $tokenData->contract_addr;
        $decimal = $tokenData->decimal;

        // 获取用户代发钱包信息
        $walletData = Model\UserDispenseWallet::where([
            ['user_id', $userId],
            ['token_protocol', $tokenProtocol],
        ])->first();
        if ($walletData == null) {
            return $this->error(503);
        } else if ($walletData->status != 1) {
            // 更新钱包状态
            $walletId = $walletData->wallet_id;
            $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/getTaskStatus', [
                'walletId' => $walletId,
            ]);

            $resArr = json_decode($resJson, true);
            if (!$resArr || $resArr['errcode'] !== 0) {
                return $this->error(104);
            }

            $status = $resArr['data']['status'];
            if ($status == 0) {
                return $this->error(505);
            }
            $walletData->status = 1;
            $walletData->save();
        }

        $walletId = $walletData->wallet_id;

        // 锁定用户钱包
        $flag = Model\UserDispenseWallet::where([
            ['user_id', $userId],
            ['token_protocol', $tokenProtocol],
            ['status', 1],
        ])->update(['status' => 2]);
        if (!$flag) {
            // 钱包锁定失败
            return $this->error(504);
        }

        // 调用接口启动代发
        $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/addDispense', [
            'walletId' => $walletId,
            'tokenSymbol' => $tokenSymbol,
            'contractAddr' => $contractAddr,
            'decimal' => $decimal,
            'dispenseList' => $dispenseList,
        ]);
        
        $resArr = json_decode($resJson, true);
        if (!$resArr || $resArr['errcode'] !== 0) {
            // 调用接口失败，将用户资产钱包状态重置
            Model\UserDispenseWallet::where([
                ['user_id', $userId],
                ['token_id', $tokenId],
                ['token_protocol', $tokenProtocol],
                ['status', 2],
            ])->update(['status' => 1]);
            return $this->error(104);
        }

        // 保存任务信息
        $taskId = $resArr['data']['taskId'];
        Model\UserDispenseTask::create([
            'user_id' => $userId,
            'task_id' => $taskId,
            'status' => 1,
        ]);

        return $this->output(['taskId' => $taskId]);
    }

    public function getUserTaskList (Request $request) {
        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        // 获取未完成的任务进度
        $taskIdArr = Model\UserDispenseTask::where([['user_id', $userId], ['status', 1]])->pluck('task_id')->toArray();
        if ($taskIdArr != null) {
            // 调用接口更新状态
            $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/getTaskProcess', [
                'taskIdArr' => $taskIdArr,
            ]);

            $resArr = json_decode($resJson, true);
            if (!$resArr || $resArr['errcode'] !== 0) {
                return $this->error(104);
            }
            $taskList = $resArr['data']['dataList'];
            foreach ($taskList as $task) {
                $status = $task['process'] == 1 ? 2 : 1;
                $flag = Model\UserDispenseTask::where('task_id', $task['taskId'])
                    ->update(['process' => $task['process'], 'status' => $status]);
            }
        }

        $dataList = Model\UserDispenseTask::from('user_dispense_task as A')
            ->join('token as B', 'A.token_id', '=', 'B.id')
            ->where('A.user_id', $userId)
            ->select('A.task_id', 'A.process', 'A.status', 'B.symbol', 'B.logo_url')
            ->get()->toArray();

        return $this->output(['dataList' => $dataList]);
    }

    public function getDispenseList (Request $request) {
        // 获取请求参数
        $params = $this->validation($request, [
            'taskId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 判断taskId是否存在
        $taskObj = Model\UserDispenseTask::where('task_id', $taskId)->first();
        if (!$taskObj) {
            return $this->error(100);
        }

        $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/getDispenseList', [
            'taskId' => $taskId,
        ]);

        $resArr = json_decode($resJson, true);
        if (!$resArr || $resArr['errcode'] !== 0) {
            return $this->error(104);
        }
        $resData = $resArr['data'];

        return $this->output($resData);
    }

    private function updateDispenseBalance ($userId) {

        // 获取用户钱包地址
        $walletList = Model\UserDispenseWallet::where('user_id', $userId)
            ->get()->toArray();
        $walletDict = [];
        foreach ($walletList as $wallet) {
            $walletDict[$wallet['token_protocol']] = $wallet['addr'];
        }

        // 获取用户代发资产种类
        $assetList = Model\UserDispenseAsset::from('user_dispense_asset as A')
            ->join('token as B', 'A.token_id', '=', 'B.id')
            ->select('B.symbol', 'B.contract_addr', 'B.protocol', 'B.decimal', 'A.id')
            ->where('user_id', $userId)
            ->get()->toArray();

        // 更新资产余额
        foreach ($assetList as $assetData) {
            if ($assetData['protocol'] == 1 && $assetData['symbol'] === 'ETH') {
                $walletAddr = $walletDict[$assetData['protocol']];
                $resJson = @file_get_contents("https://api.etherscan.io/api?module=account&action=balance&address=$walletAddr&tag=latest&apikey=3IV46D5P2XE6PA7R9KCMJEIW1YR5VEECIU");
                $resArr = json_decode($resJson, true);
                if ($resArr && $resArr['status'] == 1) {
                    Model\UserDispenseAsset::where('id', $assetData['id'])->update([
                        'amount' => $resArr['result'] / pow(10, $assetData['decimal']),
                    ]);
                }
            } else if ($assetData['protocol'] == 1) {
                $walletAddr = $walletDict[$assetData['protocol']];
                $contractAddr = $assetData['contract_addr'];
                if (!$walletAddr || !$contractAddr) {
                    continue;
                }
                $resJson = @file_get_contents("https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=$contractAddr&address=$walletAddr&tag=latest&apikey=3IV46D5P2XE6PA7R9KCMJEIW1YR5VEECIU");
                $resArr = json_decode($resJson, true);
                if ($resArr && $resArr['status'] == 1) {
                    Model\UserDispenseAsset::where('id', $assetData['id'])->update([
                        'amount' => $resArr['result'] / pow(10, $assetData['decimal']),
                    ]);
                }
            }
        }
    }
}
