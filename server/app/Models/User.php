<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

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
}
