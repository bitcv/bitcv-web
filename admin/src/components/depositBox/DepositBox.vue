<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <el-tab-pane label="余币宝列表">
        <el-table :data="depositBoxList">
          <el-table-column label="ID" prop="id"></el-table-column>
          <el-table-column label="项目ID" prop="projId"></el-table-column>
          <el-table-column label="总额度" prop="totalAmount"></el-table-column>
          <el-table-column label="起购额度" prop="minAmount"></el-table-column>
          <el-table-column label="剩余额度" prop="remainAmount"></el-table-column>
          <el-table-column label="年化回报">
            <template slot-scope="scope">{{ scope.row.interestRate * 100 }}%</template>
          </el-table-column>
          <el-table-column label="接收地址">
            <template slot-scope="scope">
              <a class="link" :href="'https://etherscan.io/token/' + scope.row.contractAddr + '?a=' + scope.row.toAddr" target="_blank">{{ getShortStr(scope.row.toAddr, 6) }}</a>
            </template>
          </el-table-column>
          <el-table-column label="状态" prop="status"></el-table-column>
          <!--<el-table-column label="操作">-->
            <!--<template slot-scope="scope">-->
              <!--<el-button size="mini" type="primary" @click="">详情</el-button>-->
              <!--<div v-if="scope.row.status===0">-->
                <!--<el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>-->
              <!--</div>-->
            <!--</template>-->
          <!--</el-table-column>-->
        </el-table>
      </el-tab-pane>
      <el-tab-pane label="订单列表">
        <el-table :data="depositOrderList">
          <el-table-column label="余币宝ID" prop="depositBoxId"></el-table-column>
          <el-table-column label="用户手机号" prop="mobile"></el-table-column>
          <el-table-column label="订单金额" prop="orderAmount"></el-table-column>
          <el-table-column label="支付地址">
            <template slot-scope="scope">
              <a class="link" :href="'https://etherscan.io/token/' + scope.row.contractAddr + '?a=' + scope.row.fromAddr" target="_blank">{{ getShortStr(scope.row.fromAddr, 6) }}</a>
            </template>
          </el-table-column>
          <el-table-column label="接收地址">
            <template slot-scope="scope">
              <a class="link" :href="'https://etherscan.io/token/' + scope.row.contractAddr + '?a=' + scope.row.toAddr" target="_blank">{{ getShortStr(scope.row.toAddr, 6) }}</a>
            </template>
          </el-table-column>
          <el-table-column label="合约地址">
            <template slot-scope="scope">
              <a class="link" :href="'https://etherscan.io/address/' + scope.row.contractAddr" target="_blank">{{ getShortStr(scope.row.contractAddr, 6) }}</a>
            </template>
          </el-table-column>
          <el-table-column label="状态" prop="status"></el-table-column>
        </el-table>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
export default {
  data () {
    return {
      depositBoxList: [],
      depositOrderList: []
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      this.$http.post('/api/getAdminDepositBoxList').then((res) => {
        if (res.data.errcode === 0) {
          this.depositBoxList = res.data.data.dataList
        }
      })
      this.$http.post('/api/getAdminDepositOrderList').then((res) => {
        if (res.data.errcode === 0) {
          this.depositOrderList = res.data.data.dataList
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.link {
  &:hover {
    color: #FFCF81;
  }
}
</style>
