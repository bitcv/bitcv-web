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
        $params = $this->validation($request, [
            'linkUrl' => 'required|string',
            'data' => 'required|array',
        ]);
        $requestData = $request->all();
        $linkUrl = $requestData['linkUrl'];
        $data = $requestData['data'];
        $dataArr = json_decode($data, true);
        $data = json_decode($requestData['data'],true);
        $articleList = $data['article'];

        for($id = 0; $id < count($data['article']); $id++){
            $article = $articleList[$id];
            $data['link_url'] = $requestData['linkUrl'];
            $data['refer_url'] = $article['content_url'];
            $data['content'] = $article['abstract'];
            $data['official_name'] = $article['author'];
            $data['title'] = $article['title'];
            $data['banner_url'] = $article['cover'];
            $data['post_time'] = date('Y-m-d H-i-s', $article['datetime']);
            $data['post_time'] = substr($data['post_time'],0,10).str_replace("-",":",substr($data['post_time'],10));
            //return $data['post_time'];
            $projSocial = Model\ProjSocial::where([['link_url', $data['link_url']], ['social_id', 5]])->first();
            $crawlerNews = Model\CrawlerSocialNews::where([['title', $data['title']]])->first();
            if($crawlerNews){
                Model\CrawlerSocialNews::where('title',$crawlerNews['title'])->update(['refer_url' => $data['refer_url']]);
            }else{
                DB::insert('insert into crawler_socialnews (proj_id, social_id,official_name,title,logo_url,refer_url,post_time) values (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE post_time=VALUES(post_time)', [$projSocial['proj_id'], $projSocial['social_id'],$projSocial['link_url'],$data['title'],$data['banner_url'],$data['refer_url'], $data['post_time']]);
            }

        }
    }

}















