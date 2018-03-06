<?php

namespace App\Utils;

use Request;
//use Redis;
use Illuminate\Support\Facades\Redis;
use Validator;

class Service {

    //调试日志和系统日志分开
    private static $loggers = array();
    public static function log($msg, $file = 'debug') {
        if (empty(self::$loggers[$file])) {
            self::$loggers[$file] = new \Illuminate\Log\Writer(new \Monolog\Logger($file));
            self::$loggers[$file]->useFiles(storage_path()."/logs/{$file}.log");
        }
        return self::$loggers[$file]->info($msg);
        //不能改写默认日志，会同时写2个文件
        //Log::useFiles(storage_path()."/logs/{$file}.log");
    }

    //中美手机号格式校验
    public static function checkMobile($mobile, $nation = 86) {
        $nation = strlen($mobile) == 11 ? 86 : 1;
        $data = ['mobile' => $mobile];
        $reg = $nation == 86 ? 'regex:/^1[35789]\d{9}$/' : 'regex:/^\d{8,10}$/';
        $v = Validator::make($data, ['mobile' => $reg]);
        if ($v->fails()) {
            return false;
        }
        return true;
    }

    //获取加密密码
    public static function getPwd($pass) {
        return password_hash($pass.env('PASS_SALT'), PASSWORD_DEFAULT);
    }
    //校验密码
    public static function checkPwd($pass, $hash) {
        return password_verify($pass.env('PASS_SALT'), $hash);
    }

    //生成随机码
    public static function genRandChars($len = 6) { 
        //$chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ023456789'; 
        $chars = '0123456789'; 
        $password = ''; 
        for ($i = 0; $i < $len; $i++ ) { 
            $password .= $chars[mt_rand(0, strlen($chars) - 1)]; 
        } 
        return $password; 
    }

    //生成验证码
    public static function getVcode($type, $id) {
        if (empty($id)) {
            return array('err' => 1, 'msg' => 'no id');
        }
        if ($type == 'reg') {
            $vcode = self::genRandChars(6);
            Redis::set("{$type}_{$id}", $vcode);
            Redis::expire("{$type}_{$id}", 600);
            return array('err' => 0, 'data' => $vcode);
        } else {
            return array('err' => 1, 'msg' => 'invalid vcode type');
        }
    }
    //校验验证码
    public static function checkVCode($type, $id, $vcode) {
        if (!($type && $id && $vcode)) {
            return array('err' => 1, 'msg' => 'no vcode');
        }
        if (0 != strcasecmp($vcode, Redis::get("{$type}_{$id}"))) {
            return array('err' => 2, 'msg' => 'vilidation code is invalid');
        }
        return array('err' => 0);
    }

    public static function sms($mobile, $msg) {
        $ip = Request::getClientIp();
        $key = "lianbi_vcode_{$ip}";
        $num = Redis::get($key);
        if ($num > 10) { //每ip每分钟只能发10条
            return array('err' => 1, 'msg' => 'too many sms');
        }
        $ret = self::smsGuodu($mobile, $msg);
        if ($ret) {
            Redis::incr($key);
            Redis::expire($key, 60);
            return array('err' => 0);
        } else {
            return array('err' => 2, 'msg' => 'send sms failed');
        }
    }

    public static function smsGuodu($mobile, $msg) {
        $msg = iconv("UTF-8","GB2312//IGNORE", $msg);
        
        $param['OperID'] = config('services.sms.id');
        $param['OperPass'] = config('services.sms.pass');
        $param['SendTime'] = '';
        $param['ValidTime'] = '';
        $param['AppendID'] = '';
        $param['DesMobile'] = $mobile;
        $param['Content'] =  '[MSG]';
        $param['ContentType'] = 15;
        $api = 'http://qxtsms.guodulink.net:8000/QxtSms/QxtFirewall?'.urldecode(http_build_query($param));
        $api = str_replace('[MSG]',  urlencode($msg) , $api);
        $res = file_get_contents($api);

        return $res;
    }

}
