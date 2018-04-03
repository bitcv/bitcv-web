<?php

namespace App\Utils;

class BaseUtil 
{
    public static function curlPost($url, Array $dataArr = []) {
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

    public static function bigDiv($number, $decimal) {
        $number = strtolower($number);
        if (strpos($number, 'e') !== false) {
            // 解析科学计数法
            $numArr = explode('e', $number);
            $number = $numArr[0];
            $decimal = $decimal - $numArr[1];
        }
        if (strpos($number, '.') !== false) {
            // 如果$number是小数，则变为整数处理
            $decimal += @strlen(@explode('.', $number)[1]);
            $number = str_replace('.', '', $number);
        }
        if ($decimal > 0) {
            $number = str_pad('', $decimal, '0') . $number;
            $integerPart = substr($number, 0, $decimal * -1);
            $decimalPart = substr($number, $decimal * -1);
            $retStr = $integerPart . '.' . $decimalPart;
            // 去掉左边多余的0
            $retStr = ltrim($retStr, '0');
            if (strpos($retStr, '.') === 0) {
                $retStr = '0' . $retStr;
            }
            // 去掉右边多余的0
            $retStr = rtrim(rtrim($retStr, '0'), '.');
        } else {
            $retStr = $number . str_pad('', $decimal * -1, '0');
            $retStr = ltrim($retStr, '0');
        }

        return $retStr;
    }
}
