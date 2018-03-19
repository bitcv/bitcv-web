<?php

namespace App\Console;

use Binance\API;
use Faker\Provider\DateTime;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models as Model;
use Illuminate\Support\Facades\DB;
include "lib.php";
define('ACCOUNT_ID', '34253486'); // 你的账户ID
define('ACCESS_KEY','0b3ef77e-98c4d54b-c6982338-d9d08'); // 你的ACCESS_KEY
define('SECRET_KEY', 'f4169235-31a63cfe-b927bd53-4ce45'); // 你的SECRET_KEY

require_once (dirname(__FILE__) . '/OKCoin/OKCoin.php');
require 'php-binance-api.php';

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        //Commands\SendSMS::class,
        Commands\CrawlData::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command("crawl:data weibo")->everyMinute()->withoutOverlapping();
        //$schedule->command("crawl:data facebook")->everyMinute()->withoutOverlapping();
        //$schedule->command("crawl:data twitter")->everyMinute()->withoutOverlapping();
        //$schedule->command("crawl:data price")->everyMinute()->withoutOverlapping();
//        $schedule->call(function () {
//            $this->getCoinOfOk();
//        })->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {        
        require base_path('routes/console.php');
    }


    protected function crawlPrice(){
        //https://otcbtc.com/api/v2/tickers
        //http://api.huobipro.com/market/history/kline?preiod=1min&size=10&symbol=ethusdt
        //https://www.okex.com/api/v1/ticker.do?symbol=kcash_eth
        //https://www.bit-z.com/api_v1/ticker?coin=pxc_eth
        //http://data.gate.io/api2/1/ticker/doge_usdt
//        $data = self::curl('https://otcbtc.com/api/v2/tickers');
//        $fields = ['btc','eos','bch','gxs','usdt','zec','qtum','otb','dew','big','bnb','uip','fair','jex','mds',
//            'lrc','tnb','pix','nas','snt','wlk','kin','rct','credo','bkx','hmc','swftc','elf','yoyow','seer','mvc',
//            'mot','aix','qash'];
//        foreach ($fields as $field) {
//            $this->updPrice($field,$data[$field.'_eth']['ticker']['last']);
//        }
//        $ethData = self::curl('http://api.huobipro.com/market/history/kline?preiod=1min&size=10&symbol=ethusdt');
//        $this->updPrice('eth',$ethData['data'][0]['close']);
//        $kcashData = self::curl('https://www.okex.com/api/v1/ticker.do?symbol=kcash_eth');
//        $this->updPrice('kcash',$kcashData['ticker']['last']);
//        $pxcData = self::curl('https://www.bit-z.com/api_v1/ticker?coin=pxc_eth');
//        $this->updPrice('pxc',$pxcData['data']['last']);
//        $dogeData = self::curl('http://data.gate.io/api2/1/ticker/doge_usdt');
//        $this->updPrice('doge',$dogeData['last']);

        $bcvData = self::curl('https://api.aex.com/ticker.php?c=bcv&mk_type=usdt');
        $this->updPrice('bcv',$bcvData['ticker']['last']);
    }


    function curl($url){
        $userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.2) Gecko/2008070208 Firefox/3.0.1';
        $curlObj = curl_init();
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_URL, $url);
        curl_setopt($curlObj, CURLOPT_USERAGENT, $userAgent);
        $result = curl_exec($curlObj);
        $result = json_decode($result,true);
        curl_close($curlObj);
        return $result;
    }

    function updPrice($symbol,$price){
        if (Model\Token::where('symbol', strtoupper($symbol))->first()) {
            Model\Token::where('symbol', strtoupper($symbol))->update(['price' => $price]);
        } else {
            Model\Token::create([
                'symbol' => strtoupper($symbol),
                'price' => $price,
            ]);
        }
    }

    //币安
    function getKlineOfBinance(){
        $api_key = 'TiDyWAFtjd2lFsVyEdz8F537RAsRRKNgf2zhVpVEq38o4YigVSqC2pYLICrfSyGn';
        $secret_key = 'CFtlfzfMx2yC4dyQjsEqmxbgJ1nPkFoXy80ZeRqgZTvKKnxYrm0e3pCAA2J2aYRs';
        $api = new API($api_key,$secret_key);
        $data = $api->candlesticks('LTCBTC');
        foreach ($data as $res){
            Model\Kline::create([
                'post_time' => date('Y-m-d H:i:s', $data['openTime']/1000),
                'open' => $data['open'],
                'close' => $data['close'],
                'high' => $data['high'],
                'low' => $data['low'],
                'volume' => $data['volume'],
                'symbol' =>'btcusdt',
            ]);
        }
    }
    /*
     * [1520821200000] => Array
        (
            [open] => 0.01963900
            [high] => 0.01964800
            [low] => 0.01960600
            [close] => 0.01964500
            [volume] => 0.89575077
            [openTime] => 1520821200000
            [closeTime] => 1520821499999
        )
     * */

    //火币
    function getKlineOfHuobi(){
        $req = new \req();
        $datas = $req->get_history_kline('btcusdt','1day','200');
        foreach ($datas['data'] as $data){
            Model\Kline::create([
                'crawl_time' => $data['id'],
                'open' => $data['open'],
                'close' => $data['close'],
                'high' => $data['high'],
                'low' => $data['low'],
                'volume' => $data['vol'],
                'symbol' =>'btcusdt',

            ]);
        }
    }

    //OkEX
    function getKlineOfOk(){
        $API_KEY = '';
        $SECRET_KEY = '';
        $client = new \OKCoin(new \OKCoin_ApiKeyAuthentication($API_KEY,$SECRET_KEY));
        //获取比特币或莱特币的K线数据
        $params = array('symbol' => 'btc_usd', 'type' => '1day', 'size' => 5);
        $result = $client -> klineDataApi($params);

        foreach ($result as $data){
            Model\Kline::create([
                'post_time' => date('Y-m-d H:i:s', $data[0]/1000),
                'open' => $data[1],
                'close' => $data[4],
                'high' => $data[2],
                'low' => $data[3],
                'volume' => $data[5],
                'symbol' =>'btc_usd',
            ]);
        }

        //print_r($result[0]);
        /*
        [0] => 1520726400000  时间戳
        [1] => 9110.56        开
        [2] => 10093.64       高
        [3] => 8895.47        低
        [4] => 9879.52        收
        [5] => 96.6058        交易量／交易量转化 BTC或 LTC 数量 不确定
         * */
    }

    //ZB
    function getKlineOfZb(){

        $data = self::curl('http://api.zb.com/data/v1/kline?market=btc_usdt');
    }

    //BiBox
    function getKlineOfBiBox(){

        $API_Key = '812ce36a2007dd3d7c6ccfb45eb496aca473e8b8';
        $Secret = 'd8393b62e75cd6437aa73a8559d0c869adcd2f3f';
        $data = self::curl('https://api.bibox.com/v1/mdata?cmd=kline&pair=BIX_BTC&period=1min&size=10');
    }

    //Aex
    function getKlineOfAex(){
        $data = self::curl('https://api.aex.com/ticker.php?c=bcv&mk_type=usdt');
    }

    //Gate
    function getKlineOfGate(){
        $data = self::curl('http://data.gate.io/api2/1/tradeHistory/etc_usdt');
        print_r($data);
    }

    function getCoinOfOk(){

        $result = file_get_contents('https://support.okex.com/hc/zh-cn/sections/115000437971-%E5%B8%81%E7%A7%8D%E4%BB%8B%E7%BB%8D');
        $pages = '/<nav class=\"pagination\".*?>(.*?)<\/nav>/ism';

        $okex = array();
        $okex[0] = 'https://support.okex.com/hc/zh-cn/sections/115000437971-%E5%B8%81%E7%A7%8D%E4%BB%8B%E7%BB%8D';
        if (preg_match_all($pages, $result, $data)){
            $subdata = $data[1][0];
            $res = '/<a href=.*?>(.*?)<\/a>/ism';
            if(preg_match_all($res,$subdata,$lis)){
                foreach ($lis[0] as $key => $li){
                    //print_r(substr($li,'8',86));
                    //$okex[$key + 1] = 'https://support.okex.com'.substr($li,'9',68);
                }
            }
        }
        //print_r($okex);
        $reg = '/<ul class=\"article-list\".*?>(.*?)<\/ul>/ism';
        foreach ($okex as $ok){
            $result = file_get_contents($ok);
            if(preg_match_all($reg,$result,$data)){
                $subdata = $data[1][0];

                $res = '/<a href=.*?>(.*?)<\/a>/ism';
                if(preg_match_all($res,$subdata,$lis)){

                    foreach ( $lis[0] as $key => $li){
                        $article = self::cut('href="','" class',$li);
                        $symbol = self::cut('>','(',$li);
                        $result = file_get_contents('https://support.okex.com'.$article);

                        $reg = '/<section class=\"article-info\".*?>(.*?)<\/section>/ism';
                        if(preg_match_all($reg,$result,$data)){
                            //print_r($data);
                            $subdata = $data[1][0];
                            $res = '/<a href=.*?>(.*?)<\/a>/ism';
                            if(preg_match_all($res,$subdata,$lis)){

                                if( count($lis[1]) == 1){
                                    //$sym = $symbol;
                                    //$website = $lis[1][0];
                                    //$whitepaper = $lis[1][1];
                                    if (!Model\Project::where('name_cn',$symbol)->first()){
                                        Model\Project::create([
                                            'home_url' => $lis[1][0],
                                            'name_cn' => $symbol,
                                        ]);
                                    }

                                }elseif (count($lis[1]) == 2) {
                                    //$sym = $symbol;
                                    //$website = $lis[1][0];
                                    //$whitepaper = $lis[1][1];
                                    if (!Model\Project::where('name_cn', $symbol)->first()) {
                                        Model\Project::create([
                                            'name_cn' => $symbol,
                                            'home_url' => $lis[1][0],
                                            'white_paper_url' => $lis[1][1],
                                        ]);
                                    }

                                }
                                //print_r(count($lis[1]));
                                //$sym = $symbol;
                                //$website = $lis[1][0];
                                //$whitepaper = $lis[1][1];
                                //print_r($lis);
                                //print_r(count($lis[1]));
                            }
                        }
                    }
                }
            }
        }
    }

    function getCoinOfOtcbtc(){

        $result = file_get_contents('https://support.otcbtc.com/hc/zh-cn/sections/115000739411-%E5%B8%81%E7%A7%8D%E4%BB%8B%E7%BB%8D');
        $pages = '/<nav class=\"pagination\".*?>(.*?)<\/nav>/ism';

        $okex = array();
        $okex[0] = 'https://support.okex.com/hc/zh-cn/sections/115000437971-%E5%B8%81%E7%A7%8D%E4%BB%8B%E7%BB%8D';
        if (preg_match_all($pages, $result, $data)){
            $subdata = $data[1][0];
            $res = '/<a href=.*?>(.*?)<\/a>/ism';
            if(preg_match_all($res,$subdata,$lis)){
                foreach ($lis[0] as $key => $li){
                    //print_r(substr($li,'8',86));
                    //$okex[$key + 1] = 'https://support.okex.com'.substr($li,'9',68);
                }
            }
        }
        //print_r($okex);
        $reg = '/<ul class=\"article-list\".*?>(.*?)<\/ul>/ism';
        foreach ($okex as $ok){
            $result = file_get_contents($ok);
            if(preg_match_all($reg,$result,$data)){
                $subdata = $data[1][0];

                $res = '/<a href=.*?>(.*?)<\/a>/ism';
                if(preg_match_all($res,$subdata,$lis)){

                    foreach ( $lis[0] as $key => $li){
                        $article = self::cut('href="','" class',$li);
                        $symbol = self::cut('>','</',$li);
                        $result = file_get_contents('https://support.okex.com/hc/zh-cn/articles/360001679971-ONT-Ontology-Token-');

                        $reg = '/<section class=\"article-info\".*?>(.*?)<\/section>/ism';
                        if(preg_match_all($reg,$result,$data)){
                            //print_r($data);
                            $subdata = $data[1][0];
                            $res = '/<a href=.*?>(.*?)<\/a>/ism';
                            if(preg_match_all($res,$subdata,$lis)){

                                if( count($lis[1]) == 1){
                                    //$sym = $symbol;
                                    //$website = $lis[1][0];
                                    //$whitepaper = $lis[1][1];
                                    Model\Project::create([
                                        'home_url' => $lis[1][0],
                                    ]);
                                } elseif (count($lis[1]) == 2) {
                                    //$sym = $symbol;
                                    //$website = $lis[1][0];
                                    //$whitepaper = $lis[1][1];


                                    Model\Project::create([
                                        'home_url' => $lis[1][0],
                                        'white_paper_url' => $lis[1][1],
                                    ]);
                                }
                                //print_r(count($lis[1]));
                                //$sym = $symbol;
                                //$website = $lis[1][0];
                                //$whitepaper = $lis[1][1];
                                //print_r($lis);
                                //print_r(count($lis[1]));
                            }
                        }
                    }
                }
            }
        }
    }

    function cut($begin,$end,$str){
        $b = mb_strpos($str,$begin) + mb_strlen($begin);
        $e = mb_strpos($str,$end) - $b;
        return mb_substr($str,$b,$e);
    }




}



