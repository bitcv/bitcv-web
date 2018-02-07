<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\Cookie;
use App\Utils\Service;

class NewsController extends Controller{

    public function getNewsList(Request $request){
        //获取请求参数
        $params = $this->validation($request, [
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $offset = $perpage * ($pageno - 1);
        $newList = Model\ProjReport::where('status',1)->offset($offset)->limit($perpage)->get()->toArray();
        //$dataCount = Model\News::count();

        return $this->output($newList);

    }

    public function getNewsDetail(Request $request){
        //获取请求参数
        $params = $this->validation($request,[
            'id' => 'required|numeric',
        ]);
        if ($params === false){
            return $this->error(100);
        }
        extract($params);
        $newDetail = Model\ProjReport::where('id', $id)->first();
        if ($newDetail === null) {
            return $this->error(203);
        }
        return $this->output($newDetail);
    }

}