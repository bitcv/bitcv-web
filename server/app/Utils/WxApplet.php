<?php

namespace App\Utils;

use Illuminate\Support\Facades\Redis;

include_once __DIR__ . '/WxAes/wxBizDataCrypt.php';

class WxApplet
{
    const APP_ID = 'wx94020807caef9cd3';
    const APP_SECRET = '21d07f1316624ad35e94d506549bd0ca';

    public static function login($code) {
        // 获取session_id
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='
            . self::APP_ID . '&secret='
            . self::APP_SECRET . '&js_code='
            . $code . '&grant_type=authorization_code';
        $resJson = file_get_contents($url);
        $resArr = json_decode($resJson, true);
        if (array_key_exists('errcode', $resArr)) return false;
        $sessionKey = $resArr['session_key'];
        $openId = $resArr['openid'];

        $redisKey = md5($openId . $sessionKey);
        Redis::set($redisKey, json_encode(['openId' => $openId, 'sessionKey' => $sessionKey]));

        return $redisKey;
    }

    public static function decypher($redisKey, $rawData, $iv) {
        $redisJson = Redis::get($redisKey);
        $redisArr = json_decode($redisJson, true);
        $sessionKey = $redisArr['sessionKey'];

        $pc = new \WXBizDataCrypt(self::APP_ID, $sessionKey);
        $errCode = $pc->decryptData($rawData, $iv, $data);
        if ($errCode !== 0) return false;
        $data = json_decode($data, true);

        return $data;
    }
}
