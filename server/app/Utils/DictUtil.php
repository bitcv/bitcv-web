<?php

namespace App\Utils;

class DictUtil 
{
    // 命名规范 TableName_FieldName

    const Token_Protocol = [
        '1' => 'ERC20',
        '2' => 'BTC',
        '3' => 'NEO',
        '4' => 'DOGE',
    ];

    const UserAsset_Status = [
        '1' => '可提取',
        '2' => '转账中',
        '3' => '已提取',
    ];

    //英文版
    const UserAsset_EnStatus = [
        '1' => 'Extractable',
        '2' => 'Transfer',
        '3' => 'Extracted',
    ];

    const UserTransferRecord_Status = [
        '1' => '进行中',
        '2' => '交易成功',
        '3' => '交易失败',
    ];

    //英文版
    const UserTransferRecord_EnStatus = [
        '1' => 'Processing',
        '2' => 'Successful transaction',
        '3' => 'Transaction failed',
    ];

    const DepositBox_Status = [
        '0' => '待付款',
        '1' => '进行中',
        '2' => '已暂停',
        '3' => '已结束',
        '4' => '已清算',
    ];

    const DepositOrder_Status = [
        '0' => '待确认',
        '1' => '已完成',
        '2' => '已取消',
        '3' => '已过期',
    ];

    const DepositFinance_Type = [
        '1' => '购买',
        '2' => '收益',
        '3' => '提取',
    ];
}
