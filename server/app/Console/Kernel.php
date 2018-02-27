<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models as Model;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $this->facebookCurl("https://www.baidu.com");
        })->everyMinute();
    }

    protected function getIpList(){
        $iplist = file_get_contents('proxy.html');
        $pattern = "/tbody(.*)<\/tbody>/smi";
        if(preg_match_all($pattern,$iplist,$result)){
            $regip = "/td(.*?)<\/td>/smi";
            if(preg_match_all($regip,$result[1][0],$ips)){
                for($id = 8; $id < count($ips[0])/8 ; $id=$id+8){
                    $y = $id + 1;
                    $ipPort = self::cut("td>","<",$ips[0][$id]).":".self::cut("td>","<",$ips[0][$y]);
                    DB::insert('INSERT INTO proxy_ip (proxy_port) VALUES (?)
                    ON DUPLICATE KEY UPDATE proxy_port=VALUES(proxy_port)',[$ipPort]);
                }
            }
        }
    }

    function cut($begin,$end,$str){
        $b = mb_strpos($str,$begin) + mb_strlen($begin);
        $e = mb_strpos($str,$end) - $b;
        return mb_substr($str,$b,$e);
    }

    protected function projReportRobot () {

        //如果是特定的 link_url,遍历
//        $html = file_get_contents("https://mp.weixin.qq.com/profile?src=3&timestamp=1518079945&ver=1&signature=Ns*O1*XgfMswxhIOwwQ0DclOzfJdU7Cc4u3iwvJ8sDDHMRjWFwWQSsFUrqx6Jt9A8*6P1TvujFVDJ-BJQ*eJhw==");
//        preg_match_all('/msgList =(.*?)seajs/ism',$html,$matches);
//        $json = $matches[1][0];
//        $resultList = json_decode($json,true);
//        foreach ($resultList['list'] as $result) {
//            $con_url = $result['app_msg_ext_info']['content_url'];
//            //$con_url = $result['list'][0]['app_msg_ext_info']['content_url'];
//            $cut_url = explode('&amp', $con_url);
//            $sub_url = implode('&', $cut_url);
//
//            Model\ProjReport::where('id',1)->update(['link_url' => $sub_url]);
//        }

        $projReportList = Model\ProjReport::join('media', 'proj_report.media_id', '=', 'media.id')
            ->where('status', 0)
            ->select('proj_report.id', 'link_url', 'title_reg', 'release_time_reg', 'banner_url_reg', 'content_reg')
            ->get()->toArray();
        foreach ($projReportList as $projReport) {
            $projReportId = $projReport['id'];
            $linkUrl = $projReport['link_url'];
            $titleReg = $projReport['title_reg'];
            $releaseTimeReg = $projReport['release_time_reg'];
            $bannerUrlReg = $projReport['banner_url_reg'];
            $contentReg = $projReport['content_reg'];

            $html = file_get_contents($linkUrl);
            $html = str_replace("\n", '', $html);

            $projReportData = [];
            if(preg_match_all($titleReg, $html, $matches)){  
                $projReportData['title'] = $matches[1][0];
            }
            if(preg_match_all($releaseTimeReg, $html, $matches)){  
                $projReportData['release_time'] = $matches[1][0];
            }
            if(preg_match_all($bannerUrlReg, $html, $matches)){  
                $projReportData['banner_url'] = $matches[1][0];
            }
            if(preg_match_all($contentReg, $html, $matches)){  
                $content = trim($matches[1][0]);
                $projReportData['content'] = preg_replace("/<a[^>]*>(.*?)<\/a>/ism", "$1", $content);
            }

            if ($projReportData['title'] && $projReportData['content']) {
                $projReportData['status'] = 1;
                Model\ProjReport::where('id', $projReportId)->update($projReportData);
            } else {
                $projReportData['status'] = 2;
                Model\ProjReport::where('id', $projReportId)->update($projReportData);
            }
        }
    }


    protected function projReportWeibo(){

        $socialList = Model\ProjSocial::where('social_id', 6)->get()->toArray();
        foreach ($socialList as $socialItem){
            $keyword = trim(strrchr($socialItem['link_url'], '/'),'/');
            $content = file_get_contents('http://s.weibo.com/weibo/'.$keyword.'?topnav=1&wvr=6&b=1');
            //$content = file_get_contents("weibo.txt");
            $pattern = "/STK.pageletM.view\((.*?)\)<\/script>/smi";
            if(preg_match_all($pattern, $content, $result)) {
                $list = $result[1];
                foreach ($list as $item) {
                    $str = $item;
                    //  echo $item."\n";
                    $obj = json_decode($str);
                    if ($obj->pid == "pl_weibo_direct") {

                        $html = $obj->html;
                        $contentpattern = "/class=\"feed_action clearfix\">(.*?)<\!\-\-\/feed_detail\-\->/sim";
                        if (preg_match_all($contentpattern, $html, $result)) {

                            $weiboitem = array();
                            foreach ($result[1] as $content) {
                                $weiboitem = array();
                                $pattern = "/<p class=\"comment_txt\".*?>(.*?)<\/p>/ism";
                                if (preg_match_all($pattern, $content, $listitems)) {

                                    $weiboitem['content'] = strip_tags($listitems[1][0]);
                                    $weiboitem['content'] = str_replace("展开全文c", "", $weiboitem['content']);
                                }
                                $name_pattern = "/suda-data=\"key=tblog_search_weibo&value=weibo_ss_\d_[a-zA-Z0-9_]*icon\"\s+href=\"(.*?)\"\s+title=\"(.*?)\"(.*?)<img src=\"(.*?)\"\s+alt=\"(.*?)\"\s+width=\"50\"\s+height=\"50\"\s+class=\"W_face_radius\"/sim";
                                //$name_pattern = "/suda-data=\"key=tblog_search_weibo&value=weibo_ss_\d_icon\"\s+href=\"(.*?)\"\s+title=\"(.*?)\"(.*?)<img src=\"(.*?)\"\s+alt=\"(.*?)\"\s+width=\"50\"\s+height=\"50\"\s+class=\"W_face_radius\"/sim";
                                if (preg_match_all($name_pattern, $content, $nameitems)) {

                                    $weiboitem['homeurl'] = $nameitems[1][0];
                                    $weiboitem['name'] = $nameitems[2][0];
                                    $weiboitem['logo'] = $nameitems[4][0];
                                }

                                //$linkpattern = "/title\s+\-\-\>\s+<a\s+href=\"(.*?)\"\s+target=\"_blank\"\s+title=\"(.*?)\"\s+date=\"(.*?)\"\s+class=\"W_textb\"\s+node-type=\"feed_list_item_date/sim";
                                $linkpattern = "/title\s+\-\-\>\s+<a\s+href=\"(.*?)\"\s+target=\"_blank\"\s+title=\"(.*?)\"\s+date=\"(.*?)\"\s+[class=\"W_textb\"\s+]*node-type=\"feed_list_item_date/sim";

                                if (preg_match_all($linkpattern, $content, $linkitems)) {

                                    $weiboitem['url'] = $linkitems[1][0];
                                    $weiboitem['date'] = $linkitems[2][0];

                                }
                                if (strpos($content, "fl_unfold") !== false) {
                                    $actionpattern = "/action-type=\"fl_unfold\"\s+action-data=\"(.*?)\">/";
                                    if (preg_match_all($actionpattern, $content, $actitems)) {

                                        $weiboitem['unfold'] = $actitems[1][0];
                                    }
                                }

                                DB::insert('INSERT INTO crawler_socialnews (proj_id,social_id,official_name,title,logo_url,refer_url,post_time) VALUES (?,?,?,?,?,?,?)
                    ON DUPLICATE KEY UPDATE post_time=VALUES(post_time)',[$socialItem['proj_id'],$socialItem['social_id'],$weiboitem['name'],$weiboitem['content'],$weiboitem['logo'],$weiboitem['homeurl'],$weiboitem['date']]);

                            }
                        }
                    }
                }
            }
        }
    }


    protected function projReportWeChat(){

        $socialList = Model\ProjSocial::where('social_id', 5)->get()->toArray();
        $proxyList = Model\ProxyIp::get()->toArray();

        foreach ($socialList as $socialitem){
            $keyword = trim(strrchr($socialitem['link_url'], '/'),'/');
            $searchHtml = file_get_contents('http://weixin.sogou.com/weixin?type=1&s_from=input&query='.$keyword.'&ie=utf8');
            $pattern = '/account_name_0" href="(.*?)"></ism';

            if(preg_match_all($pattern,$searchHtml,$matches)) {
                $url = $matches[1][0];
                $url = str_replace("&amp;", "&", $url);
                $listHtml = self::CrawlerUrl($url, 1);
                print_r($listHtml);
                $pattern = '/var msgList = ({.*});/ism';
                if(preg_match_all($pattern, $listHtml, $matches)) {
                    $json = $matches[1][0];
                    $results = json_decode($json, true);

                    foreach ($results['list'] as $result) {
                        $msg = $result['app_msg_ext_info'];
                        $data['link_url'] = 'https://mp.weixin.qq.com' . str_replace('&amp;', '&', $msg['content_url']);
                        $data['content'] = $msg['digest'];
                        $data['title'] = $msg['title'];
                        $data['banner_url'] = $msg['cover'];
                        $data['created_at'] = $result['comm_msg_info']['datetime'];

                        DB::insert('INSERT INTO crawler_socialnews (proj_id,social_id,official_name,title,logo_url,post_time,refer_url) VALUES (?,?,?,?,?,?,?)
                            ON DUPLICATE KEY UPDATE post_time=VALUES(post_time)', [$socialitem['proj_id'], $socialitem['social_id'], $socialitem['link_url'], $data['title'], $data['banner_url'], date('Y-m-d H:i:s', $data['created_at']), $data['link_url']]);

                    }
                }
            }
        }
    }

    public function projReportWeiboHome(){

        $socialList = Model\ProjSocial::where('social_id', 6)->first();

        //$content = file_get_contents(__DIR__ . "/weibo.html");
        //$content = self::CrawlerUrl('https://weibo.com/bitcv?profile_ftype=1&is_all=1#_0',0);
        //print_r($content);
        $content = $this->weiBoCurl("https://weibo.com/bitcv?profile_ftype=1&is_all=1");
        //$pattern = "/FM.view\((.*?)\)<\/script>/smi";
        //print_r($content);
        $pattern = "/Pl_Official_MyProfileFeed__20.*?(.*?)<\/script>/smi";
        if(preg_match_all($pattern, $content, $result)){
            $list = $result[1];
            foreach($list as $item){
                $str = $item;
                $obj = json_decode($str);
                print_r($obj);
                $html =  $obj->html;
                $weiboitem = array();
                $pattern = "/node-type=\"feed_list_content\".*?>(.*?)<\/div>/ism";
                $name_pattern = "/<img usercard=.*?>(.*?)/ism";
                $linkpattern = "/<div class=\"WB_from S_txt2\">(.*?)suda-data=\"key=tblog_home_new&value=feed_time/ism";
                if(preg_match_all($pattern,$html,$listitems) && preg_match_all($name_pattern,$html,$nameitems) && preg_match_all($linkpattern,$html,$linkitems))
                {
                    for ($id = 0; $id < count($listitems); $id++) {
                        $data[$id]['content'] = $listitems[1][$id];
                        $data[$id]['title'] = $listitems[1][$id];
                        $data[$id]['banner_url'] = self::cut("src="," width",$nameitems[0][0]);
                        $data[$id]['post_time'] = self::cut("title=","date",$linkitems[0][0]);
                        print_r($data[$id]);
                        Model\CrawlerSocialNews::Create([
                            'proj_id' => $socialList['proj_id'],
                            'social_id' => $socialList['social_id'],
                            'title' => $data[$id]['title'],
                            'logo_url' => $data[$id]['banner_url'],
                            'created_at' => $data[$id]['post_time'],
                            'refer_url' => $socialList['link_url'],
                        ]);
                    }
                }
            }
        }
    }

    protected function Curl(){

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,"https://weibo.com/bitcv?profile_ftype=1&is_all=1#_0");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $err = curl_error($ch);
        print_r($output);
        if($output === FALSE ){
            echo "CURL Error:".curl_error($ch);
        }
        // 4. 释放curl句柄
        curl_close($ch);
    }

    protected function weiBoCurl($url){

        $curlObj = curl_init();
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_URL, $url);
        //curl_setopt($curlObj, CURLOPT_COOKIEFILE, './cookie.txt');
        //curl_setopt($curlObj, CURLOPT_COOKIEJAR, './cookie.txt');
        curl_setopt($curlObj, CURLOPT_COOKIE, 'TC-Page-G0=b05711a62e11e2c666cc954f2ef362fb; SUB=_2AkMtz-Ppf8NxqwJRmP4Rz2zkZY5_zAvEieKbkxIyJRMxHRl-yj9jqlEntRB6Bk_NBoqbm0-fsRxfzgOi9DUseKjp6bH_; SUBP=0033WrSXqPxfM72-Ws9jqgMF55529P9D9Whp22-9s1yfYi_ArQwMlw1g');
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array(
            "Content-type: application/json; charset=utf-8",
        ));
        $result = curl_exec($curlObj);
        curl_close($curlObj);
        return $result;

    }

    protected function  projReportFacebookHome()
    {
        $socialList = Model\ProjSocial::where('social_id', 2)->first();
        $html = file_get_contents('https://www.facebook.com/BitCapitalVendor');
        //https://www.facebook.com/BitCapitalVendor
        $reg = "/class=\"_3-95\"(.*)<\/script>/smi";
        //print_r($html);
        if (preg_match_all($reg, $html, $result)) {
            //$regContent = "/<p>(.*?)<\/p>/ism";
            //print_r($result);
            $regContent = "/_2vxa _3-95.*?(.*?)<\/div>/smi";
            //$regDate = "/data-utime=.*?(.*?)<\/abbr>/smi";
            $regName = "/class=\"_hil\".*?(.*?)img/smi";
            $reglogo = "/background-image.*?(.*?)background-position/smi";

            if (preg_match_all($regContent, $result[0][0], $contentItem)
                && preg_match_all($regName, $result[0][0], $nameItem) && preg_match_all($reglogo, $result[0][0], $logoItem)) {

                for ($id = 0; $id < count($contentItem[1]); $id++) {
                    $data[$id]['content'] = $contentItem[1][$id];
                    $data[$id]['title'] = cut(">", "<span", $nameItem[1][$id]);
                    $data[$id]['banner_url'] = cut("url(", ")", $logoItem[1][$id]);
                    print_r($data[$id]);
                }
            }
        }
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

    protected function Crawler($keyword,$proxyid){

        $proxyList = Model\ProxyIp::get()->toArray();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://weixin.sogou.com/weixin?type=1&s_from=input&query='.$keyword.'&ie=utf8",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 100,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
            CURLOPT_PROXY => $proxyList[$proxyid]['proxy_port'],
            CURLOPT_PROXYAUTH => CURLAUTH_BASIC,
            //CURLOPT_PROXYUSERPWD => '45501:641735',
            CURLOPT_USERAGENT => $this->UA(),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
            $id = rand(0,count($proxyList));
            print_r('url-sougou'.$id);
            print_r($keyword);
            $this->Crawler($keyword,$id);
        }
        curl_close($curl);
        return $response;
    }

    protected function CrawlerUrl($url,$proxyid){

        $proxyList = Model\ProxyIp::get()->toArray();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
            CURLOPT_PROXY => '118.120.223.52:8118',
            CURLOPT_PROXYAUTH => CURLAUTH_BASIC,
            //CURLOPT_PROXYUSERPWD => '45501:641735',
            CURLOPT_USERAGENT => $this->UA(),
        ));
        //$proxyList[$proxyid]['proxy_port']
        $response = curl_exec($curl);
        //print_r($response);
        $err = curl_error($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
            $id = rand(0,count($proxyList)-1);
            print_r("url-wechat".$id);
            //return $this->CrawlerUrl($url,$id);
        }
        curl_close($curl);
        return $response;
    }

    private function UA()
    {
        $userAgent = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.86 Safari/537.36',//Chrome
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36',//win7 chrome
            'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36',//360
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0',//firefox
            //'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        ];
        return $userAgent[rand(0, 3)];
    }


    protected function Facebook(){

        $socialList = Model\ProjSocial::where('social_id', 6)->get()->toArray();
        foreach ($socialList as $socialtem) {

            //$allContents = file_get_contents("bitcap.html");
            $allContents = file_get_contents($socialtem['link_url']);
            $patt = "/<body(.*?)<\/script>/smi";
            if (preg_match_all($patt, $allContents, $result)) {

                $portContent = $result[0][0];
                $patter = "/head(.*?)<\/script>/smi";
                if (preg_match_all($patter, $portContent, $result)) {
                    $allhtml = $result[0][0];

                    $pattern = "/class=\"_4-u2 _3xaf _3-95 _4-u8\"(.*)<\/div>/smi";
                    if (preg_match_all($pattern, $allhtml, $result)) {
                        $res = $result[0][0];
                    }
                    $res = $result[0][0];

                    $patContent = "/<p>(.*?)<\/p>/ism";
                    $patDate = "/data-utime=.*?(.*?)<\/abbr>/smi";
                    $patName = "/<span class=\"fwn\s+fcg\".*?>(.*?)<\/span>/smi";
                    $patlogo = "/_38vo\".*?>(.*?)<\/div>/smi";

                    if (preg_match_all($patContent, $res, $contentItem) && preg_match_all($patDate, $res, $dateItem)
                        && preg_match_all($patName, $res, $nameItem) && preg_match_all($patlogo, $res, $logoItem)) {

                        for ($id = 0; $id < count($contentItem[1]); $id++) {
                            $data[$id]['content'] = $contentItem[1][$id];
                            $data[$id]['title'] = $nameItem[1][$id];
                            $data[$id]['logo'] = str_replace('src=', 'alt', cut('src=', 'alt', $logoItem[0][$id]));
                            $data[$id]['date'] = cut("data-utime=\"", "\" data-shorten", $dateItem[0][$id]);

                            DB::insert('INSERT INTO crawler_socialnews (proj_id,social_id,official_name,title,logo_url,post_time,refer_url) VALUES (?,?,?,?,?,?,?)
                            ON DUPLICATE KEY UPDATE post_time=VALUES(post_time)', [$socialtem['proj_id'], $socialtem['social_id'], $socialtem['link_url'], $data[$id]['title'], $data[$id]['logo'], date('Y-m-d H:i:s', $data[$id]['date']), $socialtem['link_url']]);

                        }
                    }
                }
            }
        }
    }

    protected function Weibo(){

        $socialList = Model\ProjSocial::where('social_id', 6)->get()->toArray();
        foreach ($socialList as $socialtem) {
            //$content = $this->weiCurl("https://weibo.com/bitcv?profile_ftype=1&is_all=1");
            $content = $this->weiCurl($socialtem['link_url']);
            $pattern = "/FM.view\((.*?)\)<\/script>/smi";
            if (preg_match_all($pattern, $content, $result)) {
                $list = $result[1];
                //print_r($result[1]);
                foreach ($list as $item) {
                    $str = $item;
                    $obj = json_decode($str);
                    $arrayobj = $this->object_array($obj);

                    if ($arrayobj['domid'] == "Pl_Official_MyProfileFeed__20") {
                        $html = $obj->html;
                        $weiboitem = array();
                        $pattern = "/node-type=\"feed_list_content\".*?>(.*?)<\/div>/ism";
                        $name_pattern = "/<img usercard=.*?>(.*?)/ism";
                        $linkpattern = "/<div class=\"WB_from S_txt2\">(.*?)suda-data=\"key=tblog_home_new&value=feed_time/ism";

                        if (preg_match_all($pattern, $html, $listitems) && preg_match_all($name_pattern, $html, $nameitems) && preg_match_all($linkpattern, $html, $linkitems)) {
                            for ($id = 0; $id < count($listitems[1]); $id++) {
                                $data[$id]['content'] = $listitems[1][$id];
                                $data[$id]['title'] = $listitems[1][$id];
                                $data[$id]['banner_url'] = self::cut("src=", " width", $nameitems[0][0]);
                                $data[$id]['post_time'] = self::cut("title=", "date", $linkitems[0][0]);
                                //print_r($data[$id]);

                                DB::insert('INSERT INTO crawler_socialnews (proj_id,social_id,official_name,title,logo_url,post_time,refer_url) VALUES (?,?,?,?,?,?,?)
                            ON DUPLICATE KEY UPDATE post_time=VALUES(post_time)', [$socialtem['proj_id'], $socialtem['social_id'], $socialtem['link_url'], $data[$id]['title'], $data[$id]['banner_url'], date('Y-m-d H:i:s', $data[$id]['post_time']), $socialtem['link_url']]);


                            }
                        }
                    }
                }
            }
        }
    }

    function object_array($array) {
        if(is_object($array)) {
            $array = (array)$array;
        } if(is_array($array)) {
            foreach($array as $key=>$value) {
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }

    function weiCurl($url){

        $curlObj = curl_init();
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_URL, $url);
        //curl_setopt($curlObj, CURLOPT_COOKIEFILE, './cookie.txt');
        //curl_setopt($curlObj, CURLOPT_COOKIEJAR, './cookie.txt');
        curl_setopt($curlObj, CURLOPT_COOKIE, 'TC-Page-G0=b05711a62e11e2c666cc954f2ef362fb; SUB=_2AkMtz-Ppf8NxqwJRmP4Rz2zkZY5_zAvEieKbkxIyJRMxHRl-yj9jqlEntRB6Bk_NBoqbm0-fsRxfzgOi9DUseKjp6bH_; SUBP=0033WrSXqPxfM72-Ws9jqgMF55529P9D9Whp22-9s1yfYi_ArQwMlw1g');
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array(
            "Content-type: application/json; charset=utf-8",
        ));
        $result = curl_exec($curlObj);
        curl_close($curlObj);
        return $result;

    }

    protected function curlFacebook(){
        //$socialList = Model\ProjSocial::where('social_id', 6)->get()->toArray();
        //foreach ($socialList as $socialtem) {

        //$allContents = file_get_contents("https://www.facebook.com/BitCapitalVendor");
        $allContents = $this->facebookCurl("https://www.facebook.com/BitCapitalVendor");
        $patt = "/<body(.*?)<\/script>/smi";
        if (preg_match_all($patt, $allContents, $result)) {
            $portContent = $result[0][0];
            $patter = "/head(.*?)<\/script>/smi";
            if (preg_match_all($patter, $portContent, $result)) {
                $allhtml = $result[0][0];
                $pattern = "/class=\"_4-u2 _3xaf _3-95 _4-u8\"(.*)<\/div>/smi";
                if (preg_match_all($pattern, $allhtml, $result)) {
                    $res = $result[0][0];
                }
                $res = $result[0][0];
                $patContent = "/<p>(.*?)<\/p>/ism";
                $patDate = "/data-utime=.*?(.*?)<\/abbr>/smi";
                $patName = "/<span class=\"fwn\s+fcg\".*?>(.*?)<\/span>/smi";
                $patlogo = "/_38vo\".*?>(.*?)<\/div>/smi";

                if (preg_match_all($patContent, $res, $contentItem) && preg_match_all($patDate, $res, $dateItem)
                    && preg_match_all($patName, $res, $nameItem) && preg_match_all($patlogo, $res, $logoItem)) {

                    for ($id = 0; $id < count($contentItem[1]); $id++) {
                        $data[$id]['content'] = $contentItem[1][$id];
                        $data[$id]['title'] = $nameItem[1][$id];
                        $data[$id]['logo'] = str_replace('src=', 'alt', cut('src=', 'alt', $logoItem[0][$id]));
                        $data[$id]['date'] = self::cut("data-utime=\"", "\" data-shorten", $dateItem[0][$id]);
                        //print_r($data[$id]);
                        DB::insert('INSERT INTO crawler_socialnews (proj_id,social_id,official_name,title,logo_url,post_time,refer_url) VALUES (?,?,?,?,?,?,?)
                            ON DUPLICATE KEY UPDATE post_time=VALUES(post_time)', [$socialtem['proj_id'], $socialtem['social_id'], $socialtem['link_url'], $data[$id]['title'], $data[$id]['logo'], date('Y-m-d H:i:s', $data[$id]['date']), $socialtem['link_url']]);

                    }
                }
            }
        }
        // }
    }

    function facebookCurl($url){

        $UserAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.2) Gecko/2008070208 Firefox/3.0.1';
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
//        $data = curl_exec($curl);
//        curl_close($curl);
//        return $data;
        $UserAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.2) Gecko/2008070208 Firefox/3.0.1';
        $curlObj = curl_init();
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_URL, $url);
        curl_setopt($curlObj, CURLOPT_USERAGENT, $UserAgent);
        $result = curl_exec($curlObj);
        curl_close($curlObj);
        return $result;
    }

    
}

