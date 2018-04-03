<?php
namespace App\Utils;
use Cookie;
use Illuminate\Support\Facades\Redis;

class AuthUser {
    //角色
    const R_ADMIN_PERMISSION = 1;
    const R_FINANCE = 2;
    const R_ASSEST = 3;
    const R_PERSONNEL = 4;
    const R_MEDIA = 5;

    //角色
    public static $AUTHROLES = array (
        self:: R_ADMIN_PERMISSION  => '系统管理员',
        self:: R_FINANCE           => '财务',
        self:: R_ASSEST            => '行政',
        self:: R_PERSONNEL         => '人事',
        self:: R_MEDIA             => '运营'
    );

    public static $menu = array(
        array ('icon' => 'el-icon-menu', 'p' => self:: R_MEDIA, 'url' => '/admin/project', 'text' => '项目管理', 'child' => array(
            array ('icon' => 'el-icon-menu', 'p' => self::R_MEDIA, 'url' => '/admin/project', 'text' => '项目管理'),
            array ('icon' => 'el-icon-menu', 'p' => self:: R_MEDIA, 'url' => '/admin/projdata', 'text' => '项目更新'),
            array ('icon' => 'el-icon-menu', 'p' => self:: R_MEDIA, 'url' => '/admin/mediareport', 'text' => '项目动态'),
        )),

        array ('icon' => 'el-icon-menu', 'p' => self:: R_MEDIA, 'url' => '/admin/social', 'text' => '社群配置' ,'child' => array(
            array ('icon' => 'el-icon-menu', 'p' => self:: R_MEDIA, 'url' => '/admin/social', 'text' => '社群配置'),
        )),

        array ('icon' => 'el-icon-menu', 'p' => self:: R_MEDIA, 'url' => '/admin/media', 'text' => '媒体配置' ,'child' => array(
            array ('icon' => 'el-icon-menu', 'p' => self:: R_MEDIA, 'url' => '/admin/media', 'text' => '媒体配置'),
            array ('icon' => 'el-icon-menu', 'p' => self:: R_MEDIA, 'url' => '/admin/data', 'text' => '内容运营'),
            array ('icon' => 'el-icon-menu', 'p' => self:: R_MEDIA, 'url' => '/admin/exchange', 'text' => '交易所管理'),
        )),

        array ('icon' => 'el-icon-menu', 'p' => self:: R_MEDIA, 'url' => '/admin/institution', 'text' => '机构管理' ,'child' => array(
            array ('icon' => 'el-icon-menu', 'p' => self:: R_MEDIA, 'url' => '/admin/institution', 'text' => '机构管理'),
            array ('icon' => 'el-icon-menu', 'p' => self:: R_MEDIA, 'url' => '/admin/perlist', 'text' => '成员配置'),
            array ('icon' => 'el-icon-menu', 'p' => self:: R_MEDIA, 'url' => '/admin/token', 'text' => '通证配置'),
        )),

        array ('icon' => 'el-icon-menu', 'p' => self:: R_FINANCE, 'url' => '/admin/finance', 'text' => '币财报' ,'child' => array(
            array ('icon' => 'el-icon-menu', 'p' => self:: R_FINANCE, 'url' => '/admin/finance', 'text' => '币财报'),
            array ('icon' => 'el-icon-menu', 'p' => self:: R_FINANCE, 'url' => '/admin/depositBox', 'text' => '余币宝'),
//            array ('icon' => 'el-icon-menu', 'p' => self:: R_FINANCE, 'url' => '/admin/daifabao', 'text' => '代发宝'),
        )),

        array ('icon' => 'el-icon-menu', 'p' => self:: R_PERSONNEL, 'url' => '/admin/user', 'text' => '用户管理', 'child' => array(
            array ('icon' => 'el-icon-menu', 'p' => self:: R_PERSONNEL, 'url' => '/admin/user', 'text' => '用户管理'),
            array ('icon' => 'el-icon-menu', 'p' => self:: R_PERSONNEL, 'url' => '/admin/authuser', 'text' => '后台用户'),
            array ('icon' => 'el-icon-menu', 'p' => self:: R_PERSONNEL, 'url' => '/admin/editor', 'text' => '运营人员'),
            array ('icon' => 'el-icon-menu', 'p' => self:: R_PERSONNEL, 'url' => '/admin/vcode', 'text' => '验证码'),
        )),

        array ('icon' => 'el-icon-menu', 'p' => self:: R_PERSONNEL, 'url' => '/admin/permission', 'text' => '权限管理', 'child' => array(
            array ('icon' => 'el-icon-menu', 'p' => self:: R_PERSONNEL, 'url' => '/admin/permission', 'text' => '权限管理'),
        )),

        array ('icon' => 'el-icon-menu', 'p' => 99, 'url' => '/admin/setting', 'text' => '个人中心' ,'child' => array (
            array ('icon' => 'el-icon-menu', 'p' => 99, 'url' => '/admin/setting', 'text' => '个人中心')
        )),
    );

    public static $uid = 0;
    public static $authuser = [];

    public static function setLogin($user) {
        $token = self::genToken($user['uid']);
        if (!$token) {
            return array('err' => 1, 'msg' => 'generate token failed');
        }
        $user['token'] = $token;
        Cookie::queue('authtoken', $token, 30*24*60); //cookie分钟
        self::$authuser = $user;
        self::$uid = $user['uid'];
        session(['authuinfo' => $user]);
        return array('err' => 0, 'data' => ['token'=>$token]);
    }

    public static function genToken($uid) {
        $token = substr(md5($uid.'BitCVAuthUser'.'_'.rand()), 0, 16);
        Redis::set("authtoken:$token", json_encode(['uid'=>$uid,'t'=>time()]));
        Redis::expire("authtoken:$token", 86400*30);
        return $token;
    }

    public static function setLogout() {
        Cookie::queue('authtoken', '', 0);
        return true;
    }
}