<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Service;
use Redis;

class User extends Model
{
    protected $table = 'user';
    protected $guarded = [];

    public function getUser($uid) {
        $user = self::where('id', $uid)->first()->toArray();
        if (!$user) {
            return false;
        }
        $role = (array)DB::table("admin")->where('id', $uid)->first();
        if ($role) {
            $user['is_sys'] = $role['is_sys'];
            $user['proj_id'] = $role['proj_id'];
        } else {
            $user['is_sys'] = 0;
            $user['proj_id'] = 0;
        }
        return $user;
    }

    public function loginUser($mobile, $pass) {
        $user = self::where('mobile', $mobile)->first();
        if (!$user) {
            return false;
        }
        $key = 'pass_err_'.$mobile;
        //1分钟内密码尝试5次
        if (Redis::get($key) > 5) {
            return false;
        }
        if (md5($pass) == $user['passwd']) { //更新老的加密方式
            $passwd = Service::getPwd($pass);
            self::where('mobile', $mobile)->update(['passwd'=>$passwd]);
        } else {
            $hash = $user['passwd'];
            if (!Service::checkPwd($pass, $hash)) {
                Redis::incr($key);
                Redis::expire($key, 60);
                return false;
            }
        }
        return $user;
    }

    public function regUser($nation, $mobile, $passwd) {
        if (empty($mobile) || empty($passwd)) {
            return false;
        }
        if (strlen($mobile) == 11) {
            $nation = 86;
        }
        
        $u = self::where('mobile', $mobile)->first();
        if ($u) { // 经过了验证码校验，更新密码
            self::where('mobile', $mobile)->update([
                'nation' => $nation,
                'passwd' => Service::getPwd($passwd)
            ]);
            $userId = $u->id;
        } else {
            $userModel = self::create([
                'nation' => $nation,
                'mobile' => $mobile,
                'passwd' => Service::getPwd($passwd)
            ]);
            $userId = $userModel->id;
        }
        return $userId;
    }

}
