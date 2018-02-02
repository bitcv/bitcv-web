<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\Cookie;
use App\Utils\Service;

class UserController extends Controller
{

    //获取验证码
    public function vcode($mobile) {
        $ret = Service::getVcode('reg', $mobile);
        if ($ret['err'] > 0) {
            $this->error(100);
        }
        Service::sms($mobile, '【BitCV】您的验证码为'.$ret['data']);
        return $this->output();
    }

    public function signup (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'mobile' => 'required|numeric',
            'passwd' => 'required|string',
            'vcode' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $ret = Service::checkVCode('reg', $mobile, $vcode);
        if ($ret['err'] > 0) {
            return $this->error(100);
        }

        // 验证是否重复注册
        $isExist = Model\User::where('mobile', $mobile)->count();
        if ($isExist) {
            return $this->error(201);
        }

        // 创建用户
        $userModel = Model\User::create([
            'mobile' => $mobile,
            'passwd' => md5($passwd)
        ]);
        $userId = $userModel->id;

        // 发送验证邮件
        //$curTime = time();
        //$sig = md5('kingco' . $userId . $curTime . 'ucai');
        //$verifyUrl = "http://www.kingco.com/api/verifySignup?userId=$userId&timestamp=$curTime&sig=$sig";
        //$result = mail($account, 'test', 'test email');

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

        $md5Passwd = md5($passwd);
        $userData = Model\User::where([['mobile', $mobile], ['passwd', $md5Passwd]])->first();
        if (!$userData) {
            return $this->error(202);
        }
        $timestamp = time();
        $userSig = md5($userData->id . 'test' . $timestamp);
        $expireTime = time() + 3600 * 24;

        setcookie('userId', $userData->id, $expireTime);
        setcookie('timestamp', $timestamp, $expireTime);
        setcookie('userSig', $userSig, $expireTime);

        return $this->output([
            'userId' => $userData->id,
            'mobile' => $userData->mobile,
            'avatarUrl' => $userData->avatar_url
        ]);
    }

    public function signout (Request $request) {

        $expireTime = time() - 3600;
        setcookie('userId', '', $expireTime);
        setcookie('account', '', $expireTime);
        setcookie('avatarUrl', '', $expireTime);
        setcookie('userSig', '', $expireTime);

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

        $userId = $_COOKIE['userId'];
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
        ]);
        if($params === false){
            return $this->error(100);
        }
        extract($params);
        self::vcode($mobile);
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
            'userId' =>'required|numeric',
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
        $flag = Model\User::where('id', $userId)->update(['passwd' => $passwd]);
        if ($flag === 0) {
            return $this->error(203);
        }

        return $this->output();
    }
}
