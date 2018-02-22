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
        $schedule->call(function () {
            $this->projReportWeChat();
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

        foreach ($socialList as $socialitem){
            $keyword = trim(strrchr($socialitem['link_url'], '/'),'/');
            $searchHtml = file_get_contents('http://weixin.sogou.com/weixin?type=1&s_from=input&query='.$keyword.'&ie=utf8');
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

                Model\CrawlerSocialNews::Create([
                    'proj_id' => $socialitem['proj_id'],
                    'social_id' => $socialitem['social_id'],
                    'official_name' => $socialitem['link_url'],
                    'title' => $data['title'],
                    'logo_url' => $data['banner_url'],
                    'created_at' => $data['created_at'],
                    'refer_url' => $data['link_url'],
                ]);
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
}

