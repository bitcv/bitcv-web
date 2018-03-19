<?php

namespace App\Console;

use Binance\API;
use Faker\Provider\DateTime;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models as Model;
use Illuminate\Support\Facades\DB;
define('ACCOUNT_ID', '34253486'); // 你的账户ID
define('ACCESS_KEY','0b3ef77e-98c4d54b-c6982338-d9d08'); // 你的ACCESS_KEY
define('SECRET_KEY', 'f4169235-31a63cfe-b927bd53-4ce45'); // 你的SECRET_KEY

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
        //$schedule->command("crawl:data weibo")->everyMinute()->withoutOverlapping();
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
}



