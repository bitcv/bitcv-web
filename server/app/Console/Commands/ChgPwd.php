<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Utils\Service;
use DB;

class ChgPwd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imp:chgpwd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $start = 0;
        $limit = 200;
        $count = 0;
        while (true) {
            $rows = DB::table('user')->where('id','<',30000)->orderBy('id')->offset($start)->limit($limit)->get()->toArray();
            if (empty($rows)) {
                break;
            }
            $start += $limit;
            foreach ($rows as $row) {
                $u = (array)$row;
                if (Service::checkPwd('bitcv#2018224', $u['passwd'])) {
                    $passwd = Service::getPwd('bcv'.microtime());
                    echo "{$u['id']},{$u['mobile']},newpass:{$passwd}\n";
                    $count += 1;
                    DB::table('user')->where('id',$u['id'])->update(['passwd' => $passwd]);
                }
            }
        }
        echo "count: {$count}\n";
    }
}
