<template>
    <div class="proj-list">
        <h1 class="page-title">项目列表</h1>
        <div class="content-container">
            <router-link to="/addProject">
              <el-button type="primary" icon="el-icon-plus">新建项目</el-button>
            </router-link>
            <el-table :data="tableData5" style="width: 100%">
                <el-table-column type="expand">
                    <template slot-scope="props">
                        <el-form label-position="left" inline class="demo-table-expand">
                            <el-form-item label="订单ID">
                                <span>{{ props.row.id }}</span>
                            </el-form-item>
                            <el-form-item label="购买代币">
                                <span>{{ props.row.buySymbol }}</span>
                            </el-form-item>
                            <el-form-item label="支付代币">
                                <span>{{ props.row.paySymbol }}</span>
                            </el-form-item>
                            <el-form-item label="购买数量">
                                <span>{{ props.row.buyAmount }}</span>
                            </el-form-item>
                            <el-form-item label="支付数量">
                                <span>{{ props.row.payAmount }}</span>
                            </el-form-item>
                            <el-form-item label="额外奖励">
                                <span>{{ props.row.bonus }}</span>
                            </el-form-item>
                            <el-form-item label="交易汇率">
                                <span>{{ props.row.exchangeRate }}</span>
                            </el-form-item>
                            <el-form-item label="购买总数">
                                <span>{{ props.row.totalBuyAmount }}</span>
                            </el-form-item>
                            <el-form-item label="创建时间">
                                <span>{{ props.row.timestamp }}</span>
                            </el-form-item>
                            <el-form-item label="交易时间">
                                <span>{{ props.row.timestamp }}</span>
                            </el-form-item>
                        </el-form>
                    </template>
                </el-table-column>
                <el-table-column sortable label="项目ID" prop="id">
                </el-table-column>
                <el-table-column label="项目logo" prop="totalBuyAmount">
                </el-table-column>
                <el-table-column label="项目名称" prop="payAmount">
                </el-table-column>
                <el-table-column label="货币名称" prop="payAmount">
                </el-table-column>
                <el-table-column label="货币符号" prop="paySymbol">
                </el-table-column>
                <el-table-column label="操作">
                  <template slot-scope="scope">
                    <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                          <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                  </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            tableData5: [{
                id: '2132',
                paySymbol: 'BTC',
                payAmount: 25325.3453,
                exchangeRate: 3.24567,
                buySymbol: 'DASH',
                buyAmount: 23.456,
                bonus: 1.3577,
                totalBuyAmount: 24.856,
                timestamp: '2017-01-23',
            }, {
                id: '2132',
                paySymbol: 'BTC',
                payAmount: 25325.3453,
                exchangeRate: 3.24567,
                buySymbol: 'DASH',
                buyAmount: 23.456,
                bonus: 1.3577,
                totalBuyAmount: 24.856,
                timestamp: '2017-01-23',
            }, {
                id: '2132',
                paySymbol: 'BTC',
                payAmount: 25325.3453,
                exchangeRate: 3.24567,
                buySymbol: 'DASH',
                buyAmount: 23.456,
                bonus: 1.3577,
                totalBuyAmount: 24.856,
                timestamp: '2017-01-23',
            }, {
                id: '2132',
                paySymbol: 'BTC',
                payAmount: 25325.3453,
                exchangeRate: 3.24567,
                buySymbol: 'DASH',
                buyAmount: 23.456,
                bonus: 1.3577,
                totalBuyAmount: 24.856,
                timestamp: '2017-01-23',
            }, {
                id: '2132',
                paySymbol: 'BTC',
                payAmount: 25325.3453,
                exchangeRate: 3.24567,
                buySymbol: 'DASH',
                buyAmount: 23.456,
                bonus: 1.3577,
                totalBuyAmount: 24.856,
                timestamp: '2017-01-23',
            }]
        }
    }
}
</script>

<style lang="scss" scoped>
.record {
    background: rgba(236, 236, 236, 0.4);
    padding: 30px 40px;
}
</style>
<style>
.demo-table-expand {
    font-size: 0;
}
.demo-table-expand label {
    width: 90px;
    color: #99a9bf;
    text-align: right !important;
}
.demo-table-expand .el-form-item {
    margin-right: 0;
    margin-bottom: 0;
    width: 50%;
}
</style>
