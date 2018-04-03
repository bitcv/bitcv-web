<?php

namespace App\Utils;

use App\Utils\BaseUtil;
use App\Models as Model;
use Redis;


class EtherscanUtil
{
    const API_KEY = '3IV46D5P2XE6PA7R9KCMJEIW1YR5VEECIU';

    public static function getDepositList ($tokenId, $toAddr, $endTime = null) {
        $toAddr = strtolower($toAddr);
        // 获取通证信息
        $tokenObj = Model\Token::find($tokenId);
        if ($tokenObj == null || $tokenObj->protocol != 1) {
            return false;
        }
        $symbol = $tokenObj->symbol;
        $contractAddr = $tokenObj->contract_addr;
        if ($symbol === 'ETH') {
            // 以太坊直接查询
            $url = "http://api.etherscan.io/api?module=account&action=txlist&address=$toAddr&startblock=0&endblock=99999999&sort=desc&apikey=" . self::API_KEY;
            echo "\nethStartTime:".microtime()."\n";
            $resJson = @file_get_contents($url);
            echo "\nethEndTime:".microtime()."\n";
            $resArr = json_decode($resJson, true);
            if ($resArr['status'] != '1') {
                return false;
            }
            $txList = [];
            foreach ($resArr['result'] as $tx) {
                $decimal = 18;
                $txAmount = BaseUtil::bigDiv($tx['value'], $decimal);
                // 忽略0.001的转账记录
                if ($tx['input'] !== '0x' || strtolower($tx['to']) !== $toAddr || $txAmount < 0.001) {
                    continue;
                }
                $txTimestamp = (int)$tx['timeStamp'] + 8 * 3600; // 矫正UTC时间
                $txTime = date('Y-m-d H:i:s', $txTimestamp);
                if ($txTime <= $endTime) break;
                $txHash = $tx['hash'];
                $fromAddr = $tx['from'];
                $txList[] = [
                    'fromAddr' => $fromAddr,
                    'toAddr' => $toAddr,
                    'txHash' => $txHash,
                    'txTime' => $txTime,
                    'txAmount' => $txAmount,
                ];
            }
        } else {
            // ERC20查询合约
            $url = "http://api.etherscan.io/api?module=account&action=txlist&address=$contractAddr&startblock=5366821&endblock=5371821&sort=desc&apikey=" . self::API_KEY;
            echo "$url\n";
            echo "\nerc20StartTime:".microtime()."\n";
            $resJson = @file_get_contents($url);
            echo "\nerc20EndTime:".microtime()."\n";
            echo $resJson;exit;
            $resArr = json_decode($resJson, true);
            if ($resArr['status'] != '1') {
                return false;
            }
            $txList = [];
            foreach ($resArr['result'] as $tx) {
                $txTimestamp = (int)$tx['timeStamp'] + 8 * 3600; // 矫正UTC时间
                $txTime = date('Y-m-d H:i:s', $txTimestamp);
                if ($txTime <= $endTime) break;
                $decimal = $tokenObj->decimal;
                $txToAddr = '0x' . substr($tx['input'], 34, 40);
                if (strtolower($txToAddr) !== $toAddr) {
                    continue;
                }
                $txAmount = BaseUtil::bigDiv(hexdec(preg_replace('/^0+/', '', substr($tx['input'], 74))), $decimal);
                $txHash = $tx['hash'];
                $fromAddr = $tx['from'];
                $txList[] = [
                    'fromAddr' => $fromAddr,
                    'toAddr' => $toAddr,
                    'txHash' => $txHash,
                    'txTime' => $txTime,
                    'txAmount' => $txAmount,
                ];
            }
        }
        return $txList;
    }

    private static function getBlockNumber () {
        $blockNumber = Redis::get('block_number');
        if (!$blockNumber) {
            $resJson = file_get_contents(evn('TX_API_URL') . '/api/getBlockNumber');
            $resArr = json_decode($resJson, true);
            if ($resArr && $resArr['errcode'] == 0) {
                Redis::set('block_number', $resArr['data']['blockNumber']);
                Redis::expire('block_number', 3600);
            }
        }

        return $blockNumber;
    }

    public static function getTxList ($addr, $endTime = null) {
        $url = "http://api.etherscan.io/api?module=account&action=txlist&address=$addr&startblock=0&endblock=99999999&sort=desc&apikey=" . self::API_KEY;
        $resJson = file_get_contents($url);
        $resArr = json_decode($resJson, true);
        if ($resArr['status'] != '1') {
            return false;
        }
        $txList = [];
        foreach ($resArr['result'] as $tx) {
            $txTimestamp = (int)$tx['timeStamp'] + 8 * 3600; // 矫正UTC时间
            $txTime = date('Y-m-d H:i:s', $txTimestamp);
            if ($txTime <= $endTime) break;
            if ($tx['input'] === '0x') {
                // 以太坊交易记录
                $type = 'ETH';
                $contractAddr = '';
                $decimal = 18;
                $toAddr = $tx['to'];
                $txAmount = BaseUtil::bigDiv($tx['value'], $decimal);
            } else {
                // ERC20交易记录
                $type = 'ERC20';
                $contractAddr = strtolower($tx['to']);
                // 根据合约地址获取decimal
                $tokenObj = Model\Token::where('contract_addr', $contractAddr)->first();
                if ($tokenObj == null) continue; // 忽略不在token表内的交易记录
                $decimal = $tokenObj->decimal;
                $toAddr = '0x' . substr($tx['input'], 34, 40);
                $txAmount = BaseUtil::bigDiv(hexdec(preg_replace('/^0+/', '', substr($tx['input'], 74))), $decimal);
            };
            $gasUsed = $tx['gasUsed'];
            $gasPrice = $tx['gasPrice'];
            $gasAmount = $gasUsed * $gasPrice / pow(10, 18);
            // 根据交易记录获取交易信息
            $txHash = $tx['hash'];
            $fromAddr = $tx['from'];
            $txList[] = [
                'type' => $type,
                'contractAddr' => $contractAddr,
                'fromAddr' => $fromAddr,
                'toAddr' => $toAddr,
                'txHash' => $txHash,
                'txTime' => $txTime,
                'txAmount' => $txAmount,
                'gasAmount' => $gasAmount,
            ];
        }
        return $txList;
    }
}

