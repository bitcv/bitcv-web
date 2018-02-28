<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\DB;
use App\Utils\Auth;

class ProjectController extends Controller
{
    public function getProjList (Request $request) {

        //获取请求参数
        $params = $this->validation($request, [
            'keyword' => 'string|nullable',
            'region' => 'numeric',
            'buzType' => 'numeric',
            'stage' => 'numeric',
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $projModel = Model\Project::join('token', 'project.token_id', '=', 'token.id')
            ->where('status', 1);
        if ($keyword) {
            $projModel = $projModel
                ->where(function ($query) use ($keyword) {
                    $query->where('name_cn', 'like', "%$keyword%")
                        ->orWhere('name_en', 'like', "%$keyword%")
                        ->orWhere('token.name', 'like', "%$keyword%")
                        ->orWhere('token.symbol', 'like', "%$keyword%");
                });
        }
        if ($region) {
            $projModel = $projModel->where('region', $region);
        }
        if ($buzType) {
            $projModel = $projModel->where('buz_type', $buzType);
        }
        if ($stage) {
            $projModel = $projModel->where('stage', $stage);
        }

        $offset = $perpage * ($pageno - 1);
        $dataCount = $projModel->count();
        $projList = $projModel->select('project.id', 'name_cn', 'name_en', 'short_desc', 'project.logo_url', 'status', 'found_date', 'buz_type', 'fund_stage', 'token.name as tokenName', 'token.symbol as tokenSymbol', 'token.price as tokenPrice')
            ->offset($offset)->limit($perpage)->orderBy('project.created_at', 'desc')->get()->toArray();

        // 获取用户关注状态
        //$userId = isset($_COOKIE['userId']) ? $_COOKIE['userId'] : null;
        $userId = Auth::getUserId();
        if (!$userId) {
            foreach ($projList as &$project) {
                $project['focusStatus'] = 0;
            }
        } else {
            foreach ($projList as &$project) {
                $projId = $project['id'];
                $focusStatus = Model\UserFocus::where([['user_id', $userId], ['proj_id', $projId]])->value('status');
                $project['focusStatus'] = (int)$focusStatus;
            }
        }

        return $this->output(['dataCount' => $dataCount, 'projList' => $projList]);
    }

    public function getProjDetail (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 获取项目基本信息
        $projData = Model\Project::where('id', $projId)
            ->select('id', 'name_cn', 'name_en', 'logo_url', 'banner_url', 'abstract', 'white_paper_url', 'home_url', 'view_times', 'token_id', 'node_amount', 'cur_amount', 'plan_amount', 'fund_start_time', 'fund_end_time', 'status', 'admin_id', 'company_addr', 'company_email')
            ->first();
        if ($projData === null) {
            return $this->error(301);
        }
        $projData->toArray();

        // 获取项目关注数目
        $projData['focusNum'] = Model\UserFocus::where([['proj_id', $projId], ['status', 1]])->count();

        // 获取项目关注状态
        //$userId = isset($_COOKIE['userId']) ? $_COOKIE['userId'] : null;
        $userId = Auth::getUserId();
        if (!$userId) {
            $projData['focusStatus'] = 0;
        } else {
            $focusStatus = Model\UserFocus::where([['user_id', $userId], ['proj_id', $projId]])->value('status');
            $projData['focusStatus'] = (int)$focusStatus;
        }

        // 获取token信息
        $tokenId = $projData['token_id'];
        unset($projData['token_id']);
        $tokenData = Model\Token::where('id', $tokenId)->first();
        if ($tokenData === null) {
            return $this->error(101);
        }
        $projData['tokenName'] = $tokenData->name;
        $projData['tokenSymbol'] = $tokenData->symbol;

        // 获取项目标签
        $projTagList = Model\ProjTag::where('proj_id', $projId)
            ->pluck('tag')->toArray();
        $projData['tagList'] = $projTagList;

        // 获取项目优势
        $projAdvList = Model\ProjAdvantage::where('proj_id', $projId)
            ->select('title', 'detail')
            ->get()->toArray();
        $projData['advangateList'] = $projAdvList;

        // 获取项目成员信息
        $projMemberList = Model\ProjMember::where('proj_id', $projId)
            ->select('photo_url', 'name', 'position', 'intro')
            ->get()->toArray();
        $projData['memberList'] = $projMemberList;

        // 获取项目事件
        $projEventList = Model\ProjEvent::where('proj_id', $projId)
            ->select('occur_time', 'title', 'detail')
            ->orderBy('occur_time', 'desc')->get()->toArray();
        $resultList = array();
        foreach ($projEventList as $event) {
            $timestamp = strtotime($event['occur_time']);
            $year = date('Y', $timestamp);
            $month = date('m', $timestamp);
            $season = ceil($month / 3);
            $key = $year . '年 第' . $season . '季度';
            $isExist = false;
            foreach ($resultList as &$eventItem) {
                if ($eventItem['eventKey'] === $key) {
                    $isExist = true;
                    $eventItem['eventNode'][] = array(
                        'time' => date('m月d日', $timestamp),
                        'title' => $event['title']
                    );
                }
            }
            if (!$isExist) {
                $resultList[] = array(
                    'eventKey' => $key,
                    'eventNode' => array(
                        array(
                            'time' => date('m月d日', $timestamp),
                            'title' => $event['title']
                        )
                    )
                );
            }
        }
        $projData['eventList'] = $resultList;

        // 获取项目顾问信息
        $projAdvisorList = Model\ProjAdvisor::where('proj_id', $projId)
            ->select('name', 'photo_url', 'company', 'intro')
            ->get()->toArray();
        $projData['advisorList'] = $projAdvisorList;

        // 获取合作伙伴信息
        $projPartnerList = Model\ProjPartner::where('proj_id', $projId)
            ->select('name', 'logo_url', 'home_url')
            ->get()->toArray();
        $projData['partnerList'] = $projPartnerList;

        // 获取媒体报道信息
        $projReportList = Model\ProjReport::join('media', 'proj_report.media_id', '=', 'media.id')
            ->where('proj_id', $projId)
            ->limit(5)->orderBy('proj_report.release_time','desc')
            ->select('name', 'logo_url', 'link_url', 'title','content','release_time','banner_url')
            ->limit(4)->get()->toArray();
        $projData['reportList'] = $projReportList;

        $projDynamicList = Model\CrawlerSocialNews::join('social','crawler_socialnews.social_id','=','social.id')
            ->where([['proj_id', $projId]])
            ->whereIn('social_id', [2,3,5,6])
            ->limit(4)->orderBy('crawler_socialnews.post_time','desc')
            ->select('crawler_socialnews.created_at','crawler_socialnews.post_time','crawler_socialnews.refer_url','crawler_socialnews.official_name','crawler_socialnews.title','crawler_socialnews.logo_url','social.font_class','social.name')
            ->get()->toArray();

        $projData['dynamicList'] = $projDynamicList;

        $projPublicList = Model\CrawlerSocialNews::join('social','crawler_socialnews.social_id','=','social.id')
            ->where([['proj_id', $projId]])
            ->whereIn('social_id', [5])
            ->limit(5)->orderBy('crawler_socialnews.post_time','desc')
            ->select('crawler_socialnews.created_at','crawler_socialnews.post_time','crawler_socialnews.title','crawler_socialnews.refer_url')
            ->get()->toArray();
        $projData['publicList'] = $projPublicList;

        // 获取社交链接信息
        $projSocialList = Model\ProjSocial::join('social', 'proj_social.social_id', '=', 'social.id')
            ->where([['proj_id', $projId]])
            ->select('name', 'font_class', 'link_url')
            ->get()->toArray();
        $projData['socialList'] = $projSocialList;

        return $this->output($projData);
    }

    public function getProjTopList (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'type' => 'required|string',
            'count' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        if ($type === 'view') {
            $projList = Model\Project::orderBy('view_times', 'desc')
                ->where('status', 1)
                ->select('id as proj_id', 'name_cn', 'view_times as count')->limit($count)
                ->get()->toArray();
            return $this->output($projList);
        }
        if ($type === 'focus') {
            $focusList = Model\UserFocus::select('proj_id', DB::raw('COUNT(proj_id) as count'))
                ->groupBy('proj_id')->orderBy('count', 'desc')->limit($count)
                ->get()->toArray();
            foreach ($focusList as $index => &$focus) {
                $project = Model\Project::where([
                    ['id', $focus['proj_id']],
                    ['status', 1],
                ])->first();
                if (!$project) {
                    unset($focusList[$index]);
                } else {
                    $focus['name_cn'] = $project->name_cn;
                }
            }
            return $this->output($focusList);
        }
    }

    public function getProjTagList (Request $request) {
        $regionOptionList = [
            array('label' => '不限', 'value' => 0),
            array('label' => '美国', 'value' => 1),
            array('label' => '日韩', 'value' => 2),
            array('label' => '欧洲', 'value' => 3),
            array('label' => '东南亚', 'value' => 4),
            array('label' => '其它地区', 'value' => 5),
        ];
        $buzOptionList = [
            array('label' => '不限', 'value' => 0),
            array('label' => '金融', 'value' => 1),
            array('label' => '数字资产', 'value' => 2),
            array('label' => '娱乐', 'value' => 3),
            array('label' => '供应链管理', 'value' => 4),
            array('label' => '法律服务', 'value' => 5),
            array('label' => '医疗', 'value' => 6),
            array('label' => '能源服务', 'value' => 7),
            array('label' => '公益', 'value' => 8),
            array('label' => '物联网', 'value' => 9),
            array('label' => '农业', 'value' => 10),
            array('label' => '社交', 'value' => 11),
            array('label' => '其它', 'value' => 100),
        ];
        $stageOptionList = [
            array('label' => '不限', 'value' => 0),
            array('label' => '募资前', 'value' => 1),
            array('label' => '募资中', 'value' => 2),
            array('label' => '公开发行', 'value' => 3),
            array('label' => '产品落地', 'value' => 4),
        ];

        return $this->output([
            'region' => array('label' => '地区', 'default' => 0, 'optionList' => $regionOptionList),
            'buzType' => array('label' => '行业', 'default' => 0, 'optionList' => $buzOptionList),
            'stage' => array('label' => '阶段', 'default' => 0, 'optionList' => $stageOptionList),
        ]);
    }

    public function index(){
        $projectList = Model\Project::where('id',1)->get()->toArray();
        return Model\Project::all(); //bad
    }

    public function getPList(Request $request){

        $params = $this->validation($request, [
            'p' => 'required|numeric',
            'chid' => 'string|nullable',
        ]);

        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $offset = 6 * ($p - 1);
        if($chid){
//            $projList = Model\Project::join('proj_tag','project.id','=','proj_tag.proj_id')->where('tag','like',"%$chid%")
//                ->offset($offset)->limit(6)->get()->toArray();
            $projList = Model\Project::where('buz_type',$chid)
                ->offset($offset)->limit(6)->get()->toArray();
        }else{
            $projList = Model\Project::offset($offset)->limit(6)->get()->toArray();
        }

        return response()->json($projList);
    }

    public function getProInfo(Request $request)
    {

        $params = $this->validation($request, [
            'id' => 'required|numeric',
        ]);
        extract($params);

        // 获取项目基本信息
        $projData = Model\Project::where('id', $id)
            ->select('name_cn','logo_url', 'name_en', 'short_desc', 'abstract', 'white_paper_url', 'home_url', 'view_times', 'token_id', 'node_amount', 'cur_amount', 'plan_amount', 'fund_start_time', 'fund_end_time', 'status', 'admin_id')
            ->first();

        $projData->toArray();

        // 获取项目优势
        $projAdvList = Model\ProjAdvantage::where('proj_id', $id)
            ->select('title', 'detail')
            ->get()->toArray();
        $projData['advangateList'] = $projAdvList;

        // 获取项目成员信息
        $projMemberList = Model\ProjMember::where('proj_id', $id)
            ->select('photo_url', 'name', 'position', 'intro')
            ->get()->toArray();
        $projData['memberList'] = $projMemberList;

        // 获取项目事件
        $projEventList = Model\ProjEvent::where('proj_id', $id)
            ->select('occur_time', 'title', 'detail')
            ->get()->toArray();
        $projData['eventList'] = $projEventList;

        // 获取合作伙伴信息
        $projPartnerList = Model\ProjPartner::where('proj_id', $id)
            ->select('name')
            ->get()->toArray();
        $projData['partnerList'] = $projPartnerList;

        // 获取媒体报道信息
//        $projMediaList = Model\ProjMedia::where('proj_id', $id)
//            ->get()->toArray();
//        $projData['mediaList'] = $projMediaList;

        // 获取社交链接信息
        $projSocialList = Model\ProjSocial::where('proj_id', $id)
            ->get()->toArray();
        $projData['socialList'] = $projSocialList;

        return response()->json($projData);
    }


    public function gerNewsList(){

    }
}
