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
        '3' => '收到余币宝投资',
        '4' => '币种兑换',
        '5' => '员工工资',
        '6' => '团队激励',
        '7' => '市场推广费',
        '8' => '币种投资',
        '9' => '币种出售',
        '10' => '币种兑换',
        '11' => '退回投资款',
        '12' => '手续费',
        '13' => '账户互转'
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
        'BAI' => 'BAI',
        'INSUR' => 'INSUR',
        'LXT' => 'LXT',
        'ICST' => 'ICST',
        'PXC' => 'PXC'
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
