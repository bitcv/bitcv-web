<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\Cookie;
use App\Utils\Service;
use Illuminate\Support\Facades\DB;

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
        $newList = Model\ProjReport::join('media', 'proj_report.media_id', '=', 'media.id')
            ->where('status',1)->offset($offset)->limit($perpage)->get()->toArray();
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
        $newDetail = Model\ProjReport::join('media', 'proj_report.media_id', '=', 'media.id')
            ->where('proj_report.id', $id)->first();
        if ($newDetail === null) {
            return $this->error(310);
        }
        return $this->output($newDetail);
    }

    public function getWeChatList(){

        $weChatList = Model\ProjSocial::where('social_id','=',5)
            ->select('proj_id','social_id','link_url','id')->get()->toArray();

        return $this->output(['weChatList' => $weChatList]);
    }

    public function articleList(Request $request){

        $condition = $request->all();
        //file_put_contents('./log.log',var_export($condition,true));
        $result = var_export($condition,true);
        $data = json_decode($result['data'],true);
        $articleList = $data['article'];

        for($id = 0; $id < count($data['article']); $id++){
            $article = $articleList[$id];
            $data['link_url'] = $result['linkUrl'];
            $data['refer_url'] = $article['content_url'];
            $data['content'] = $article['abstract'];
            $data['official_name'] = $article['author'];
            $data['title'] = $article['title'];
            $data['banner_url'] = $article['cover'];
            $data['post_time'] = date('Y-m-d H-i-s', $article['datetime']);
            DB::table('crawler_socialnews')->where('link_url',$data['link_url'])->update(['refer_url'=>$data['refer_url']]);
        }
    }

}















