<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;

class UserController extends Controller
{
    public function signup (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'account' => 'required|string',
            'passwd' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 验证是否重复注册
        $isExist = Model\User::where('account', $account)->count();
        if ($isExist) {
            return $this->error(201);
        }

        // 创建用户
        $userId = Model\User::insertGetId([
            'account' => $account,
            'passwd' => md5($passwd)
        ]);

        return $this->output(['userId' => $userId]);
    }

    public function signin (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'account' => 'required|string',
            'passwd' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $md5Passwd = md5($passwd);
        $userId = Model\User::where([['account', $account], ['passwd', $md5Passwd]])->value('id');
        if (!$userId) {
            return $this->error(202);
        }

        return $this->output(['userId' => $userId]);
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

    public function focusProject (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'userId' => 'required|numeric',
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 检验是否重复关注
        $isExist = Model\UserFocus::where([['user_id', $userId], ['proj_id', $projId]])->count();
        if ($isExist) {
            return $this->error(204);
        }

        Model\UserFocus::insert(['user_id' => $userId, 'proj_id' => $projId]);

        return $this->output();
    }

    public function viewProject (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'userId' => 'required|numeric',
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
}
