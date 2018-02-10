<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models as Model;

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
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function (){
            $this->projReportWeChat();
            })->everyMinute();

        $schedule->call(function (){
            $this->projReportWeibo();
        })->everyMinute();
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

    protected function projReportWeChat(){

        $socialList = Model\ProjSocial::where('social_id', 5)->get()->toArray();
        //print_r($socialList[0]['link_url']);
        foreach ($socialList as $socialitem){
            //print_r($socialitem['link_url']);
            $searchHtml = file_get_contents('http://weixin.sogou.com/weixin?type=1&s_from=input&query='.$socialitem['link_url'].'&ie=utf8');
            $pattern = '/account_name_0" href="(.*?)"></ism';
            preg_match_all($pattern,$searchHtml,$matches);
            $url = $matches[1][0];
            $url = str_replace("&amp;", "&", $url);

            $listHtml = file_get_contents($url);

            $pattern = '/var msgList = ({.*});/ism';
            preg_match_all($pattern,$listHtml,$matches);

            $json = $matches[1][0];
            $results = json_decode($json,true);

            foreach($results['list'] as $result){
                $msg =  $result['app_msg_ext_info'];
                $data['link_url'] = 'https://mp.weixin.qq.com'.str_replace('&amp;','&',$msg['content_url']);
                $data['content'] = $msg['digest'];
                $data['title'] = $msg['title'];
                $data['banner_url'] = $msg['cover'];
                $data['created_at'] = $result['comm_msg_info']['datetime'];
                //echo $title."\n".$digest."\n".$conUrl."\n".$logo."\n";
                //Model\ProjReport::where('id',$socialitem['proj_id'])->update($data);
                Model\ProjSocial::Create([
                    'proj_id' => $socialitem['proj_id'],
                    'social_id' => $socialitem['social_id'],
                    'official_name' => $socialitem['link_url'],
                    'title' => $data['title'],
                    'logo_url' => $data['banner_url'],
                    'created_at' => $data['created_at'],
                    'refer_url' => $data['link_url'],
                    'status' => 1,
                    ]);
            }
        }
    }

    protected function projReportWeibo(){

        $socialList = Model\ProjSocial::where('social_id', 6)->get()->toArray();
        foreach ($socialList as $socialItem){
            $content = file_get_contents('http://s.weibo.com/weibo/'.$socialItem['link_url'].'?topnav=1&wvr=6&b=1');
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
                            // print_r($result);
                            $weiboitem = array();
                            foreach ($result[1] as $content) {
                                $weiboitem = array();
                                $pattern = "/<p class=\"comment_txt\".*?>(.*?)<\/p>/ism";
                                if (preg_match_all($pattern, $content, $listitems)) {
                                    //   print_r($listitems);
                                    $weiboitem['content'] = strip_tags($listitems[1][0]);
                                    $weiboitem['content'] = str_replace("展开全文c", "", $weiboitem['content']);
                                }
                                $name_pattern = "/suda-data=\"key=tblog_search_weibo&value=weibo_ss_\d_icon\"\s+href=\"(.*?)\"\s+title=\"(.*?)\"(.*?)<img src=\"(.*?)\"\s+alt=\"(.*?)\"\s+width=\"50\"\s+height=\"50\"\s+class=\"W_face_radius\"/sim";
                                if (preg_match_all($name_pattern, $content, $nameitems)) {
                                    //   print_r($nameitems);
                                    $weiboitem['homeurl'] = $nameitems[1][0];
                                    $weiboitem['name'] = $nameitems[2][0];
                                    $weiboitem['logo'] = $nameitems[4][0];

                                }
                                // echo $html;
                                //"
                                $linkpattern = "/title\s+\-\-\>\s+<a\s+href=\"(.*?)\"\s+target=\"_blank\"\s+title=\"(.*?)\"\s+date=\"(.*?)\"\s+class=\"W_textb\"\s+node-type=\"feed_list_item_date/sim";
                                if (preg_match_all($linkpattern, $content, $linkitems)) {
                                    //   print_r($linkitems);
                                    $weiboitem['url'] = $linkitems[1][0];
                                    $weiboitem['date'] = $linkitems[2][0];

                                }
                                if (strpos($content, "fl_unfold") !== false) {
                                    $actionpattern = "/action-type=\"fl_unfold\"\s+action-data=\"(.*?)\">/";
                                    if (preg_match_all($actionpattern, $content, $actitems)) {
                                        //     print_r($actitems);
                                        $weiboitem['unfold'] = $actitems[1][0];
                                    }
                                }
                                //print_r($weiboitem);
                                Model\ProjSocial::create([
                                    'proj_id' => $socialItem['proj_id'],
                                    'social_id' => $socialItem['social_id'],
                                    'title' => $weiboitem['content'],
                                    'logo_url' => $weiboitem['logo'],
                                    'refer_url' => $weiboitem['homeurl'],
                                    'created_at' => strtotime($weiboitem['date']),
                                    'official_name' => $weiboitem['name'],
                                    'status' => 1,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    protected function projReportTwitter(){

        $socialList = Model\Social::where('social_id', 3)->first();
        //https://twitter.com/BCVofficial
        $html = file_get_contents("twitter.txt");

        $regContent = "/<p class=\"TweetTextSize\s+TweetTextSize--normal\s+js-tweet-text\s+tweet-text\".*?>(.*?)<\/p>/ism";
        $regTitle = "/<strong class=\"fullname\s+show-popup-with-id\s+u-textTruncate\s+\".*?>(.*?)<\/strong>/ism";
        $regLogo = "/<img class=\"avatar\s+js-action-profile-avatar\".*?>(.*?)/ism";
        $regDate = "/data-aria-label-part=\"last\".*?>(.*?)<\/span>/ism";

        if(preg_match_all($regContent,$html,$contentItem) && preg_match_all($regTitle,$html,$titleItem)
            && preg_match_all($regLogo,$html,$logoItem) && preg_match_all($regDate,$html,$dateItem)){

            for($id = 0; $id < count($contentItem[1]); $id++){
                $data[$id]['content'] = $contentItem[1][$id];
                $data[$id]['title'] = $titleItem[1][$id];
                $data[$id]['banner_url'] = self::cut('src=','alt',$logoItem[0][$id]);
                $data[$id]['created_at'] = self::cut('data-time-ms=','data-long-form',$dateItem[0][$id]);
                Model\ProjSocial::Create([
                    'proj_id' => $socialList['proj_id'],
                    'social_id' => $socialList['social_id'],
                    'title' => $data['title'],
                    'logo_url' => $data['banner_url'],
                    'link_url' => $socialList['link_url'],
                ]);
            }
        }
    }

    protected function projReportFacebook(){

        $socialList = Model\ProjSocial::where('social_id', 2)->first();
        $html = file_get_contents('facebook.txt');
        //https://www.facebook.com/BitCapitalVendor
        $reg = "/class=\"_442n\"(.*)<\/script>/smi";
        if(preg_match_all($reg,$html,$result)){
            $regContent = "/<p>(.*?)<\/p>/ism";
            $regDate = "/data-utime=.*?(.*?)<\/abbr>/smi";
            $regName= "/data-hovercard-referer=.*?>(.*?)<\/a>/smi";
            $reglogo = "/_38vo\".*?>(.*?)<\/div>/smi";

            if(preg_match_all($regContent,$result[1][0],$contentItem) && preg_match_all($regDate,$result[1][0],$dateItem)
                && preg_match_all($regName,$result[1][0],$nameItem) && preg_match_all($reglogo,$result[1][0],$logoItem)) {

                for ($id = 0; $id < count($contentItem); $id++) {
                    $data[$id]['content'] = $contentItem[1][$id];
                    $data[$id]['title'] = $nameItem[1][$id];
                    $data[$id]['banner_url'] = str_replace('src=', 'alt', self::cut('src=', 'alt', $logoItem[0][$id]));
                    $data[$id]['created_at'] = substr($dateItem[1][$id],1,10);
                    Model\ProjSocial::Create([
                        'proj_id' => $socialList['proj_id'],
                        'social_id' => $socialList['social_id'],
                        'title' => $data['title'],
                        'logo_url' => $data['banner_url'],
                        'created_at' => $data['created_at'],
                        'refer_url' => $socialList['link_url'],
                        'status' => 1,
                    ]);
                    //print_r($data[$id]);
                }
            }
        }
    }

    function cut($begin,$end,$str){
        $b = mb_strpos($str,$begin) + mb_strlen($begin);
        $e = mb_strpos($str,$end) - $b;
        return mb_substr($str,$b,$e);
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
}
