<template>
  <div class="record-detail" v-loading="tableLoad">
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/acting/record' }">{{ $t('label.fa_record') }}</el-breadcrumb-item>
      <el-breadcrumb-item>{{ $t('label.fa_detail') }}</el-breadcrumb-item>
    </el-breadcrumb>
    <el-table :data="dataList" style="width: 100%">
      <el-table-column type="index" :label="$t('label.assest_num')">
      </el-table-column>
      <el-table-column prop="toAddr" :label="$t('label.addre')">
        <template slot-scope="scope">
          <a :href="'https://etherscan.io/token/' + contractAddr + '?a=' + scope.row.toAddr" target="_blank">
            {{maskStr(scope.row.toAddr, 5)}}
          </a>
        </template>
      </el-table-column>
      <el-table-column prop="amount" :label="$t('label.coin_num')"></el-table-column>
      <el-table-column prop="txHash" :label="$t('label.hash')">
        <template slot-scope="scope">
          <a :href="'https://etherscan.io/tx/' + scope.row.txHash" target="_blank">
            {{maskStr(scope.row.txHash, 5)}}
          </a>
        </template>
      </el-table-column>
      <el-table-column prop="txTime" :label="$t('label.r_time')" width="170"></el-table-column>
      <el-table-column prop="status" :label="$t('label.coin_status')">
        <template slot-scope="scope">
          <span v-if="language === 'cn'" :class="scope.row.status === 4 ? 'text-success' : ''">
            {{['', '待发送', '待发送', '发送中', '已完成'][scope.row.status]}}
          </span>
          <span v-else-if="language === 'en'" :class="scope.row.status === 4 ? 'text-success' : ''">
            {{['', 'To be sen', 'To be sen', 'Transfering', 'Completed'][scope.row.status]}}
          </span>
          <span v-else :class="scope.row.status === 4 ? 'text-success' : ''">
            {{['', '送信する', '送信する', '転送', '完了'][scope.row.status]}}
          </span>
        </template>
      </el-table-column>
    </el-table>

    <footer class="footer">
      <el-row>
        <el-col :span="6">
          <h5>{{ $t('label.zong_amount') }}</h5>
          <div>{{totalAmount}}<small>{{ $t('label.coin_amount') }}</small></div>
        </el-col>
        <el-col :span="6">
          <h5>{{ $t('label.zong_addr') }}</h5>
          <div>{{dataCount}}<small>{{ $t('label.tiao') }}</small></div>
        </el-col>
        <el-col :span="8" class="text-warning">
          <h5> </h5>
          <a :href="'/api/getDispenseReport?taskId=' + taskId">{{ $t('label.down_report') }}</a>
        </el-col>
      </el-row>
    </footer>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex'
import {maskStr} from '@/utils/utils'
export default {
  data () {
    return {
      dataList: [],
      dataCount: 0,
      process: 0,
      totalAmount: 0,
      contractAddr: '',
      tokenSymbol: '',
      tableLoad: false,
      total: {
        number: 200,
        address: 100,
        minute: 10
      }
    }
  },
  computed: {
    ...mapState({
      taskId: state => state.route.params.taskId
    }),
    language() {
      return this.$i18n.locale;
    }
  },
  mounted () {
    this.fetch()
    if (!this.timerId) {
      this.timerId = setInterval(() => {
        this.fetch()
      }, 30000)
    }
  },
  beforeDestroy () {
    if (this.timerId) {
      clearInterval(this.timerId)
    }
  },
  methods: {
    ...mapActions(['getDispenseList']),
    fetch () {
      this.tableLoad = true
      this.getDispenseList({
        taskId: this.taskId
      }).then((data = {}) => {
        this.tableLoad = false
        this.dataList = data.dataList
        this.dataCount = data.dataCount
        this.process = data.process
        this.totalAmount = data.totalAmount
        this.contractAddr = data.contractAddr
        this.tokenSymbol = data.tokenSymbol
      }).catch(() => {
        this.tableLoad = false
      })
    },
    maskStr (str, number) {
      return maskStr(str, number)
    }
  }
}
</script>

<style lang="scss">
.record-detail{
  padding: 80px 0;
  .el-breadcrumb::after, .el-breadcrumb::before{
    display: none;
  }
  .el-table{
    margin-top: 30px;
  }
  .footer{
    background: #fdf6ec;
    margin-top: 30px;
    padding: 20px;
    .el-col-6{
      text-align: center;
      h5{
        color: #999;
      }
      div{
        font-size: 18px;
        color: orange;
        small{
          font-size: 12px;
          color: #ccc;
        }
      }
    }
    .text-warning{
      color: orange;
      text-align: center;
    }
  }
}
</style>
