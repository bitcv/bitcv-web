<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\DB;
use App\Utils\BaseUtil;

class AdminController extends Controller
{

    public function getUser() {
        return $this->output(\App\Utils\Auth::$user);
    }

    public function authProject (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Project::where('id', $projId)->update(['status' => 1]);

        return $this->output();
    }

    public function clearProjAuth (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Project::where('id', $projId)->update(['status' => 0]);

        return $this->output();
    }

    public function addDepositBox (Request $request) {

        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'minAmount' => 'required|string',
            'lockTime' => 'required|numeric',
            'totalAmount' => 'required|string',
            'interestRate' => 'required|string',
            'fromAddr' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        // 获取钱包地址
        $projData = Model\Project::join('token', 'project.token_id', '=', 'token.id')
            ->where([
                ['project.id', $projId],
                ['project.status', 1],
            ])->first();
        if (!$projData) {
            return $this->error(301);
        }

        if (!$projData->contract_addr) {
            return $this->error(308);
        }

        $walletAddr = $projData->wallet_addr;
        if (!$walletAddr) {
            // 生成钱包地址
            $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/createWallet', array(
                'symbol' => 'eth',
            ));
            $resArr = json_decode($resJson, true);
            if (!$resArr || $resArr['errcode'] !== 0) {
                return $this->error();
            }

