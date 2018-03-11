<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\Cookie;
use App\Utils\Service;
use App\Utils\Auth;
//use Redis;
use Illuminate\Support\Facades\Redis;
use App\Utils\SMS;
use App\Utils\DictUtil;
use App\Utils\BaseUtil;

class UserController extends Controller
{

    //获取验证码
    public function vcode($mobile, $nation = 86) {
        $ret = Service::getVcode('reg', $mobile);
        if ($ret['err'] > 0) {
            $this->error(100);
        }
        if (strlen($mobile) == 11) {
            Service::sms($mobile, '【BitCV】您的验证码为'.$ret['data']);
        } else {
            SMS::sendVcode($mobile, $ret['data'], $nation);
        }
        return $this->output();
    }

    public function signup (Request $request) {
        // 获取请求参数
        $params = $this->validation($request, [
            'mobile' => 'required|numeric',
            'passwd' => 'required|string',
            'vcode' => 'required|string',
            'nation' => 'nullable|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $ret = Service::checkVCode('reg', $mobile, $vcode);
        if ($ret['err'] > 0) {
            return $this->error(206);
        }

        // 验证是否重复注册
        /*
        $isExist = Model\User::where('mobile', $mobile)->count();
        if ($isExist) {
            return $this->error(201);
        }
        */

        // 创建用户
        $userId = (new Model\User())->regUser($nation, $mobile, $passwd);
        if (!$userId) {
            return $this->error(201);
        }
        /*
        $userModel = Model\User::create([
            'mobile' => $mobile,
            'passwd' => Service::getPwd($passwd)
        ]);
        $userId = $userModel->id;
        */

        return $this->output(['userId' => $userId]);
    }

    public function signin (Request $request) {
        // 获取请求参数
        $params = $this->validation($request, [
            'mobile' => 'required|numeric',
            'passwd' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $userData = (new Model\User())->loginUser($mobile, $passwd);
        /*
        $md5Passwd = md5($passwd);
        $userData = Model\User::where([['mobile', $mobile], ['passwd', $md5Passwd]])->first();
        */
        if (!$userData) {
            return $this->error(202);
        }
        $uid = $userData->id;
        $user = (new Model\User())->getUser($uid);
        try {
            \App\Utils\Auth::setLogin($user);            
        } catch (\Exception $ex) {
            return $this->error(100);
        }
        //var_dump(Cookie::get('token'));exit;

        return $this->output([
            'userId' => $userData->id,
            'mobile' => $userData->mobile,
            'avatarUrl' => $userData->avatar_url
        ]);
    }

    public function signout (Request $request) {
        \App\Utils\Auth::setLogout();

        return $this->output();
    }

    public function getUserInfo (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'userId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $userData = Model\User::where('id', $userId)->first();
        if ($userData === null) {
            return $this->error(203);
        }

        return $this->output($userData);
    }

    public function toggleFocus (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }
        // 检验是否重复关注
        $userFocusData = Model\UserFocus::where([['user_id', $userId], ['proj_id', $projId]])->first();
        if ($userFocusData) {
            $status = $userFocusData->status ? 0 : 1;
            $flag = Model\UserFocus::where([['user_id', $userId], ['proj_id', $projId]])->update(['status' => $status]);
        } else {
            $status = 1;
            Model\UserFocus::create(['user_id' => $userId, 'proj_id' => $projId, 'status' => $status]);
        }

        return $this->output(['status' => $status]);
    }

    public function viewProject (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 项目浏览数+1
        $flag = Model\Project::where('id', $projId)->increment('view_times');
        if ($flag === 0) {
            return $this->error(301);
        }

        return $this->output();
    }

    public function updateUserInfo (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'userId' => 'required|numeric',
            'account' => 'string|min:6',
            'nickname' => 'string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $userData = [];
        if (!is_null($account)) $userData['account'] = $account;
        if (!is_null($nickname)) $userData['nickname'] = $nickname;

        $flag = Model\User::where('id', $userId)->update($userData);
        if ($flag === 0) {
            return $this->error(203);
        }

        return $this->output();
    }

    public function getVcode(Request $request){
        $params = $this->validation($request,[
            'mobile' => 'required|string',
            'nation' => 'nullable|numeric'
        ]);
        if($params === false){
            return $this->error(100);
        }
        extract($params);
        if (!$nation) {
            $userModel = Model\User::where('mobile', $mobile)->first();
            if ($userModel) {
                $nation = $userModel->nation;
            }
        }
        return self::vcode($mobile, $nation);
    }

    public function checkVcode(Request $request){
        $params = $this->validation($request,[
            'mobile' => 'required|numeric',
            'vcode' => 'required|numeric',
        ]);
        if($params === false){
            return $this->error(100);
        }
        extract($params);
        $ret = Service::checkVCode('reg', $mobile, $vcode);
        if ($ret['err'] > 0) {
            return $this->error(100);
        }
        return $this->output();
    }

    public function resetPwd(Request $request){
        $params = $this->validation($request,[
            'mobile' => 'required|numeric',
            'vcode' => 'required|string',
            'passwd' =>'required|string',
            'nation' => 'nullable|numeric',
        ]);
        if($params === false){
            return $this->error(100);
        }
        extract($params);

        if (!$nation) {
            $nation = 86;
        }

        $ret = Service::checkVCode('reg', $mobile, $vcode);
        if ($ret['err'] > 0) {
            return $this->error(206);
        }
        if (strlen($passwd) < 6) {
            return $this->error(205);
        }

        $passwd = Service::getPwd($passwd);

        $flag = Model\User::where('mobile', $mobile)->update(['passwd' => $passwd]);
        if ($flag === 0) {
            return $this->error(203);
        }

        return $this->output();
    }

    public function getUserAsset (Request $request) {
        $params = $this->validation($request, [
            'pageno' =>'required|numeric',
            'perpage' =>'required|numeric',
        ]);
        if($params === false){
            return $this->error(100);
        }
        extract($params);

        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        // 更新用户转账记录
        $this->updateUserAsset($userId);

        $userAssetModel = Model\UserAsset::join('token', 'user_asset.token_id', '=', 'token.id')
            ->where('user_asset.user_id', $userId)
            ->select('token.logo_url', 'token.protocol', 'token.symbol', 'token.price', 'user_asset.amount', 'user_asset.id', 'user_asset.status');
        $dataCount = $userAssetModel->count();
        $offset = $perpage * ($pageno - 1);
        $dataList = $userAssetModel->offset($offset)->limit($perpage)->get()->toArray();

        // 获取钱包地址
        foreach ($dataList as &$data) {
            $walletData = Model\UserWallet::where([['user_id', $userId], ['token_protocol', $data['protocol']]])
                ->select('addr')->first();
            if ($walletData == null) {
                $data['walletAddr'] = '';
            } else {
                $data['walletAddr'] = $walletData['addr'];
            }
        }

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $dataList,
            'protocolDict' => DictUtil::Token_Protocol,
            'statusDict' => DictUtil::UserAsset_Status,
            'enstatusDist' => DictUtil::UserAsset_EnStatus
        ]);
    }

    public function getUserWallet (Request $request) {

        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        // 
        $userWalletList = Model\UserWallet::where('user_id', $userId)->get()->toArray();

        return $this->output([
            'dataList' => $userWalletList,
        ]);
    }

    public function addUserWallet (Request $request) {
        $params = $this->validation($request,[
            'tokenProtocol' => 'required|numeric',
            'walletAddr' => 'required|string',
            'mobile' => 'required|string',
            'vcode' => 'required|string',
        ]);
        if($params === false){
            return $this->error(100);
        }
        extract($params);
        // 正则校验钱包地址
        if ($tokenProtocol == 1) {
            // ERC20钱包地址校验
            $walletAddr = strtolower($walletAddr);
            $reg = '/^0x[0-9a-f]{40}$/';
            if (!preg_match($reg, $walletAddr)) {
                return $this->error(212);
            }
        } else if ($tokenProtocol == 2) {
            // 比特币钱包地址校验
            $balance = @file_get_contents("https://blockchain.info/q/addressbalance/$walletAddr");
            if (!is_numeric($balance)) {
                return $this->error(212);
            }
        } else if ($tokenProtocol == 3) {
            // 狗狗币钱包地址校验
            $result = @file_get_contents("https://dogechain.info/api/v1/address/balance/$walletAddr");
            if ($result === false) {
                return $this->error(212);
            }
        } else if ($tokenProtocol == 4) {
            // NEO钱包地址校验
            $data = [
                'method' => 'validateaddress',
                'params' => [$walletAddr],
                'jsonrpc' => '2.0',
                'id' => 1,
            ];
            echo 'start';exit;
            $resJson = \App\Utils\BaseUtil::curlPost('http://139.219.98.23:10332', $data);
            $resArr = json_decode($resJson, true);
            if (!isset($resArr['result']['isvalid']) || !$resArr['result']['isvalid']) {
                return $this->error(212);
            }
        } else if ($tokenProtocol == 5) {
            // KCASH钱包地址校验
            $data = [
                'jsonrpc' => '2.0',
                'params' => [$walletAddr],
                'id' => 20,
                'method' => 'wallet_check_address',
            ];
            $resJson = self::post('http://40.125.205.96:34942/rpc', $data);
            $resArr = json_decode($resJson, true);
            if (!isset($resArr['result']) || !$resArr['result']) {
                return $this->error(212);
            }
        } else {
            return $this->error(100);
        }
        // 获取用户ID
        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }
        // 验证手机号和用户ID是否一致
        $isExist = Model\User::where([['id', $userId], ['mobile', $mobile]])->count();
        if (!$isExist) {
            return $this->error(100);
        }
        // 验证验证码
        $ret = Service::checkVCode('reg', $mobile, $vcode);
        if ($ret['err'] > 0) {
            return $this->error(206);
        }
        // 验证钱包地址是否为其它用户所有
        $isExist = Model\UserWallet::where('addr', $walletAddr)->count();
        if ($isExist) {
            return $this->error(210);
        }
        // 验证用户是否已经有此类型的钱包地址
        $isExist = Model\UserWallet::where([['user_id', $userId], ['token_protocol', $tokenProtocol]])->count();
        if ($isExist) {
            return $this->error(211);
        }
        // 添加用户钱包地址
        Model\UserWallet::create([
            'user_id' => $userId,
            'token_protocol' => $tokenProtocol,
            'addr' => $walletAddr,
        ]);

        return $this->output();
    }

