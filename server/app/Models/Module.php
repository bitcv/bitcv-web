<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    const MODULES = [
        1 => 'invite',
    ];

    protected $table = 'proj_mod';
    protected $guarded = [];

    public function add($proj_id, $mod_id) {
        if (!$proj_id || !$mod_id) {
            return ['err' => 1, 'msg' => 'para error'];
        }
        $data = [
            'proj_id' => $proj_id,
            'mod_id' => $mod_id,
            'valid' => 1,
        ];
        try {
            $ret = DB::table(self::$table)->insert($data);
        } catch (\Exception $e) {
            return ['err' => 2, 'msg' => 'add module error'];
        }
        return ['err' => 0];
    }

    public function audit($proj_id, $mod_id, $valid) {
        if (!$proj_id || !$mod_id) {
            return false;
        }

        $where  = array(
            'proj_id'   => $proj_id,
            'mod_id' => $mod_id,
        );
        $data   = array(
            'valid'     => $valid
        );
        return DB::table(self::$table)->where($where)->update($data);
    }

    public function getAll($start = 0, $num = 30) {
        $data = (array)DB::table(self::$table)
            ->leftJoin('project on proj_mod.proj_id=project.id')
            ->select('proj_id,mod_id,name_cn,name_en')->limit($start, $num)->get();
        foreach ($data as $k => $v) {
            $data[$k]['mod_name'] = self::MODULES[$v['mod_id']];
        }
        return $data;
    }

}
