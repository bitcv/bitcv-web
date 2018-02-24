<?php

namespace App\Utils;

class DictUtil 
{
    // 命名规范 TableName_FieldName

    const Token_Protocol = [
        '1' => 'PRC20',
        '2' => 'BTC',
        '3' => 'NEO',
        '4' => 'DOGE',
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
}
