<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\Cookie;

class FileController extends Controller
{
    public function uploadFile (Request $request) {

        $path = null;
        if ($request->file('logo') && $request->file('logo')->isValid()) {
            $path = $request->file('logo')->store('image/logo', 'public');
        } else if ($request->file('avatar') && $request->file('avatar')->isValid()) {
            $path = $request->file('avatar')->store('image/avatar', 'public');
        } else if ($request->file('picture') && $request->file('picture')->isValid()) {
            $path = $request->file('picture')->store('image/picture', 'public');
        } else if ($request->file('whitePaper') && $request->file('whitePaper')->isValid()) {
            $path = $request->file('whitePaper')->store('pdf/whitePaper', 'public');
        }
        if ($path === null) {
            return $this->error(100);
        }
        $path = '/storage/' . $path;

        return $this->output(['url' => $path]);
    }

    public function parseAddrFile (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'isTest' => 'required|numeric',
            'tokenId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        if ($request->file('addr') && $request->file('addr')->isValid()) {
            $path = $request->file('addr')->store('excel/addr', 'public');
        } else {
            return $this->error(100);
        }
        $path = '/' . $path;
        $path = __DIR__ . '/../../../storage/app/public/' . $path;
        //$path = "/Users/JunK/ucai/lianbi/server/app/Http/Controllers/../../../storage/app/public/excel/addr/3vDA0gExfAILC2NhlyoqRTEtsnnAA38Oh96LrtKz.xls";
        $dataList = [];
        \Excel::selectSheetsByIndex(0)->load($path, function($reader) use (&$dataList) {
            $dataList = $reader->select(['address', 'amount'])->get()->toArray();
        });
        $totalAmount = 0;
        $wrongCount = 0;
        $addrList = [];
        foreach ($dataList as $index => $data) {
            if ($isTest == 1 && $index == 2) break; // 测试状态下只发送前两条
            $status = 0;
            if (!preg_match('/^0x[0-9a-fA-F]{40}$/i', $data['address'])) {
                $status = 1;
            }
            if ($data['amount'] <= 0) {
                $status = $status === 1 ? 3 : 2;
            } else {
                $totalAmount += $data['amount'] * pow(10, 8);
            }
            $addrList[] = [
                'address' => trim(strtolower($data['address'])),
                'amount' => floatval($data['amount']),
                'status' => $status,
            ];
            $wrongCount = $status === 0 ? $wrongCount : $wrongCount + 1;
        }
        session_start();
        $dispenseData = [
            'tokenId' => $tokenId,
            'dispenseList' => $addrList,
        ];
        $_SESSION['dispenseData'] = $dispenseData;

        // 获取非重复的地址数
        $addressList = array_column($addrList, 'address');
        $uniqueCount = count(array_unique($addressList));

        return $this->output([
            'dataList' => $addrList,
            'dataCount' => count($dataList),
            'uniqueCount' => $uniqueCount,
            'totalAmount' => $totalAmount / pow(10, 8),
            'wrongCount' => $wrongCount,
        ]);
    }
}
