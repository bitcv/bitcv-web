<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use DB;

class ImpUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imp:invite';

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
        $types = [
            'bcv' => 2,
            'btc' => 160,
            'eth' => 156,
            'eos' => 154,
            'neo' => 158,
            'pxc' => 32,
            'doge' => 217,
            'kcash' => 218,
            'icst' => 219,
        ];
        $start = 0;
        $limit = 200;
        $usermodel = new User();
        while (true) {
            $rows = DB::table('mod_invite_2')->where('id','>',24813)->orderBy('id')->offset($start)->limit($limit)->get()->toArray();
            if (empty($rows)) {
                break;
            }
            $start += $limit;
            foreach ($rows as $row) {
                $u = (array)$row;
                $mobile = $u['mobile'];
                $user = $usermodel->where('mobile', $mobile)->first();
                if ($user) {
                    $uid = $user->id;
                } else {
                    $uid = $usermodel->regUser(86, $mobile, 'bitcv#2018224'.microtime());
                }
                if (!$uid) {
                    echo "$mobile reg failed\n";
                    continue;
                }
                foreach ($types as $t => $tid) {
                    $num = $u[$t.'_num'];
                    if ($num > 0) {
                        if ($t == 'btc') {
                            $num = $num/10000;
                        } elseif ($t == 'eth') {
                            $num = $num/10000;
                        } elseif ($t == 'neo') {
                            $num = $num/1000;
                        } elseif ($t == 'eos') {
                            $num = $num/100;
                        }
                        try {
                            $ret = DB::table('user_asset')->insert([
                                'user_id' => $uid,
                                'token_id' => $tid,
                                'amount' => $num,
                                'created_at' => date('Y-m-d H:i:s'),
                            ]);
                        } catch (\Exception $ex) {
                            echo "already\n";
                        }
                        echo "{$u['id']},{$mobile},{$t},{$num}\n";
                    }
                }
            }
            echo "{$start}\n";
        }
        echo "all complete\n";
    }

}
