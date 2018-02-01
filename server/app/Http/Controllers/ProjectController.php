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
            'buzType' => 'numeric',
            'stage' => 'numeric',
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $projModel = Model\Project::select();
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
        if ($buzType) {
            $projModel = $projModel->where('buz_type', $buzType);
        }
        if ($stage) {
            $projModel = $projModel->where('stage', $stage);
        }

        $offset = $perpage * ($pageno - 1);
        $dataCount = $projModel->count();
        $projList = $projModel->offset($offset)->limit($perpage)->orderBy('created_at', 'desc')->get()->toArray();

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

    public function getProjBasicInfo (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        // 获取项目基本信息
        $projBasicInfo = Model\Project::where('id', $projId)->first();
        if ($projBasicInfo == null) {
            return $this->error(301);
        }
        // 获取项目标签
        $projBasicInfo['tagList'] = Model\ProjTag::where('proj_id', $projId)->pluck('tag')->toArray();

        // 获取项目token
        $tokenId = $projBasicInfo->token_id;
        if ($tokenId) {
            $tokenModel = Model\Token::where('id', $tokenId)->first();
            $projBasicInfo['tokenName'] = $tokenModel->name;
            $projBasicInfo['tokenSymbol'] = $tokenModel->symbol;
            $projBasicInfo['tokenPrice'] = $tokenModel->price;
        }

        return $this->output($projBasicInfo);
    }

    public function updProjBasicInfo (Request $request) {
        // 获取请求参数
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'nameCn' => 'required|string',
            'nameEn' => 'required|string',
            'foundDate' => 'required|string',
            'logoUrl' => 'required|string',
            'homeUrl' => 'required|string',
            'shortDesc' => 'required|string',
            'whitePaperUrl' => 'required|string',
            'abstract' => 'required|string',
            'region' => 'required|numeric',
            'buzType' => 'required|numeric',
            'stage' => 'required|numeric',
            'fundStage' => 'required|numeric',
            'tagList' => 'array',
            'fundStartTime' => 'string|nullable',
            'fundEndTime' => 'string|nullable',
            'companyEmail' => 'string|nullable',
            'companyAddr' => 'string|nullable',
            'bannerUrl' => 'string|nullable',
            'tokenName' => 'string|nullable',
            'tokenSymbol' => 'string|nullable',
            'tokenPrice' => 'string|numeric|nullable',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $homeUrl = strpos($homeUrl, 'http') === 0 ? $homeUrl : 'http://' . $homeUrl;

        $projInfo = [
            'name_cn' => $nameCn,
            'name_en' => $nameEn,
            'found_date' => date('Y-m-d H-i-s', strtotime($foundDate)),
            'logo_url' => $logoUrl,
            'home_url' => $homeUrl,
            'short_desc' => $shortDesc,
            'white_paper_url' => $whitePaperUrl,
            'abstract' => $abstract,
            'region' => $region,
            'buz_type' => $buzType,
            'stage' => $stage,
            'fund_stage' => $fundStage,
        ];

        if ($tokenName && $tokenSymbol) {
            $tokenModel = Model\Token::firstOrCreate([
                'name' => $tokenName,
                'symbol' => $tokenSymbol,
                'price' => $tokenPrice ?: 0,
            ]);
            $projInfo['token_id'] = $tokenModel->id;
        }
        if ($fundStartTime) {
            $projInfo['fund_start_time'] = date('Y-m-d H-i-s', strtotime($fundStartTime));
        }
        if ($fundEndTime) {
            $projInfo['fund_end_time'] = date('Y-m-d H-i-s', strtotime($fundEndTime));
        }
        if ($companyEmail) {
            $projInfo['company_email'] = $companyEmail;
        }
        if ($companyAddr) {
            $projInfo['company_addr'] = $companyAddr;
        }
        if ($bannerUrl) {
            $projInfo['banner_url'] = $bannerUrl;
        }

        Model\Project::where('id', $projId)->update($projInfo);

        foreach ($tagList as $tag) {
            Model\ProjTag::firstOrCreate(['proj_id' => $projId, 'tag' => $tag]);
        }

        return $this->output();
    }

    public function addProjMember (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'name' => 'required|string',
            'photoUrl' => 'required|string',
            'position' => 'required|string',
            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjMember::firstOrCreate([
            'proj_id' => $projId,
            'name' => $name,
            'photo_url' => $photoUrl,
            'position' => $position,
            'intro' => $intro,
        ]);

        return $this->output();
    }

    public function getProjMemberList (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $projMemberList = Model\ProjMember::where('proj_id', $projId)->get()->toArray();

        return $this->output(['dataList' => $projMemberList]);
    }

    public function updProjMember (Request $request) {
        $params = $this->validation($request, [
            'memberId' => 'required|numeric',
            'name' => 'required|string',
            'photoUrl' => 'required|string',
            'position' => 'required|string',
            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjMember::where('id', $memberId)->update([
            'name' => $name,
            'photo_url' => $photoUrl,
            'position' => $position,
            'intro' => $intro,
        ]);

        return $this->output();
    }

    public function delProjMember (Request $request) {
        $params = $this->validation($request, [
            'memberId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjMember::where('id', $memberId)->delete();

        return $this->output();
    }

    public function addProjEvent (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'occurTime' => 'required|string',
            'title' => 'required|string',
            'detail' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjEvent::firstOrCreate([
            'proj_id' => $projId,
            'occur_time' => date('Y-m-d H:i:s', strtotime($occurTime)),
            'title' => $title,
            'detail' => $detail,
        ]);

        return $this->output();
    }

    public function getProjEventList (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $projEventList = Model\ProjEvent::where('proj_id', $projId)->get()->toArray();

        return $this->output(['dataList' => $projEventList]);
    }

    public function updProjEvent (Request $request) {
        $params = $this->validation($request, [
            'eventId' => 'required|numeric',
            'occurTime' => 'required|string',
            'title' => 'required|string',
            'detail' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjEvent::where('id', $eventId)->update([
            'occur_time' => date('Y-m-d H:i:s', strtotime($occurTime)),
            'title' => $title,
            'detail' => $detail,
        ]);

        return $this->output();
    }

    public function delProjEvent (Request $request) {
        $params = $this->validation($request, [
            'eventId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjEvent::where('id', $eventId)->delete();

        return $this->output();
    }

    public function addProjSocial (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'name' => 'required|string',
            'logoUrl' => 'required|string',
            'linkUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $linkUrl = strpos($linkUrl, 'http') === 0 ? $linkUrl : 'http://' . $linkUrl;

        $socialObj = Model\Social::firstOrCreate([
            'name' => $name,
            'logo_url' => $logoUrl,
        ]);

        Model\ProjSocial::firstOrCreate([
            'proj_id' => $projId,
            'social_id' => $socialObj->id,
            'link_url' => $linkUrl,
        ]);

        return $this->output();
    }

    public function getProjSocialList (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $projSocialList = Model\ProjSocial::where('proj_id', $projId)
            ->join('social', 'proj_social.social_id', '=', 'social.id')
            ->select('proj_social.id', 'name', 'logo_url', 'link_url')
            ->get()->toArray();

        return $this->output(['dataList' => $projSocialList]);
    }

    public function updProjSocial (Request $request) {
        $params = $this->validation($request, [
            'socialId' => 'required|numeric',
            'name' => 'required|string',
            'logoUrl' => 'required|string',
            'linkUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $linkUrl = strpos($linkUrl, 'http') === 0 ? $linkUrl : 'http://' . $linkUrl;

        $socialObj = Model\Social::firstOrCreate([
            'name' => $name,
            'logo_url' => $logoUrl,
        ]);

        Model\ProjSocial::where('id', $socialId)->update([
            'social_id' => $socialObj->id,
            'link_url' => $linkUrl,
        ]);

        return $this->output();
    }

    public function delProjSocial (Request $request) {
        $params = $this->validation($request, [
            'socialId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjSocial::where('id', $socialId)->delete();

        return $this->output();
    }

    public function addProjPartner (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'name' => 'required|string',
            'logoUrl' => 'required|string',
            'homeUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $homeUrl = strpos($homeUrl, 'http') === 0 ? $homeUrl : 'http://' . $homeUrl;

        Model\ProjPartner::firstOrCreate([
            'proj_id' => $projId,
            'name' => $name,
            'logo_url' => $logoUrl,
            'home_url' => $homeUrl,
        ]);

        return $this->output();
    }

    public function getProjPartnerList (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $projPartnerList = Model\ProjPartner::where('proj_id', $projId)->get()->toArray();

        return $this->output(['dataList' => $projPartnerList]);
    }

    public function updProjPartner (Request $request) {
        $params = $this->validation($request, [
            'partnerId' => 'required|numeric',
            'name' => 'required|string',
            'logoUrl' => 'required|string',
            'homeUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $homeUrl = strpos($homeUrl, 'http') === 0 ? $homeUrl : 'http://' . $homeUrl;

        Model\ProjPartner::where('id', $partnerId)->update([
            'name' => $name,
            'logo_url' => $logoUrl,
            'home_url' => $homeUrl,
        ]);

        return $this->output();
    }

    public function delProjPartner (Request $request) {
        $params = $this->validation($request, [
            'partnerId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjPartner::where('id', $partnerId)->delete();

        return $this->output();
    }

    public function addProjReport (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'name' => 'required|string',
            'logoUrl' => 'required|string',
            'title' => 'required|string',
            'linkUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $linkUrl = strpos($linkUrl, 'http') === 0 ? $linkUrl : 'http://' . $linkUrl;

        $mediaObj = Model\Media::firstOrCreate([
            'name' => $name,
            'logo_url' => $logoUrl,
        ]);

        Model\ProjReport::firstOrCreate([
            'proj_id' => $projId,
            'media_id' => $mediaObj->id,
            'title' => $title,
            'link_url' => $linkUrl,
        ]);

        return $this->output();
    }

    public function getProjReportList (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $projReportList = Model\ProjReport::where('proj_id', $projId)
            ->join('media', 'proj_report.media_id', '=', 'media.id')
            ->select('proj_report.id', 'name', 'title', 'logo_url', 'link_url')
            ->get()->toArray();

        return $this->output(['dataList' => $projReportList]);
    }

    public function updProjReport (Request $request) {
        $params = $this->validation($request, [
            'reportId' => 'required|numeric',
            'name' => 'required|string',
            'logoUrl' => 'required|string',
            'title' => 'required|string',
            'linkUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $linkUrl = strpos($linkUrl, 'http') === 0 ? $linkUrl : 'http://' . $linkUrl;

        $mediaObj = Model\Media::firstOrCreate([
            'name' => $name,
            'logo_url' => $logoUrl,
        ]);

        Model\ProjReport::where('id', $reportId)->update([
            'media_id' => $mediaObj->id,
            'title' => $title,
            'link_url' => $linkUrl,
        ]);

        return $this->output();
    }

    public function delProjReport (Request $request) {
        $params = $this->validation($request, [
            'reportId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjReport::where('id', $reportId)->delete();

        return $this->output();
    }

    public function addProjAdvisor (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'name' => 'required|string',
            'photoUrl' => 'required|string',
            'company' => 'required|string',
            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjAdvisor::firstOrCreate([
            'proj_id' => $projId,
            'name' => $name,
            'photo_url' => $photoUrl,
            'company' => $company,
            'intro' => $intro,
        ]);

        return $this->output();
    }

    public function getProjAdvisorList (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $projAdvisorList = Model\ProjAdvisor::where('proj_id', $projId)->get()->toArray();

        return $this->output(['dataList' => $projAdvisorList]);
    }

    public function updProjAdvisor (Request $request) {
        $params = $this->validation($request, [
            'advisorId' => 'required|numeric',
            'name' => 'required|string',
            'photoUrl' => 'required|string',
            'company' => 'required|string',
            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjAdvisor::where('id', $advisorId)->update([
            'name' => $name,
            'photo_url' => $photoUrl,
            'company' => $company,
            'intro' => $intro,
        ]);

        return $this->output();
    }

    public function delProjAdvisor (Request $request) {
        $params = $this->validation($request, [
            'advisorId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjAdvisor::where('id', $advisorId)->delete();

        return $this->output();
    }

    public function addProject (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'nameCn' => 'required|string',
            'nameEn' => 'required|string',
            'foundDate' => 'required|string',
            'logoUrl' => 'required|string',
            'homeUrl' => 'required|string',
            'shortDesc' => 'required|string',
            'whitePaperUrl' => 'required|string',
            'abstract' => 'required|string',
            'region' => 'required|numeric',
            'buzType' => 'required|numeric',
            'stage' => 'required|numeric',
            'fundStage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $homeUrl = strpos($homeUrl, 'http') === 0 ? $homeUrl : 'http://' . $homeUrl;

        // 创建项目
        $projModel = Model\Project::firstOrCreate([
            'name_cn' => $nameCn,
            'name_en' => $nameEn,
            'found_date' => $foundDate,
            'logo_url' => $logoUrl,
            'home_url' => $homeUrl,
            'white_paper_url' => $whitePaperUrl,
            'region' => $region,
            'stage' => $stage,
            'buz_type' => $buzType,
            'abstract' => $abstract,
            'fund_stage' => $fundStage,
            'short_desc' => $shortDesc,
            'found_date' => date('Y-m-d H-i-s', strtotime($foundDate)),
        ]);

        return $this->output(['projId' => $projModel->id]);
    }

    public function delProject (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjAdvantage::where('proj_id', $projId)->delete();
        Model\ProjAdvisor::where('proj_id', $projId)->delete();
        Model\ProjEvent::where('proj_id', $projId)->delete();
        Model\ProjMember::where('proj_id', $projId)->delete();
        Model\ProjPartner::where('proj_id', $projId)->delete();
        Model\ProjReport::where('proj_id', $projId)->delete();
        Model\ProjSocial::where('proj_id', $projId)->delete();
        Model\ProjTag::where('proj_id', $projId)->delete();
        Model\Project::where('id', $projId)->delete();

        return $this->output();
    }

    public function getProjTagList (Request $request) {
        $regionOptionList = [
            array('label' => '不限', 'value' => 0),
            array('label' => '国内', 'value' => 1),
            array('label' => '国外', 'value' => 2),
        ];
        $buzOptionList = [
            array('label' => '不限', 'value' => 0),
            array('label' => '金融', 'value' => 1),
            array('label' => '数字货币', 'value' => 2),
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
            array('label' => '初创期', 'value' => 1),
            array('label' => '成长发展期', 'value' => 2),
            array('label' => '上市公司', 'value' => 3),
            array('label' => '成熟期', 'value' => 4),
        ];

        return $this->output([
            'region' => array('label' => '地区', 'default' => 0, 'optionList' => $regionOptionList),
            'buzType' => array('label' => '行业', 'default' => 0, 'optionList' => $buzOptionList),
            'stage' => array('label' => '阶段', 'default' => 0, 'optionList' => $stageOptionList),
        ]);
    }

    public function getMediaList (Request $request) {
        $mediaList = Model\Media::select('id', 'name', 'logo_url')->get()->toArray();

        return $this->output(['dataList' => $mediaList]);
    }

    public function getSocialList (Request $request) {
        $socialList = Model\Social::select('id', 'name', 'logo_url')->get()->toArray();

        return $this->output(['dataList' => $socialList]);
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
            $projList = Model\Project::join('proj_tag','project.id','=','proj_tag.proj_id')->where('tag','like',"%$chid%")
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
            ->select('name_cn','logo_url', 'name_en', 'name_short', 'abstract', 'white_paper_url', 'web_url', 'view_times', 'token_id', 'node_amount', 'total_amount', 'plan_amount', 'start_time', 'end_time', 'status', 'admin_id')
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
        $projMediaList = Model\ProjMedia::where('proj_id', $id)
            ->get()->toArray();
        $projData['mediaList'] = $projMediaList;

        // 获取社交链接信息
        $projSocialList = Model\ProjSocial::where('proj_id', $id)
            ->get()->toArray();
        $projData['socialList'] = $projSocialList;

        return response()->json($projData);
    }
}
