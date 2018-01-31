<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function getProjList (Request $request) {

        //获取请求参数
        $params = $this->validation($request, [
            'keyword' => 'string|nullable',
            'region' => 'numeric',
            'bussinessType' => 'numeric',
            'phrase' => 'numeric',
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $projModel = Model\Project::join('token', 'project.token_id', '=', 'token.id');
        if ($keyword) {
            $projModel = $projModel
                ->where('name_cn', 'like', "%$keyword%")
                ->orWhere('name_en', 'like', "%$keyword%")
                ->orWhere('name_short', 'like', "%$keyword%")
                ->orWhere('token.name', 'like', "%$keyword%")
                ->orWhere('token.symbol', 'like', "%$keyword%");
        }
        if ($region) {
            $projModel = $projModel->where('region', $region);
        }
        if ($bussinessType) {
            $projModel = $projModel->where('bussiness_type', $bussinessType);
        }
        if ($phrase) {
            $projModel = $projModel->where('phrase', $phrase);
        }

        $offset = $perpage * ($pageno - 1);
        $dataCount = $projModel->count();
        $projList = $projModel->select('project.id', 'title', 'name_cn', 'name_en', 'logo_url', 'bussiness_type', 'phrase', 'region', 'token.name as tokenName', 'token.symbol as tokenSymbol', 'token.price as tokenPrice')
            ->offset($offset)->limit($perpage)->get()->toArray();

        // 获取用户关注状态
        $userId = isset($_COOKIE['userId']) ? $_COOKIE['userId'] : null;
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
                ->select('id as proj_id', 'name_cn', 'name_short', 'view_times as count')->limit($count)
                ->get()->toArray();
            return $this->output($projList);
        }
        if ($type === 'focus') {
            $focusList = Model\UserFocus::select('proj_id', DB::raw('COUNT(proj_id) as count'))
                ->groupBy('proj_id')->orderBy('count', 'desc')->limit($count)
                ->get()->toArray();
            foreach ($focusList as &$focus) {
                $project = Model\Project::find($focus['proj_id']);
                $focus['name_cn'] = $project->name_cn;
            }
            return $this->output($focusList);
        }
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
            ->select('id', 'name_cn', 'name_en', 'name_short', 'logo_url', 'banner_url', 'abstract', 'white_paper_url', 'web_url', 'view_times', 'token_id', 'node_amount', 'total_amount', 'plan_amount', 'start_time', 'end_time', 'status', 'admin_id', 'company_tel', 'company_addr', 'company_email')
            ->first();
        if ($projData === null) {
            return $this->error(301);
        }
        $projData->toArray();

        // 获取项目关注数目
        $projData['focusNum'] = Model\UserFocus::where([['proj_id', $projId], ['status', 1]])->count();

        // 获取项目关注状态
        $userId = isset($_COOKIE['userId']) ? $_COOKIE['userId'] : null;
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
            ->get()->toArray();
        $projData['eventList'] = $projEventList;

        // 获取合作伙伴信息
        $projPartnerList = Model\ProjPartner::where('proj_id', $projId)
            ->select('name', 'logo_url', 'web_url')
            ->get()->toArray();
        $projData['partnerList'] = $projPartnerList;

        // 获取媒体报道信息
        $projMediaList = Model\ProjMedia::where('proj_id', $projId)->limit(4)
            ->get()->toArray();
        $projData['mediaList'] = $projMediaList;

        // 获取社交链接信息
        $projSocialList = Model\ProjSocial::where('proj_id', $projId)
            ->get()->toArray();
        $projData['socialList'] = $projSocialList;

        return $this->output($projData);
    }

    public function addProject (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
    }

    public function index(){
        $projectList = Model\Project::where('id',1)->get()->toArray();
        //$projectList = DB::table('project')->join('')
        //return $this->output($projectList);
        //return response()->json($projectList);
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
            //$projList = Model\ProjTag::where('tag','like',"%$chid%");
            $projList = Model\Project::join('proj_tag','project.id','=','proj_tag.proj_id')->where('tag','like',"%$chid%")
                ->offset($offset)->limit(6)->get()->toArray();
        }else{
            $projList = Model\Project::offset($offset)->limit(6)->get()->toArray();
        }


        return response()->json($projList);
    }

    public function show($projId)
    {

        // 获取项目基本信息
        $projData = Model\Project::where('id', $projId)
            ->select('name_cn', 'name_en','image', 'name_short', 'abstract', 'white_paper_url', 'web_url', 'view_times', 'token_id', 'node_amount', 'total_amount', 'plan_amount', 'start_time', 'end_time', 'status', 'admin_id')
            ->first();

        $projData->toArray();

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
            ->get()->toArray();
        $projData['eventList'] = $projEventList;

        // 获取合作伙伴信息
        $projPartnerList = Model\ProjPartner::where('proj_id', $projId)
            ->select('name', 'photo_url')
            ->get()->toArray();
        $projData['partnerList'] = $projPartnerList;

        // 获取媒体报道信息
        $projMediaList = Model\ProjMedia::where('proj_id', $projId)
            ->get()->toArray();
        $projData['mediaList'] = $projMediaList;

        // 获取社交链接信息
        $projSocialList = Model\ProjSocial::where('proj_id', $projId)
            ->get()->toArray();
        $projData['socialList'] = $projSocialList;

        $lesson = Model\Project::findOrFail($projId);
        //return $lesson;
        return response()->json($projData);
    }
}
