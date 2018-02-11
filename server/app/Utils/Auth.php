<?php

namespace App\Utils;
use Cookie;
//use Redis;
use Illuminate\Support\Facades\Redis;

class Auth {
    
    public static $uid = 0;
    public static $user = [];

    public static function setLogout() {
        Cookie::queue('token', '', 0);
        return true;
    }

    //密码登录后更新登录信息
    public static function setLogin($user) {
        $token = self::genToken($user['id']);
        if (!$token) {
            return array('err' => 1, 'msg' => 'generate token failed');
        }
        $user['token'] = $token;
        Cookie::queue('token', $token, 30*24*60); //cookie分钟
        self::$user = $user;
        self::$uid = $user['id'];
        return array('err' => 0, 'data' => ['token'=>$token]);
    }

    public static function checkLogin() {
        //先从cookie获取，laravel会把加密cookie放到_REQUEST数组
        $token = Cookie::get('token');
        if (!$token) { // 移动端通过参数验证登录
            $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        }
        if (!$token) {
            return false;
        }
        $uid = self::getTokenUid($token);
        if (!$uid) {
            return false;
        }
        $userModel = new \App\Models\User();
        $user = $userModel->getUser($uid);
        if (!isset($user['id'])) {
            return false;
        }
        $user['token'] = $token;
        self::$user = $user;
        self::$uid = $uid;
        return true;
    }

    public static function getTokenUid($token) {
        $u = json_decode(Redis::get("token:$token"), true);
        if (!$u || !isset($u['uid'])) {
            return '';
        }
        return $u['uid'];
    }
    //未过期不要重复调用
    public static function genToken($uid) {
        $token = substr(md5($uid.'_'.rand()), 0, 16);
        Redis::set("token:$token", json_encode(['uid'=>$uid,'t'=>time()]));
        Redis::expire("token:$token", 86400*30);
        return $token;
    }

    public static function getUserId () {
        //先从cookie获取，laravel会把加密cookie放到_REQUEST数组
        $token = Cookie::get('token');
        if (!$token) { // 移动端通过参数验证登录
            $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        }
        if (!$token) {
            return false;
        }
        $uid = self::getTokenUid($token);
        if (!$uid) {
            return false;
        }
        return $uid;
    }
}
