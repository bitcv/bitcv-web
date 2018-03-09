<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use Validator;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const ERROR = [
        '0' => ['errcode' => 0, 'errmsg' => '成功执行'],
        // 通用错误码
        '100' => ['errcode' => 100, 'errmsg' => '参数错误'],
        '101' => ['errcode' => 101, 'errmsg' => '未知错误'],
        '102' => ['errcode' => 102, 'errmsg' => '无权限'],
        '103' => ['errcode' => 103, 'errmsg' => '暂未开通'],
        '104' => ['errcode' => 104, 'errmsg' => 'api调用失败'],
        '110' => ['errcode' => 110, 'errmsg' => '文件名称错误'],
        // 用户错误码
        '201' => ['errcode' => 201, 'errmsg' => '用户名已注册'],
        '202' => ['errcode' => 202, 'errmsg' => '用户名或密码错误'],
        '203' => ['errcode' => 203, 'errmsg' => '用户不存在'],
        '204' => ['errcode' => 204, 'errmsg' => '重复关注'],
        '205' => ['errcode' => 205, 'errmsg' => '密码格式不正确'],
        '206' => ['errcode' => 206, 'errmsg' => '验证码错误'],
        '207' => ['errcode' => 207, 'errmsg' => '未登录'],
        '208' => ['errcode' => 208, 'errmsg' => '未找到相关资产'],
        '209' => ['errcode' => 209, 'errmsg' => '未找到钱包地址'],
        '210' => ['errcode' => 210, 'errmsg' => '钱包地址重复'],
        '211' => ['errcode' => 211, 'errmsg' => '已有此类型的钱包地址'],
        '212' => ['errcode' => 212, 'errmsg' => '钱包地址错误'],
        // 项目错误码
        '301' => ['errcode' => 301, 'errmsg' => '项目不存在'],
        '302' => ['errcode' => 302, 'errmsg' => '社交ID不存在'],
        '303' => ['errcode' => 303, 'errmsg' => '媒体ID不存在'],
        '304' => ['errcode' => 304, 'errmsg' => '余币宝ID不存在'],
        '305' => ['errcode' => 305, 'errmsg' => '余币宝余额不足'],
        '306' => ['errcode' => 306, 'errmsg' => '订单ID不存在'],
        '307' => ['errcode' => 307, 'errmsg' => '支付金额与订单金额不符'],
        '308' => ['errcode' => 308, 'errmsg' => '未填写合约地址'],
        '309' => ['errcode' => 309, 'errmsg' => '没有发现相关联的交易'],
        '310' => ['errcode' => 310, 'errmsg' => '资讯不存在'],
        // 余币宝相关错误码
        '401' => ['errcode' => 401, 'errmsg' => '未找到订单'],
        '402' => ['errcode' => 402, 'errmsg' => '无法取消该订单'],
        '403' => ['errcode' => 403, 'errmsg' => '订单额度小于起始额度'],
    ];

    public function validation(Request $request, Array $rules)
    {
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return false;
        } else {
            $params = $request->only(array_keys($rules));
            foreach ($params as $key => &$value) {
                if (strpos($rules[$key], 'numeric') !== false) {
                    $value = intval($value);
                }
            }
            return $params;
        }
    }

    public static function curlPost($url, $dataArr)
    {
        $dataJson = json_encode($dataArr);
        $length = strlen($dataJson);
        $curlObj = curl_init();
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_POST, 1);
        curl_setopt($curlObj, CURLOPT_URL, $url);
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $dataJson);
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array(
            "Content-type: application/json; charset=utf-8",
            "Content-length: $length"
        ));
        $result = curl_exec($curlObj);
        curl_close($curlObj);
        return $result;
    }

    public function output($data = [])
    {
        if (is_object($data)) {
            $data = $data->toArray();
        }
        if ($data === null) {
            return json_encode(self::ERROR[0], JSON_UNESCAPED_UNICODE);
        }
        $result = $this->arrayKeyToCamel($data);
        $rtnArr = self::ERROR[0];
        $rtnArr['data'] = $result;
        return json_encode($rtnArr, JSON_UNESCAPED_UNICODE);
    }

    public function error($errcode = 101, $errmsg = '')
    {
        $err = array_key_exists($errcode, self::ERROR) ? self::ERROR[$errcode] : self::ERROR[101];
        if ($errmsg) {
            $err['errmsg'] = $errmsg;
        }
        return json_encode($err, JSON_UNESCAPED_UNICODE);
    }

    public function arrayKeyToCamel (Array $array)
    {
        $newArray = array();
        foreach ($array as $key => $value) {
            $newKey = preg_replace_callback('/([-_]+([a-z]{1}))/i', function($matches){
                return strtoupper($matches[2]);
            }, $key);
            // 去掉浮点数字符串小数点后多余的0
            $newValue = is_numeric($value) && strpos($value, '.') !== false ? floatval($value) : $value;
            $newArray[$newKey] = is_array($newValue) ? $this->arrayKeyToCamel($newValue) : $newValue;
        }
        return $newArray;
    }

    public function arrayKeyToLine (Array $array)
    {
        $newArray = array();
        foreach ($array as $key => $value) {
            $newKey = preg_replace_callback('/([A-Z]{1})/', function($matches){
                return '_'.strtolower($matches[0]);
            }, $key);
            $newArray[$newKey] = is_array($value) ? $this->arrayKeyToCamel($value) : $value;
        }
        return $newArray;
    }
}