            $walletId = $resArr['data']['walletId'];
            $walletAddr = $resArr['data']['walletAddr'];
            Model\Project::where('id', $projId)->update([
                'wallet_id' => $walletId,
                'wallet_addr' => strtolower($walletAddr),
            ]);
        }

        $depositBoxData = Model\DepositBox::create([
            'proj_id' => $projId,
            'min_amount' => $minAmount,
            'lock_time' => $lockTime,
            'total_amount' => $totalAmount,
            'remain_amount' => $totalAmount,
            'interest_rate' => $interestRate,
            'from_addr' => strtolower($fromAddr),
            'to_addr' => strtolower($walletAddr),
            'contract_addr' => strtolower($projData->contract_addr),
            'status' => 0,
        ]);
        $totalInterest = $totalAmount * $interestRate;

        return $this->output([
            'fromAddr' => strtolower($fromAddr),
            'toAddr' => strtolower($walletAddr),
            'totalInterest' => $totalInterest,
            'depositBoxId' => $depositBoxData->id,
        ]);
    }

    public function delDepositBox (Request $request) {
        $params = $this->validation($request, [
            'depositBoxId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $depositBoxModel = Model\DepositBox::where([
            ['id', $depositBoxId],
            ['status', 0]
        ]);

        if (!$depositBoxModel->count()) {
            return $this->error(100);
        }
        $depositBoxModel->delete();

        return $this->output();
    }

    public function getBoxTxRecordList (Request $request) {
        $params = $this->validation($request, [
            'depositBoxId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $depositBoxData = Model\DepositBox::find($depositBoxId);
        if (!$depositBoxData) {
            return $this->error();
        }
        $postData = array(
            'fromAddr' => $depositBoxData->from_addr,
            'toAddr' => $depositBoxData->to_addr,
            'contractAddr' => $depositBoxData->contract_addr,
        );
        $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/getTxRecordList', $postData);
        $resArr = json_decode($resJson, true);
        if (!$resArr || $resArr['errcode'] !== 0) {
            return $this->error();
        }
        $txRecordList = $resArr['data']['dataList'];

        return $this->output(['dataList' => $txRecordList]);
    }

    public function confirmBoxTx (Request $request) {

        $params = $this->validation($request, [
            'depositBoxId' => 'required|numeric',
            'txRecordIdList' => 'nullable|array',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $depositBoxData = Model\DepositBox::find($depositBoxId);
        if (!$depositBoxData) {
            return $this->error();
        }

        if ($txRecordIdList == null) {
            return $this->error(309);
        }

        $orderAmount = $depositBoxData->interest_rate * $depositBoxData->total_amount * $depositBoxData->lock_time / 12;

        $postData = array(
            'fromAddr' => $depositBoxData->from_addr,
            'toAddr' => $depositBoxData->to_addr,
            'contractAddr' => $depositBoxData->contract_addr,
            'orderAmount' => $orderAmount . '',
            'txRecordIdList' => $txRecordIdList,
        );
        $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/confirmTx', $postData);
        $resArr = json_decode($resJson, true);
        if (!$resArr || $resArr['errcode'] !== 0) {
            return $this->error(307);
        }

        $txRecordList = $resArr['data']['dataList'];
        foreach ($txRecordList as $txRecord) {
            Model\OrderTxRecord::create([
                'tx_record_id' => $txRecord['id'],
                'tx_hash' => $txRecord['tx_hash'],
                'tx_type' => 1,
                'target_id' => $depositBoxId,
            ]);
        }

        Model\DepositBox::where('id', $depositBoxId)->update(['status' => 1]);

        return $this->output();
    }

    public function getProjDepositBoxList (Request $request) {

        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $depositBoxList = Model\DepositBox::where('proj_id', $projId)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

        return $this->output(['dataList' => $depositBoxList]);
    }

    public function getProjDepositOrderList (Request $request) {

        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        //$depositOrderList = Model\DepositOrder::
            //->join('deposit_box', 'deposit_order.deposit_box_id', '=', 'deposit_box.id')
            //->where('deposit_box.proj_id', $projId)
            //->orderBy('deposit_order.created_at', 'desc')
            //->get()->toArray();
        $depositOrderList = Model\DepositOrder::join('user', 'deposit_order.user_id', '=', 'user.id')
            ->join('deposit_box', 'deposit_order.deposit_box_id', '=', 'deposit_box.id')
            ->where('deposit_box.proj_id', $projId)
            ->select('user.mobile', 'deposit_order.id', 'deposit_order.deposit_box_id', 'deposit_order.order_amount', 'deposit_order.from_addr', 'deposit_order.to_addr', 'deposit_order.to_addr', 'deposit_order.contract_addr', 'deposit_order.status', 'deposit_order.created_at')
            ->orderBy('deposit_order.created_at', 'desc')
            ->get()->toArray();

        return $this->output(['dataList' => $depositOrderList]);
    }

    public function getAdminDepositBoxList (Request $request) {

        //$params = $this->validation($request, [
            //'projId' => 'required|numeric',
        //]);
        //if ($params === false) {
            //return $this->error(100);
        //}
        //extract($params);

        //$depositBoxList = Model\DepositBox::where('proj_id', $projId)
        $depositBoxList = Model\DepositBox::orderBy('created_at', 'desc')
            ->get()->toArray();

        return $this->output(['dataList' => $depositBoxList]);
    }

    public function getAdminDepositOrderList (Request $request) {
        $depositOrderList = Model\DepositOrder::join('user', 'deposit_order.user_id', '=', 'user.id')
            ->select('user.mobile', 'deposit_order.id', 'deposit_order.deposit_box_id', 'deposit_order.order_amount', 'deposit_order.from_addr', 'deposit_order.to_addr', 'deposit_order.to_addr', 'deposit_order.contract_addr', 'deposit_order.status', 'deposit_order.created_at')
            ->orderBy('deposit_order.created_at', 'desc')
            ->get()->toArray();
        return $this->output(['dataList' => $depositOrderList]);
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
            return $this->error(202);
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
        //$projList = Model\Project::offset($offset)->limit($perpage)->get()->toArray();
        $projList = Model\Project::get()->toArray();
        $dataCount = Model\Project::count();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $projList
        ]);
    }

    //申请项目创建
    public function apply(Request $request) {
        $uid = \App\Utils\Auth::$uid;
        $adminproj = Model\Admin::where('id', $uid)->first();
        if ($adminproj) {
            return $this->error(100, '每人只能创建并管理一个项目');
        }
        $data = $this->validation($request, [
            'name_cn' => 'required|string',
            'name_en' => 'required|string',
        ]);
        if (Model\Project::where('name_cn', $data['name_cn'])->first()) {
            return $this->error(100);
        } else {
            $proj = Model\Project::create($data);
            Model\Admin::insert(['id'=>$uid, 'proj_id'=>$proj->id]);
            return $this->output(['projId' => $proj->id]);
        }
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
            $projBasicInfo['contractAddr'] = $tokenModel->contract_addr;
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
            'contractAddr' => 'string|nullable'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $projInfo = [
            'name_cn' => $nameCn,
            'name_en' => $nameEn,
            'found_date' => date('Y-m-d H-i-s', strtotime($foundDate)),
            'logo_url' => $logoUrl,
            'short_desc' => $shortDesc,
            'white_paper_url' => $whitePaperUrl,
            'abstract' => $abstract,
            'region' => $region,
            'buz_type' => $buzType,
            'stage' => $stage,
            'fund_stage' => $fundStage,
        ];

        if ($contractAddr) {
            $contractAddr = strtolower($contractAddr);
            // 查询token数据库
            $tokenObj = Model\Token::where('contract_addr', $contractAddr)->first();
            if ($tokenObj) {
                $projInfo['token_id'] = $tokenObj->id;
            } else {
                // 数据库中没有则抓取
                $url = "https://etherscan.io/searchHandler?t=t&term=$contractAddr";
                $result = file_get_contents($url);
                $result = json_decode($result, true);
                $isExist = false;
                foreach ($result as $item) {
                    preg_match('/(0x.*?)\\s.*TOKEN: (.*) \\((.*)\\)/is', $item, $match);
                    $resContractAddr = $match[1];
                    if ($contractAddr === strtolower($resContractAddr)) {
                        $tokenName = $match[2];
                        $tokenSymbol = $match[3];
                        $isExist = true;
                        break;
                    }
                }
                if ($isExist) {
                    // 抓取到后填充值数据库
                    $tokenObj = Model\Token::create([
                        'name' => $tokenName,
                        'symbol' => $tokenSymbol,
                        'contract_addr' => $contractAddr,
                    ]);
                    $projInfo['token_id'] = $tokenObj->id;
                }
            }
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
        if ($homeUrl) {
            $homeUrl = strpos($homeUrl, 'http') === 0 ? $homeUrl : 'http://' . $homeUrl;
            $projInfo['home_url'] = $homeUrl;
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
            'photoUrl' => 'nullable|string',
            'position' => 'required|string',
            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $memberData = [
            'proj_id' => $projId,
            'name' => $name,
            'position' => $position,
            'intro' => $intro,
        ];

        if ($photoUrl) {
            $memberData['photo_url'] = $photoUrl;
        }

        Model\ProjMember::firstOrCreate($memberData);

        return $this->output();
    }

    public function updProjMember (Request $request) {
        $params = $this->validation($request, [
            'memberId' => 'required|numeric',
            'name' => 'required|string',
            'photoUrl' => 'nullable|string',
            'position' => 'required|string',
            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $memberData = [
            'name' => $name,
            'position' => $position,
            'intro' => $intro,
        ];

        if ($photoUrl) {
            $memberData['photo_url'] = $photoUrl;
        }

        Model\ProjMember::where('id', $memberId)->update($memberData);

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
            'photoUrl' => 'nullable|string',
            'company' => 'required|string',
            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $advisorData = [
            'proj_id' => $projId,
            'name' => $name,
            'company' => $company,
            'intro' => $intro,
        ];

        if ($photoUrl) {
            $advisorData['photo_url'] = $photoUrl;
        }

        Model\ProjAdvisor::firstOrCreate($advisorData);

        return $this->output();
    }

    public function updProjAdvisor (Request $request) {
        $params = $this->validation($request, [
            'advisorId' => 'required|numeric',
            'name' => 'required|string',
            'photoUrl' => 'nullable|string',
            'company' => 'required|string',
            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $advisorData = [
            'name' => $name,
            'company' => $company,
            'intro' => $intro,
        ];

        if ($photoUrl) {
            $advisorData['photo_url'] = $photoUrl;
        }

        Model\ProjAdvisor::where('id', $advisorId)->update($advisorData);

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

    public function delMediaReport(Request $request){
        $params = $this->validation($request, [
           'id' => 'required|numeric'
        ]);
        if($params === false){
            return $this->error(100);
        }
        extract($params);

        Model\CrawlerSocialNews::where('id',$id)->delete();

        return $this->output();
    }

    public function getMediaReportList(Request $request){
        $projAdvisorList = Model\CrawlerSocialNews::get()->toArray();

        return $this->output(['medisReportList' => $projAdvisorList]);
    }
}
