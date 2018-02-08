<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function addCandybox (Request $request) {

        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'minAmount' => 'required|numeric',
            'lockTime' => 'required|numeric',
            'totalAmount' => 'required|numeric',
            'interestRate' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 获取钱包地址
        $projData = Model\Project::where('id', $projId)->first();
        if (!$projData) {
            return $this->error(301);
        }

        $walletAddr = $projData->wallet_addr;
        if (!$walletAddr) {
            // 生成钱包地址
            
        }

        Model\CandyBox::create([
            'proj_id' => $projId,
            'min_amount' => $minAmount,
            'lock_time' => $lockTime,
            'total_amount' => $totalAmount,
            'remain_amount' => $remainAmount,
            'interest_rate' => $interestRate,
            'status' => 1,
        ]);
        $totalInterest = $totalAmount * $interestRate;

        return $this->output([
            'walletAddr' => $walletAddr,
            'totalInterest' => $totalInterest,
        ]);
    }

    public function getProjCandyBoxList (Request $request) {

        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $candyBoxList = Model\CandyBox::where('proj_id', $projId)
            ->get()->toArray();

        return $this->output(['dataList' => $candyBoxList]);
    }

    public function getProjTradeRecordList (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'candyBoxId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 验证糖果盒Id
        $candyBoxData = Model\CandyBox::where([ ['id', $candyBoxId], ['proj_id', $projId]])
            ->join('project', 'candy_box.proj_id', '=', 'project.id')
            ->select('candy_box.pay_addr', 'project.candy_wallet_addr')
            ->first();
        if (!$candyBoxData) {
            return $this->error(304);
        }

        // 刷新项目交易记录

        // 获取未确认的项目交易记录
        $projTradeRecordModel = Model\ProjTradeRecord::where([
            ['candy_box_id', 0],
            ['from_addr', $candyBoxData->pay_addr],
            ['to_addr', $candyBoxData->candy_wallet_addr],
        ]);

        $dataCount = $projTradeRecordModel->count();
        $dataList = $projTradeRecordModel->get()->toArray();
        return $this->output(['dataCount' => $dataCount, 'dataList' => $dataList]);
    }

    public function confirmCandyBox (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'candyBoxId' => 'required|numeric',
            'projTradeRecordIdList' => 'required|array',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 获取订单信息
        $candyBoxModel = Model\CandyBox::join('project', 'candy_box.proj_id', '=', 'project.id')
            ->select('candy_wallet_addr', 'pay_addr', 'candy_box.total_amount', 'candy_box.interest_rate')
            ->where([['proj_id', $projId], ['id', $candyBoxId]]);
        $candyBoxData = $candyBoxModel->first();
        if (!$candyBoxData) {
            return $this->error(304);
        }

        $projTradeRecordModel = Model\ProjTradeRecord::where([
            ['id', 'in', $projTradeRecordIdList],
            ['candy_box_id', 0],
            ['from_addr', $candyBoxData->pay_addr],
            ['to_addr', $candyBoxData->candy_wallet_addr],
        ]);

        $recordAmountSum = $projTradeRecordModel->sum('trade_amount');
        if ($recordAmountSum !== $candyBoxData->total_amount * $candyBox->interest_rate) {
            return $this->error(307);
        }

        // 更新交易记录信息
        $projTradeRecordModel->update(['candy_box_id' => $candyBoxId]);
        $candyBoxModel->update(['status' => 2]);

        return $this->output();
    }

    public function signin (Request $request) {
        $params = $this->validation($request, [
            'account' => 'required|string',
            'passwd' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $passwd = md5($passwd);
        $adminData = Model\Admin::where([['account', $account], ['passwd', $passwd]])->first();
        if (!$adminData) {
            $this->error(202);
        }

        $timestamp = time();
        $adminSig = md5($adminData->id . 'test' . $timestamp);
        $expireTime = time() + 3600 * 12;

        setcookie('adminId', $adminData->id, $expireTime);
        setcookie('adminTimestamp', $timestamp, $expireTime);
        setcookie('adminSig', $adminSig, $expireTime);

        return $this->output();
    }

    public function signout () {

        setcookie('adminId', '', time() - 3600);
        setcookie('adminTimestamp', '', time() - 3600);
        setcookie('adminSig', '', time() - 3600);

        return $this->output();
    }

    public function getMediaList (Request $request) {
        $mediaList = Model\Media::select('id', 'name', 'logo_url', 'title_reg', 'release_time_reg', 'banner_url_reg', 'content_reg')->get()->toArray();

        return $this->output(['dataList' => $mediaList]);
    }

    public function addMedia (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'name' => 'required|string',
            'logoUrl' => 'required|string',
            'titleReg' => 'string|nullable',
            'releaseTimeReg' => 'string|nullable',
            'bannerUrlReg' => 'string|nullable',
            'contentReg' => 'string|nullable',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $mediaData = [
            'name' => $name,
            'logo_url' => $logoUrl,
        ];
        if ($titleReg) {
            $mediaData['title_reg'] = $titleReg;
        }
        if ($releaseTimeReg) {
            $mediaData['release_time_reg'] = $releaseTimeReg;
        }
        if ($bannerUrlReg) {
            $mediaData['banner_url_reg'] = $bannerUrlReg;
        }
        if ($contentReg) {
            $mediaData['content_reg'] = $contentReg;
        }
        Model\Media::firstOrCreate($mediaData);

        return $this->output();
    }

    public function updMedia (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'mediaId' => 'required|numeric',
            'name' => 'required|string',
            'logoUrl' => 'required|string',
            'titleReg' => 'string|nullable',
            'releaseTimeReg' => 'string|nullable',
            'bannerUrlReg' => 'string|nullable',
            'contentReg' => 'string|nullable',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $mediaData = [
            'name' => $name,
            'logo_url' => $logoUrl,
        ];
        if ($titleReg) {
            $mediaData['title_reg'] = $titleReg;
        }
        if ($releaseTimeReg) {
            $mediaData['release_time_reg'] = $releaseTimeReg;
        }
        if ($bannerUrlReg) {
            $mediaData['banner_url_reg'] = $bannerUrlReg;
        }
        if ($contentReg) {
            $mediaData['content_reg'] = $contentReg;
        }

        Model\Media::where('id', $mediaId)->update($mediaData);

        return $this->output();
    }

    public function delMedia (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'mediaId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Media::where('id', $mediaId)->delete();

        return $this->output();
    }

    public function getSocialList (Request $request) {
        $socialList = Model\Social::select('id', 'font_class', 'name')->get()->toArray();

        return $this->output(['dataList' => $socialList]);
    }

    public function addSocial (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'name' => 'required|string',
            'fontClass' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Social::firstOrCreate([
            'name' => $name,
            'font_class' => $fontClass,
        ]);

        return $this->output();
    }

    public function updSocial (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'socialId' => 'required|numeric',
            'name' => 'required|string',
            'fontClass' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Social::where('id', $socialId)->update([
            'name' => $name,
            'font_class' => $fontClass,
        ]);

        return $this->output();
    }

    public function delSocial (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'socialId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Social::where('id', $socialId)->delete();

        return $this->output();
    }

    public function getProjBasicList (Request $request) {
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
        $projList = Model\Project::offset($offset)->limit($perpage)->get()->toArray();
        $dataCount = Model\Project::count();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $projList
        ]);
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
            'tokenPrice' => 'string|nullable',
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
            ->select('proj_social.id', 'name', 'social_id', 'font_class', 'link_url')
            ->get()->toArray();

        return $this->output(['dataList' => $projSocialList]);
    }

    public function addProjSocial (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'socialId' => 'required|numeric',
            'linkUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $linkUrl = strpos($linkUrl, 'http') === 0 ? $linkUrl : 'http://' . $linkUrl;

        $isExist = Model\Social::where('id', $socialId)->count();
        if (!$isExist) {
            $this->error(302);
        }

        Model\ProjSocial::firstOrCreate([
            'proj_id' => $projId,
            'social_id' => $socialId,
            'link_url' => $linkUrl,
        ]);

        return $this->output();
    }

    public function updProjSocial (Request $request) {
        $params = $this->validation($request, [
            'projSocialId' => 'required|numeric',
            'socialId' => 'required|numeric',
            'linkUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $linkUrl = strpos($linkUrl, 'http') === 0 ? $linkUrl : 'http://' . $linkUrl;

        $isExist = Model\Social::where('id', $socialId)->count();
        if (!$isExist) {
            $this->error(302);
        }

        Model\ProjSocial::where('id', $projSocialId)->update([
            'social_id' => $socialId,
            'link_url' => $linkUrl,
        ]);

        return $this->output();
    }

    public function delProjSocial (Request $request) {
        $params = $this->validation($request, [
            'projSocialId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjSocial::where('id', $projSocialId)->delete();

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
            ->select('proj_report.id', 'media_id', 'name', 'title', 'logo_url', 'link_url')
            ->get()->toArray();

        return $this->output(['dataList' => $projReportList]);
    }

    public function addProjReport (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'mediaId' => 'required|numeric',
            'title' => 'required|string',
            'linkUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $linkUrl = strpos($linkUrl, 'http') === 0 ? $linkUrl : 'http://' . $linkUrl;

        $isExist = Model\Media::where('id', $mediaId)->count();
        if (!$isExist) {
            $this->error(303);
        }

        Model\ProjReport::firstOrCreate([
            'proj_id' => $projId,
            'media_id' => $mediaId,
            'title' => $title,
            'link_url' => $linkUrl,
        ]);

        return $this->output();
    }

    public function updProjReport (Request $request) {
        $params = $this->validation($request, [
            'projReportId' => 'required|numeric',
            'mediaId' => 'required|numeric',
            'title' => 'required|string',
            'linkUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $linkUrl = strpos($linkUrl, 'http') === 0 ? $linkUrl : 'http://' . $linkUrl;

        $isExist = Model\Media::where('id', $mediaId)->count();
        if (!$isExist) {
            $this->error(303);
        }

        Model\ProjReport::where('id', $projReportId)->update([
            'media_id' => $mediaId,
            'title' => $title,
            'link_url' => $linkUrl,
        ]);

        return $this->output();
    }

    public function delProjReport (Request $request) {
        $params = $this->validation($request, [
            'projReportId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjReport::where('id', $projReportId)->delete();

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
}
