<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\Cookie;
use App\Utils\Service;
use App\Utils\Auth;
use Redis;
use App\Utils\WxApplet;

class WxController extends Controller
{

    public function updWxUserInfo (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'userId' => 'required|numeric',
            'groupId' => 'nullable|string',
            'inviterId' => 'required|numeric',
            'wxAccount' => 'required|string',
            'tokenProvider' => 'required|string',
            'tokenSymbol' => 'required|string',
            'job' => 'required|string',
            //'name' => 'required|string',
            'tokenAddr' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $wxUserData = [
            'wx_account' => $wxAccount,
            'token_provider' => $tokenProvider,
            'token_symbol' => $tokenSymbol,
            'job' => $job,
            //'name' => $name,
            'token_addr' => $tokenAddr,
        ];

        if ($inviterId) {
            $wxUserData['inviter_id'] = $inviterId;
        }

        if ($groupId) {
            $wxUserData['group_id'] = $groupId;
        }

        Model\WxUser::where('id', $userId)->update($wxUserData);

        return $this->output();
    }

    public function wxLogin (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'code' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $redisKey = WxApplet::login($code);
        if ($redisKey === false) {
            return $this->error();
        }

        return $this->output(['redisKey' => $redisKey]);
    }

    public function addWxUser (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'redisKey' => 'required|string',
            'iv' => 'required|string',
            'encryptedData' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $userData = WxApplet::decypher($redisKey, $encryptedData, $iv);
        if ($userData === false) {
            return $this->error();
        }

        $wxUserObj = Model\WxUser::updateOrCreate(
            ['open_id' => $userData['openId']], 
            ['nick_name' => $userData['nickName'], 'avatar_url' => $userData['avatarUrl']]
        );

        return $this->output([
            'userId' => $wxUserObj->id,
            'openId' => $userData['openId'],
            'nickName' => $userData['nickName'],
            'avatarUrl' => $userData['avatarUrl'],
        ]);
    }

    public function getWxGroupId (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'redisKey' => 'required|string',
            'iv' => 'required|string',
            'encryptedData' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $groupData = WxApplet::decypher($redisKey, $encryptedData, $iv);
        if ($groupData === false) {
            return $this->error();
        }

        return $this->output($groupData);
    }
}
