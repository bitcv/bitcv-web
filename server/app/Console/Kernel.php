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
        //Commands\SendSMS::class,
        Commands\CrawlData::class,
        Commands\UpdateDb::class,
        Commands\AddProfit::class,
        Commands\CrawlRecords::class,
        Commands\CrawlCoin::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command("crawl:data weibo")->everyMinute()->withoutOverlapping();
        //$schedule->command("crawl:data facebook")->everyMinute()->withoutOverlapping();
        //$schedule->command("crawl:data twitter")->everyMinute()->withoutOverlapping();
        //$schedule->command("crawl:data news")->everyMinute()->withoutOverlapping();
        $schedule->command("crawl:data ok")->daily()->withoutOverlapping();
        $schedule->command("crawl:data otcbtc")->daily()->withoutOverlapping();
//        $schedule->call(function () {
//            $this->crawlPrice();
//        })->everyMinute();
        $schedule->command('add:profit')->dailyAt('00:00');
        $schedule->command("crawl:data weibo")->daily()->withoutOverlapping();
        $schedule->command("crawl:data facebook")->daily()->withoutOverlapping();
        $schedule->command("crawl:data twitter")->everyMinute()->withoutOverlapping();
        $schedule->command("crawl:crawlRecords")->hourly()->withoutOverlapping();
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

        $data = self::curl('https://otcbtc.com/api/v2/tickers');

        $fields = ['btc','eos','bch','gxs','usdt','zec','qtum','otb','dew','big','bnb','uip','fair','jex','mds',
            'lrc','tnb','pix','nas','snt','wlk','kin','rct','credo','bkx','hmc','swftc','elf','yoyow','seer','mvc',
            'mot','aix','qash'];
        foreach ($fields as $field) {
            $this->updPrice($field,$data[$field.'_eth']['ticker']['last']);
        }

        $ethData = self::curl('http://api.huobipro.com/market/history/kline?preiod=1min&size=10&symbol=ethusdt');
        $this->updPrice('eth',$ethData['data'][0]['close']);

        $kcashData = self::curl('https://www.okex.com/api/v1/ticker.do?symbol=kcash_eth');
        $this->updPrice('kcash',$kcashData['ticker']['last']);

        $pxcData = self::curl('https://www.bit-z.com/api_v1/ticker?coin=pxc_eth');
        $this->updPrice('pxc',$pxcData['data']['last']);

        $dogeData = self::curl('http://data.gate.io/api2/1/ticker/doge_usdt');
        $this->updPrice('doge',$dogeData['last']);
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

}