    public function withdraw (Request $request) {
        $params = $this->validation($request,[
            'assetId' =>'required|numeric',
        ]);
        if($params === false){
            return $this->error(100);
        }

        extract($params);

        // 获取用户ID
        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        // 获取用户资产
        $userAssetData = Model\UserAsset::join('token', 'user_asset.token_id', '=', 'token.id')
            ->select('user_asset.amount', 'token.id as tokenId', 'token.symbol', 'token.protocol')
            ->where([['user_asset.user_id', $userId], ['user_asset.id', $assetId], ['user_asset.status', 1]])->first();
        if ($userAssetData == null) {
            return $this->error(208);
        }
        $tokenId = $userAssetData->tokenId;
        $tokenProtocol = $userAssetData->protocol;
        $tokenSymbol = strtoupper($userAssetData->symbol);
        $amount = $userAssetData->amount;

        // 检查是否已开放提现
        $tokenSymbolArr = ['BCV', 'EOS', 'PXC', 'ICST', 'ETH', 'BTC', 'DOGE', 'KCASH', 'NEO'];
        if (!in_array($tokenSymbol, $tokenSymbolArr)) {
            return $this->error(103);
        }

        // 获取用户钱包地址
        $userWalletData = Model\UserWallet::where([['user_id', $userId], ['token_protocol', $tokenProtocol]])
            ->first();
        if ($userWalletData == null) {
            return $this->error(209);
        }
        $walletAddr = $userWalletData['addr'];

        // 更改用户资产状态
        $flag = Model\UserAsset::where([['id', $assetId], ['status', 1]])->update(['status' => 2]);
        if ($flag !== 1) {
            return $this->error(208);
        }

        // 调用提现接口
        $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/withdraw', [
            'toAddr' => $walletAddr,
            // 如果是NEO则发4倍的GAS
            'amount' => $tokenSymbol === 'NEO' ? $amount * 4 : $amount,
            'tokenSymbol' => $tokenSymbol === 'NEO' ? 'GAS' : $tokenSymbol,
        ]);
        
        $resArr = json_decode($resJson, true);
        if (!$resArr || $resArr['errcode'] !== 0) {
            // 调用接口失败，将用户资产状态重置
            Model\UserAsset::where([['id', $assetId], ['status', 2]])->update(['status' => 1]);
            return $this->error(104);
        }

        // 添加用户提现记录
        $transferRecordId = $resArr['data']['transferRecordId'];
        Model\UserTransferRecord::create([
            'user_id' => $userId,
            'record_id' => $transferRecordId,
            'asset_id' => $assetId,
            'token_id' => $tokenId,
            'amount' => $tokenSymbol === 'NEO' ? $amount * 4 : $amount,
            'status' => 1,
        ]);

        return $this->output();
    }

    public function getUserTransferRecord (Request $request) {
        $params = $this->validation($request,[
            'pageno' =>'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if($params === false){
            return $this->error(100);
        }
        extract($params);

        // 获取用户ID
        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        // 更新用户转账记录
        $this->updateUserAsset($userId);

        // 获取用户转账记录
        $recordModel = Model\UserTransferRecord::from('user_transfer_record as a')
            ->join('token as b', 'a.token_id', '=', 'b.id')
            ->where([['user_id', $userId]]);
        $dataCount = $recordModel->count();
        $offset = $perpage * ($pageno - 1);
        $dataList = $recordModel->select('a.id', 'a.amount', 'a.tx_hash', 'a.tx_time', 'a.status', 'b.logo_url', 'b.symbol')
            ->offset($offset)->limit($perpage)->get()->toArray();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $dataList,
            'statusDict' => DictUtil::UserTransferRecord_Status,
            'enstatusDict' => DictUtil::UserTransferRecord_EnStatus,
        ]);
    }

    private function updateUserAsset ($userId) {

        // 获取用户进行中的交易记录
        $recordIdArr = Model\UserTransferRecord::where([['user_id', $userId], ['status', 1]])->pluck('record_id');
        if ($recordIdArr == null) {
            return true;
        }

        // 获取用户最新交易
        $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/getUserTransferRecord', [
            'recordIdArr' => $recordIdArr,
        ]);

        $resArr = json_decode($resJson, true);
        if (!$resArr || $resArr['errcode'] !== 0) {
            return $this->error(104);
        }

        $recordList = $resArr['data']['dataList'];
        foreach ($recordList as $record) {
            // 转账成功
            if ($record['status'] == 4) {
                // 更新用户转账记录为转账成功
                $recordModel = Model\UserTransferRecord::where('record_id', $record['id'])->first();
                $assetId = $recordModel->asset_id;
                $recordModel->tx_hash = $record['txHash'];
                $recordModel->tx_time = $record['txTime'];
                $recordModel->status = 2;
                $recordModel->save();
                // 更新用户资产为提现成功
                $assetModel = Model\UserAsset::find($assetId);
                $tokenModel = Model\Token::find($assetModel->token_id);
                $assetModel->status = 3;
                if ($tokenModel->symbol === 'NEO') {
                    $assetModel->amount = $assetModel->amount - $record['actualAmount'] / 4;
                } else {
                    $assetModel->amount = $assetModel->amount - $record['actualAmount'];
                }
                $assetModel->save();
            }
            // 转账失败
            if ($record['status'] == 5) {
                // 更新用户转账记录为转账失败
                $recordModel = Model\UserTransferRecord::where('record_id', $record['id'])->first();
                $assetId = $recordModel->asset_id;
                $recordModel->status = 3;
                $recordModel->save();
                // 更新用户资产为提现失败
                $assetModel = Model\UserAsset::find($assetId);
                $assetModel->status = 4;
                $assetModel->save();
            }
        }

        return true;
    }

    public static function post($url, Array $dataArr = []) {
        $dataJson = json_encode($dataArr);
        $length = strlen($dataJson);
        $curlObj = curl_init();
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_POST, 1);
        curl_setopt($curlObj, CURLOPT_URL, $url);
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $dataJson);
        $auth = "000000" . base64_encode('bitcv:bitcv#0304');
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array(
            "Content-type: application/json; charset=utf-8",
            "Content-length: $length",
            "Authorization: $auth"
        ));
        //curl_setopt($curlObj, CURLOPT_USERPWD, 'bitcv:bitcv#0304');

        $result = curl_exec($curlObj);
        curl_close($curlObj);
        return $result;
    }
}
        
