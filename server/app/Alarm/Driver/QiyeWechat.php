<?php
namespace App\Alarm\Driver;


class QiyeWechat
{
    public $handler;
    public function __construct()
    {
    }

    public function send($data)
    {



        $e = new \Exception();
        $trace=$e->getTraceAsString();
        $userinfo=var_export($_REQUEST,true);
        if(isset($_SERVER['REQUEST_URI'])){
            $userinfo.="\n url:". $_SERVER['REQUEST_URI'];
        }
        $data=[
            'type'=>'bitcv_warn',
            'message'=>$data,
            'userinfo'=>$userinfo,
            'trace'=>$trace,
            'userid'=>'',
            'deptid'=>'',
            'tagid'=>''
        ];
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,'http://alarm.j.ubolixin.com/sendMessage');
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);
        curl_setopt($ch,CURLOPT_TIMEOUT,5);
        $result=curl_exec($ch);
        curl_close($ch);
    }
}
