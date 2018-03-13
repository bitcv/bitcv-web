<template>
  <div class="candy-order" v-loading="loading">
    <el-table :data="list" style="width: 100%;">
      <el-table-column prop="depositBoxId" label="余币宝ID">
      </el-table-column>
      <el-table-column prop="mobile" label="用户手机号">
      </el-table-column>
      <el-table-column prop="orderAmount" label="订单金额">
      </el-table-column>
      <el-table-column prop="fromAddr" label="支付地址">
        <template slot-scope="scope">
          <a class="link" :href="'https://etherscan.io/token/' + scope.row.contractAddr + '?a=' + scope.row.fromAddr" target="_blank">{{ maskStr(scope.row.fromAddr, 4) }}</a>
        </template>
      </el-table-column>
      <el-table-column prop="toAddr" label="接受地址">
        <template slot-scope="scope">
          <a class="link" :href="'https://etherscan.io/token/' + scope.row.contractAddr + '?a=' + scope.row.toAddr" target="_blank">{{ maskStr(scope.row.toAddr, 4) }}</a>
        </template>
      </el-table-column>
      <el-table-column prop="detail" label="合约地址">
        <template slot-scope="scope">
          <a class="link" :href="'https://etherscan.io/address/' + scope.row.contractAddr" target="_blank">{{ maskStr(scope.row.contractAddr, 4) }}</a>
        </template>
      </el-table-column>
      <el-table-column prop="status" label="状态">
        <template slot-scope="scope">
          {{['未支付', '已完成'][scope.row.status]}}
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex'
import {maskStr} from '@/utils/utils'
export default {
  data () {
    return {
      loading: false,
      list: []
    }
  },
  computed: {
    ...mapState({
      userInfo: state => state.userInfo
    })
  },
  created () {
    this.fetch()
  },
  methods: {
    ...mapActions(['getProjDepositOrderList']),
    fetch () {
      this.loading = true
      this.getProjDepositOrderList({projId: this.userInfo.userId})
        .then(({dataList = []} = {}) => {
          this.list = dataList
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
    maskStr (str, number) {
      return maskStr(str, number)
    }
  }
}
</script>

<style lang="scss">
  @import '~@/styles/variables.scss';
  .candy-order{
    .link{
      color: #606266;
      &:hover, &:focus{
        color: $main-color;
      }
    }
  }
</style>
