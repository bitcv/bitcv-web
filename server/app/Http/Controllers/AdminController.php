<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\DB;
use App\Utils\BaseUtil;
use App\Utils\DictUtil;

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
        $params = $this->validation($request, [
            'perpage' => 'required|numeric',
            'pageno' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $offset = $perpage * ($pageno - 1);
        $depositBoxModel = Model\DepositBox::join('project', 'deposit_box.proj_id', '=', 'project.id');
        $dataCount = $depositBoxModel->count();
        $dataList = $depositBoxModel
            ->select('deposit_box.id', 'deposit_box.proj_id', 'deposit_box.total_amount', 'deposit_box.min_amount', 'deposit_box.remain_amount', 'deposit_box.lock_time', 'deposit_box.interest_rate', 'deposit_box.from_addr', 'deposit_box.to_addr', 'deposit_box.contract_addr', 'deposit_box.status', 'project.name_cn')
            ->orderBy('deposit_box.created_at', 'desc')
            ->offset($offset)->limit($perpage)
            ->get()->toArray();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $dataList,
            'statusDict' => DictUtil::DepositBox_Status,
        ]);
    }

    public function getAdminDepositOrderList (Request $request) {
        $params = $this->validation($request, [
            'perpage' => 'required|numeric',
            'pageno' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $offset = $perpage * ($pageno - 1);
        $depositOrderModel = Model\DepositOrder::join('user', 'deposit_order.user_id', '=', 'user.id')
            ->join('deposit_box', 'deposit_order.deposit_box_id', '=', 'deposit_box.id')
            ->join('project', 'deposit_box.proj_id', '=', 'project.id')
            ->select('deposit_order.id', 'deposit_order.deposit_box_id', 'deposit_order.order_amount', 'deposit_order.from_addr', 'deposit_order.to_addr', 'deposit_order.to_addr', 'deposit_order.contract_addr', 'deposit_order.status', 'deposit_order.created_at', 'deposit_box.proj_id', 'deposit_box.total_amount', 'deposit_box.min_amount', 'deposit_box.remain_amount', 'deposit_box.lock_time', 'deposit_box.interest_rate', 'project.name_cn', 'user.mobile');
        $dataCount = $depositOrderModel->count();
        $dataList = $depositOrderModel
            ->orderBy('deposit_order.created_at', 'desc')
            ->offset($offset)->limit($perpage)
            ->get()->toArray();
            return $this->output([
                'dataCount' => $dataCount,
                'dataList' => $dataList,
                'statusDict' => DictUtil::DepositOrder_Status,
            ]);
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

    public function getTokenList (Request $request) {

        $params = $this->validation($request, [
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) return $this->error(100);
        extract($params);

        $offset = $perpage * ($pageno - 1);
        $tokenModel = Model\Token::select('id', 'name', 'symbol', 'logo_url', 'price', 'protocol', 'contract_addr');
        $dataCount = $tokenModel->count();
        $dataList = $tokenModel->offset($offset)
            ->limit($perpage)
            ->get()->toArray();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $dataList,
            'statusDict' => DictUtil::Token_Protocol,
        ]);
    }

    public function addToken (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'name' => 'nullable|string',
            'symbol' => 'required|string',
            'logoUrl' => 'nullable|string',
            'contractAddr' => 'nullable|string',
            'protocol' => 'nullable|numeric',
            'price' => 'nullable|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $tokenData = ['symbol' => $symbol];
        if ($name) $tokenData['name'] = $name;
        if ($logoUrl) $tokenData['logo_url'] = $logoUrl;
        if ($contractAddr) $tokenData['contract_addr'] = $contractAddr;
        if ($protocol) $tokenData['protocol'] = $protocol;
        if ($price) $tokenData['price'] = $price;

        Model\Token::firstOrCreate($tokenData);

        return $this->output();
    }

    public function updToken (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'tokenId' => 'required|numeric',
            'name' => 'nullable|string',
            'symbol' => 'required|string',
            'logoUrl' => 'nullable|string',
            'contractAddr' => 'nullable|string',
            'protocol' => 'nullable|numeric',
            'price' => 'nullable|string'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $tokenData = [];
        if ($name) $tokenData['name'] = $name;
        if ($symbol) $tokenData['symbol'] = $symbol;
        if ($logoUrl) $tokenData['logo_url'] = $logoUrl;
        if ($protocol) $tokenData['protocol'] = $protocol;
        if ($contractAddr) $tokenData['contract_addr'] = $contractAddr;
        if ($price) $tokenData['price'] = $price;

        Model\Token::where('id', $tokenId)->update($tokenData);

        return $this->output();
    }

    public function delToken (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'tokenId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Project::where('token_id', $tokenId)->update(['token_id' => 0]);

        Model\Token::where('id', $tokenId)->delete();

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

        $projMemberList = Model\ProjMember::where('proj_id', $projId)
            ->get()->toArray();
            //::join('person','person.id','=','proj_member.per_id')
            //->where('proj_id', $projId)
            //->select('proj_member.id','person.logo_url','person.position','person.intro','person.name')


        return $this->output(['dataList' => $projMemberList]);
    }

    public function addProjMember (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'memberId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $memberData = [
            'proj_id' => $projId,
            'per_id' => $memberId,
        ];

//        if ($photoUrl) {
//            $memberData['photo_url'] = $photoUrl;
//        }

        Model\ProjMember::firstOrCreate($memberData);

        return $this->output();
    }

    public function addProjAdvisor(Request $request){

        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'memberId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $memberData = [
            'proj_id' => $projId,
            'advisor_id' => $memberId,
        ];

//        if ($photoUrl) {
//            $memberData['photo_url'] = $photoUrl;
//        }

        Model\ProjAdvisor::firstOrCreate($memberData);

        return $this->output();

    }

    public function updProjMember (Request $request) {
        $params = $this->validation($request, [
            'memberId' => 'required|numeric',
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
        if($socialId == 5){
            $linkUrl = $linkUrl;
        }else{
            $linkUrl = strpos($linkUrl, 'http') === 0 ? $linkUrl : 'http://' . $linkUrl;
        }


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

        if($socialId == 5){
            $linkUrl = $linkUrl;
        }else{
            $linkUrl = strpos($linkUrl, 'http') === 0 ? $linkUrl : 'http://' . $linkUrl;
        }

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

        $projPartnerList = Model\ProjPartner::join('institution','institution.id','=','proj_partner.institu_id')
            ->where('proj_partner.proj_id', $projId)
            ->select('proj_partner.id','institution.name','institution.home_url','institution.logo_url')
            ->get()->toArray();
        //
        return $this->output(['dataList' => $projPartnerList]);
    }

    public function addProjPartner (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'partnerId'=> 'required|numeric',
//            'name' => 'required|string',
//            'logoUrl' => 'required|string',
//            'homeUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        //$homeUrl = strpos($homeUrl, 'http') === 0 ? $homeUrl : 'http://' . $homeUrl;

        Model\ProjPartner::firstOrCreate([
            'proj_id' => $projId,
            'institu_id' => $partnerId,
//            'name' => $name,
//            'logo_url' => $logoUrl,
//            'home_url' => $homeUrl,
        ]);

        return $this->output();
    }

    public function updProjPartner (Request $request) {
        $params = $this->validation($request, [
            'partnerId' => 'required|numeric',
            'projId' => 'required|numeric',
//            'name' => 'required|string',
//            'logoUrl' => 'required|string',
//            'homeUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        //$homeUrl = strpos($homeUrl, 'http') === 0 ? $homeUrl : 'http://' . $homeUrl;

        Model\ProjPartner::where('id', $partnerId)->update([
              'institu_id' => $projId,
//            'name' => $name,
//            'logo_url' => $logoUrl,
//            'home_url' => $homeUrl,
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
            'projId' => 'required',
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

        $projAdvisorList = Model\ProjAdvisor::join('advisor','advisor.id','=','proj_advisor.advisor_id')
            ->where('proj_id', $projId)
            ->select('proj_advisor.id','advisor.name','advisor.company','advisor.intro','advisor.logo_url')
            ->get()->toArray();

        return $this->output(['dataList' => $projAdvisorList]);
    }

//    public function addProjIAdvisor (Request $request) {
//        $params = $this->validation($request, [
//            'projId' => 'required',
//            'name' => 'required|string',
//            'photoUrl' => 'nullable|string',
//            'company' => 'required|string',
//            'intro' => 'required|string',
//        ]);
//        if ($params === false) {
//            return $this->error(100);
//        }
//        extract($params);
//
//        $advisorData = [
//            'proj_id' => $projId,
//            'name' => $name,
//            'company' => $company,
//            'intro' => $intro,
//        ];
//
//        if ($photoUrl) {
//            $advisorData['photo_url'] = $photoUrl;
//        }
//
//        Model\ProjAdvisor::firstOrCreate($advisorData);
//
//        return $this->output();
//    }

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
            'advisorId' => 'required|numeric',
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
        $params = $this->validation($request, [
            'perpage' => 'required|numeric',
            'pageno' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $offset = $perpage * ($pageno - 1);

        $projAdvisor = Model\CrawlerSocialNews::join('project','crawler_socialnews.proj_id','=','project.id')
            ->join('social','crawler_socialnews.social_id','=','social.id');
        $projAdvisorList = $projAdvisor->offset($offset)->limit($perpage)->get()->toArray();
        $dataCount = $projAdvisor->count();

        return $this->output([
            'medisReportList' => $projAdvisorList,
            'dataCount' => $dataCount,
        ]);
    }

    public function getMediaReportCount(Request $request){
        $projAdvisor = Model\CrawlerSocialNews::join('project','crawler_socialnews.proj_id','=','project.id')
            ->join('social','crawler_socialnews.social_id','=','social.id');
        $dataCount = $projAdvisor->count();

        return $this->output([
            'dataCount' => $dataCount,
        ]);
    }

    public function getInstituList(Request $request){
        $params = $this->validation($request, [
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $offset = $perpage * ($pageno - 1);
        $projList = Model\Institution::offset($offset)->limit($perpage)->get()->toArray();
        $dataCount = Model\Institution::count();

        return $this->output([
            'dataCount' => $dataCount,
            'instituList' => $projList
        ]);

        //$instituList = Model\Institution::select('id', 'name', 'logo_url','home_url')->get()->toArray();
        //return $this->output(['instituList' => $instituList]);
    }

    public function getInstituNameList(Request $request){
        $instituList = Model\Institution::select('id', 'name', 'logo_url','home_url')->get()->toArray();
        return $this->output(['instituList' => $instituList]);
    }

    public function delInstitu(Request $request){
        $params = $this->validation($request, [
            'mediaId' => 'required|numeric'
        ]);
        if($params === false){
            return $this->error(100);
        }
        extract($params);

        Model\Institution::where('id',$mediaId)->delete();

        return $this->output();
    }


    public function addInstitu(Request $request){

        //获取请求参数
        $params = $this->validation($request, [
            'name' => 'required|string',
            'logoUrl' => 'nullable|string',
            'homeUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $instituData = [
            'name' => $name,
            'logo_url' => $logoUrl,
            'home_url' => $homeUrl,
        ];

        Model\Institution::firstOrCreate($instituData);

        return $this->output();
    }

    public function updInstitu(Request $request){
        //获取请求参数
        $params = $this->validation($request, [
            'mediaId' => 'required|numeric',
            'name' => 'required|string',
            'logoUrl' => 'nullable|string',
            'homeUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $mediaData = [
            'name' => $name,
            'logo_url' => $logoUrl,
            'home_url' => $homeUrl,
        ];

        Model\Institution::where('id', $mediaId)->update($mediaData);

        return $this->output();

    }

    public function getPerList(Request $request){

        $params = $this->validation($request, [
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $offset = $perpage * ($pageno - 1);
        $projList = Model\Advisor::offset($offset)->limit($perpage)->get()->toArray();
        $dataCount = Model\Advisor::count();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $projList
        ]);

        //$perList = Model\Advisor::select('id', 'name', 'logo_url','company','intro')->get()->toArray();
        //return $this->output(['perList' => $perList]);
    }

    public function getAdvList(Request $request){

        $perList = Model\Advisor::select('id', 'name', 'logo_url','company','intro')->get()->toArray();
        return $this->output(['perList' => $perList]);
    }

    public function delPerson(Request $request){

        //获取请求参数
        $params = $this->validation($request, [
            'mediaId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Advisor::where('id', $mediaId)->delete();

        return $this->output();
    }

    public function updPerson(Request $request){
        //获取请求参数
        $params = $this->validation($request, [
            'mediaId' => 'required|numeric',
            'name' => 'nullable|string',
            'logoUrl' => 'nullable|string',
            'position' => 'nullable|string',
            'intro' => 'nullable|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $mediaData = [
            'name' => $name,
            'logo_url' => $logoUrl,
            'company' => $position,
            'intro' => $intro,
        ];

        Model\Advisor::where('id', $mediaId)->update($mediaData);

        return $this->output();
    }

    public function addPerson(Request $request){

        //获取请求参数
        $params = $this->validation($request, [
            'name' => 'required|string',
            'logoUrl' => 'nullable|string',
            'position' => 'required|string',
            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $perData = [
            'name' => $name,
            'logo_url' => $logoUrl,
            'company' => $position,
            'intro' => $intro,
        ];

        Model\Advisor::firstOrCreate($perData);

        return $this->output();
    }

    public function getExchangeList(Request $request){

        $params = $this->validation($request, [
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $offset = $perpage * ($pageno - 1);
        $projList = Model\Exchange::offset($offset)->limit($perpage)->get()->toArray();
        $dataCount = Model\Exchange::count();

        return $this->output([
            'dataCount' => $dataCount,
            'exchangeList' => $projList
        ]);


    }

    public function getExchangeNameList(Request $request){

        $exchangeList = Model\Exchange::select('id', 'name', 'logo_url','home_url','intro')->get()->toArray();
        return $this->output(['exchangeList' => $exchangeList]);
    }

    public function delExchange(Request $request){

        //获取请求参数
        $params = $this->validation($request, [
            'mediaId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Exchange::where('id', $mediaId)->delete();

        return $this->output();
    }

    public function addExchange(Request $request){
        //获取请求参数
        $params = $this->validation($request, [
            'name' => 'required|string',
            'logoUrl' => 'nullable|string',
            'homeUrl' => 'required|string',
            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $perData = [
            'name' => $name,
            'logo_url' => $logoUrl,
            'home_url' => $homeUrl,
            'intro' => $intro,
        ];

        Model\Exchange::firstOrCreate($perData);

        return $this->output();

    }

    public function updExchange(Request $request){
        //获取请求参数
        $params = $this->validation($request, [
            'mediaId' => 'required|numeric',
            'name' => 'nullable|string',
            'logoUrl' => 'nullable|string',
            'homeUrl' => 'nullable|string',
            'intro' => 'nullable|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $exchangeData = [
            'name' => $name,
            'logo_url' => $logoUrl,
            'home_url' => $homeUrl,
            'intro' => $intro,
        ];

        Model\Exchange::where('id', $mediaId)->update($exchangeData);

        return $this->output();

    }

    public function getProjExchangeList(Request $request){
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $projMemberList = Model\ProjExchange::join('exchange','exchange.id','=','proj_exchange.exchan_id')
            ->where('proj_exchange.proj_id', $projId)
            ->select('proj_exchange.id','exchange.home_url','exchange.name','exchange.logo_url','exchange.intro')
            ->get()->toArray();

        return $this->output(['dataList' => $projMemberList]);

    }

    public function addProjExchange(Request $request){
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
            'memberId' => 'required|numeric',
//            'name' => 'required|string',
//            'photoUrl' => 'nullable|string',
//            'position' => 'required|string',
//            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $memberData = [
            'proj_id' => $projId,
            'exchan_id' => $memberId,
//            'name' => $name,
//            'position' => $position,
//            'intro' => $intro,
        ];

//        if ($photoUrl) {
//            $memberData['photo_url'] = $photoUrl;
//        }

        Model\ProjExchange::firstOrCreate($memberData);

        return $this->output();
    }

    public function updProjExchange(Request $request){
        $params = $this->validation($request, [
            'memberId' => 'required|numeric',
            'projId' => 'required|numeric',
//            'name' => 'required|string',
//            'photoUrl' => 'nullable|string',
//            'position' => 'required|string',
//            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $memberData = [
            'exchan_id' => $prodId,
//            'name' => $name,
//            'position' => $position,
//            'intro' => $intro,
        ];

//        if ($photoUrl) {
//            $memberData['photo_url'] = $photoUrl;
//        }

        Model\ProjExchange::where('id', $memberId)->update($memberData);

        return $this->output();
    }

    public function delProjExchange(Request $request){
        $params = $this->validation($request, [
            'partnerId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjExchange::where('id', $partnerId)->delete();

        return $this->output();
    }


    public function addProjIMember (Request $request) {
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
            'photo_url' => $photoUrl,
        ];
//        if ($photoUrl) {
//            $memberData['logo_url'] = $photoUrl;
//        }
        Model\ProjMember::firstOrCreate($memberData);
//        $data = [
//            'proj_id' => $projId,
//            'per_id' => $result->id,
//        ];
//        Model\ProjMember::firstOrCreate($data);
        return $this->output();
    }

    public function addProjIPartner(Request $request){
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
        $partnerData = [
            'name' => $name,
            'logo_url' => $logoUrl,
            'home_url' => $homeUrl,
        ];

        $result = Model\Institution::firstOrCreate($partnerData);

        $data = [
            'proj_id' => $projId,
            'institu_id' => $result->id,
        ];
        Model\ProjPartner::firstOrCreate($data);
        return $this->output();
    }

    public function addProjIExchange(Request $request){
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

        $exchanData = [
            'name' => $name,
            'logo_url' => $logoUrl,
            'home_url' => $homeUrl,
        ];

        $result = Model\Exchange::firstOrCreate($exchanData);

        $data = [
            'proj_id' => $projId,
            'exchan_id' => $result->id,
        ];
        Model\ProjExchange::firstOrCreate($data);
        return $this->output();
    }

    public function addProjIAdvisor(Request $request){
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
        //$homeUrl = strpos($homeUrl, 'http') === 0 ? $homeUrl : 'http://' . $homeUrl;

        $exchanData = [
            'name' => $name,
            'logo_url' => $photoUrl,
            'company' => $company,
            'intro' => $intro,
        ];

        $result = Model\Advisor::firstOrCreate($exchanData);

        $data = [
            'proj_id' => $projId,
            'advisor_id' => $result->id,
        ];
        Model\ProjAdvisor::firstOrCreate($data);
        return $this->output();
    }

//    public function delProjAdvisor (Request $request) {
//        $params = $this->validation($request, [
//            'memberId' => 'required|numeric',
//        ]);
//        if ($params === false) {
//            return $this->error(100);
//        }
//        extract($params);
//
//        Model\ProjAdvisor::where('id', $memberId)->delete();
//
//        return $this->output();
//    }

}
