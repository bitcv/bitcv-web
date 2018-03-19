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

    const UserTransferRecord_Status = [
        '1' => '进行中',
        '2' => '交易成功',
        '3' => '交易失败',
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
    //钱包地址
    const WalletAddress = [
        '0' => '0xaed0363f76e4b906ef818b0f3199c580b5b01a43',
        '1' => '0x9eD38CAfc071f12f2a73c311f9F5c6D153A1A131'
    ];

    //钱包地址用途名称
    const WalletAddressName = [
        '0' => '奖金福利',
        '1' => '基金投资'
    ];

    //费用性质
    const TokenUsed = [
        '1' => '收到投资款',
        '2' => '收回投资币种',
        '3' => '币种兑换',
        '4' => '员工工资',
        '5' => '团队激励',
        '6' => '市场推广费',
        '7' => '币种投资',
        '8' => '币种出售',
        '9' => '币种投资',
        '10' => '退回投资款',
    ];

    // 代币符号
    const CoinType = [
        'BTC' => 'BTC',
        'BCV' => 'BCV',
        'ETH' => 'ETH',
        'LEND' => 'LEND',
        'CPU' => 'CPU',
        'TAC' => 'TAC',
        'EOS' => 'EOS',
        'ERR' => 'ERR',
    ];

    //类型
    const TokenType = [
        '1' => '收入',
        '2' => '支出',
        '3' => '账户互转',
    ];

    //项目主体
    const TokenSubject = [
        '1' => '员工',
        '2' => '投资人',
        '3' => 'A 类',
    ];


}
