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
            'mobile' => 'required|numeric',
            'nation' => 'nullable|numeric'
        ]);
        if($params === false){
            return $this->error(100);
        }
        extract($params);
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
            'mobile' =>'required|numeric',
            'passwd' =>'required',
            'repasswd' => 'required',
        ]);
        if($params === false){
            return $this->error(100);
        }
        if (strlen($passwd) < 6 || strlen($repasswd) > 20) {
            return $this->error(205);
        }
        if (strcmp($passwd,$repasswd) != 0 ){
            return $this->error(205);
        }
        $flag = Model\User::where('mobile', $mobile)->update(['passwd' => $passwd]);
        if ($flag === 0) {
            return $this->error(203);
        }

        return $this->output();
    }

    public function getUserAsset (Request $request) {
        $params = $this->validation($request,[
            'pageno' =>'required|numeric',
            'perpage' =>'required|numeric',
        ]);
        if($params === false){
            return $this->error(100);
        }

        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        $userAssetModel = Model\UserAsset::join('token', 'user_asset.token_id', '=', 'token.id')
            ->where('user_asset.id', $userId)
            ->select('token.logo_url', 'token.symbol', 'token.price', 'user_asset.amount', 'user_asset.id');
        $dataCount = $userAssetModel->count();
        $offset = $perpage * ($pageno - 1);
        $dataList = $userAssetModel->offset($offset)->limit($perpage)->get()->toArray();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $dataList,
        ]);
    }

    public function getUserWallet (Request $request) {
        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }
        $userWalletList = Model\UserWallet::where('user_id', $userId)->get()->toArray();

        return $this->output([
            'dataList' => $userWalletList,
        ]);
    }

    public function withdraw (Request $request) {
        $params = $this->validation($request,[
            'assetId' =>'required|numeric',
            'walletAddr' => 'required|string',
        ]);
        if($params === false){
            return $this->error(100);
        }

        // 获取用户ID
        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        // 获取用户资产
        $userAssetData = Model\UserAsset::join('token', 'user_asset.token_id', '=', 'token.id')
            ->select('user_asset.amount', 'token.symbol', 'token.protocol')
            ->where([['user_id', $userId], ['id', $assetId]])->first();
        if ($userAssetData == null) {
            return $this->error(208);
        }
        $tokenProtocol = $userAssetData->protocol;
        $tokenSymbol = $userAssetData->symbol;
        $amount = $userAssetData->amount;
        if ($tokenProtocol !== 'ERC20') {
            return $this->error(103);
        }

        // 保存用户钱包地址
        Model\UserAddr::firstOrCreate([
            'user_id' => $userId,
            'token_protocol' => $protocol,
            'addr' => $walletAddr,
        ]);

        // 调用接口提现
        $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/withdraw', [
            'toAddr' => $walletAddr,
            'amount' => $amount,
            'tokenSymbol' => $tokenSymbol,
        ]);
        
        $resArr = json_decode($resJson, true);
        if (!$resArr || $resArr['errcode'] !== 0) {
            return $this->error(307);
        }

        return $this->output();
    }

    public function getUserTxRecord (Request $request) {
        $params = $this->validation($request,[
            'papeno' =>'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if($params === false){
            return $this->error(100);
        }

        // 获取用户ID
        $userId = Auth::getUserId();
        if (!$userId) {
            return $this->error(207);
        }

        $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/getTransferRecord', [
            'toAddr' => $walletAddr,
            'amount' => $amount,
            'tokenSymbol' => $tokenSymbol,
        ]);
        
        $resArr = json_decode($resJson, true);
        if (!$resArr || $resArr['errcode'] !== 0) {
            return $this->error(307);
        }
    }
}
