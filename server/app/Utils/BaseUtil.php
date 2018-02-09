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
}
