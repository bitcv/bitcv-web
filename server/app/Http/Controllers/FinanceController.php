<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\DB;
use App\Utils\DictUtil;
use Excel;

/**
 *
 */
class FinanceController extends Controller
{
    public function getFinanceList(Request $request)
    {
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
        $fmodel = DB::table('finance')->select('*');
        $dataList = $fmodel->orderBy('transaction_time', 'desc')->offset($offset)
            ->limit($perpage)
            ->get()->toArray();

        foreach ($dataList as $key => $value)
        {
            $dataList[$key]->timeformat = date('Y-m-d H:i:s',($value->transaction_time) - (8 * 3600) );
            $dataList[$key]->walletName = $value->walletname;
            $dataList[$key]->usedname = ($value->used) ? DictUtil::TokenUsed[$value->used] : '';
            $dataList[$key]->typename = ($value->type) ? DictUtil::TokenType[$value->type] : '';
            $dataList[$key]->tokensubjectname = ($value->tokensubject) ? $value->tokensubject : '';
            $dataList[$key]->realamount = $value->amount / pow(10,18);
            $dataList[$key]->actualgasprice = (($value->gasprice) / pow(10,18)) * $value->gasused;
        }

        return $this->output([
            'dataList' => $dataList,
            'options' => DictUtil::TokenUsed,
            'tokentype' => DictUtil::TokenType,
            'tokensubject' => DictUtil::TokenSubject,
        ]);
    }

    //交易详情
    public function getDetailByHash(Request $request)
    {
        //获取请求参数
        $params = $this->validation($request, [
            'tansaction_hash' => 'required',
        ]);

        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
    }

    //获取数量
    public function getFinanceCount (Request $request)
    {
        $fmodel = DB::table('finance')->select('*');
        $count = $fmodel -> count();
        return $this->output([
            'totalCount' => $count,
        ]);
    }

    //更改交易记录
    public function updateRecords (Request $request)
    {
        //获取请求的参数
        $params = $this->validation($request, [
            'transaction_hash' => 'required',
            'used' => 'required|numeric'
        ]);

        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $receive = $request -> all();
        $data['used'] = $params['used'];
        $data['type'] = $receive['type'];
        $data['tokensubject'] = $receive['tokensubject'];
        $data['memo'] = $receive['memo'];

        $data = DB::table('finance')->where('transaction_hash',$params['transaction_hash'])->update($data);

        if ($data !== false) {
            return $this->output();
        }
    }

    //导出财务 excel
    public function exportRecords(Request $request)
    {
        $excelfilename = '币财报交易记录';
        $data = DB::table('finance')->select('*')->limit(10)->get()->toArray();
        $result = array();
        foreach ($data as $key => $value)
        {
            foreach ($value as $k => $val)
            {
                $result[$key][] = $val;
            }
        }

        Excel::create($excelfilename, function ($excel) use($result) {
            $excel->sheet('test', function ($sheet) use($result) {
                $sheet->fromArray($result);
            });
        })->export('xls');
        return $this->output();
    }

    //配置钱包
    public function addWallets (Request $request)
    {
        //获取请求的参数
        $params = $this->validation($request, [
            'walletaddr' => 'required',
            'walletname' => 'required'
        ]);

        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $data['wname'] = $params['walletname'];
        $data['waddress'] = $params['walletaddr'];
        $walletparams = $request->all();

        if (array_key_exists('walletId', $walletparams) && $walletparams['walletId']) {
            $result = DB::table('configwalletaddr')->where('id',$request->all()['walletId'])->update($data);
            if ($result !== false) {
                return $this->output();
            }
        } else {
            $result = DB::table('configwalletaddr')->insert($data);
            if ($result)
            {
                return $this->output();
            }
        }

    }

    //钱包列表
    public function getWalletList (Request $request)
    {
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

        $cmodel = DB::table('configwalletaddr')->select('*');
        $dataList = $cmodel->orderBy('id', 'desc')->offset($offset)
            ->limit($perpage)
            ->get()->toArray();
        return $this->output([
            'dataList' => $dataList,
        ]);
    }

    public function updWallets(Request $request){
        $params = $this->validation($request, [
            'walletId' => 'required|numeric',
            'walletname' => 'required',
            'walletaddr' => 'required',
//            'photoUrl' => 'nullable|string',
//            'position' => 'required|string',
//            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $memberData = [
            'id' => $walletId,
            'wname' => $walletname,
            'waddress' => $walletaddr,
//            'intro' => $intro,
        ];

//        if ($photoUrl) {
//            $memberData['photo_url'] = $photoUrl;
//        }

        $result = DB::table('configwalletaddr')->where('id', '=', $walletId)->update($memberData);
        if ($result)
        {
            return $this->output();
        }

        return $this->output();
    }

    //删除钱包地址
    public function delWalletAddr(Request $request)
    {
        //获取请求参数
        $params = $this->validation($request ,[
            'walletId' => 'required|numeric',
        ]);
        if ($params === false)
        {
            return $this->error(100);
        }
        extract($params);

        $id = intval($params['walletId']);
        $result = DB::table('configwalletaddr')->where('id', '=', $id)->delete();
        if ($result)
        {
            return $this->output();
        }
    }
}