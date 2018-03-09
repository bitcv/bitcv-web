<template>
  <div class="container">
    <div class="panel panel-custom">
      <div class="panel-body">
        <div class="col-xs-6 text-center">
          <div class="text-darker">
            <span>{{ $t('label.sum_money') }} (CNY) ≈ </span>
            <span class="text-primary" style="font-size:48px">{{ totalAssetArr[0] }}</span>
            <span class="text-primary">.{{ totalAssetArr[1] || '00' }}</span>
          </div>
        </div>
        <div class="col-xs-6 text-right" style="padding-top:30px;">
          <router-link to="/wallet/records" class="text-primary">{{ $t('label.view_r') }}</router-link>
        </div>
      </div>
    </div>

    <table class="table text-darker table-hover small" style="background: #fff;">
      <thead class="shadow-thead">
        <tr>
          <th>{{ $t('label.coin_type') }}</th>
          <th>{{ $t('label.price') }}</th>
          <th>{{ $t('label.coin_num') }}</th>
          <th>{{ $t('label.coin_status') }}</th>
          <th style="width:120px">{{ $t('label.operation') }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in dataList" :key="item.id">
          <td><img :src="item.logoUrl" class="img-circle" style="max-width: 40px;max-height: 40px;"/>&nbsp;&nbsp;{{ item.symbol }}</td>
          <td>{{ item.price || '以交易所价格为准' }}</td>
          <td>{{ item.amount }} ≈ <span class="text-dark small">{{ item.price ? parseInt(item.amount * item.price * 10000) / 10000 : '-' }}</span></td>
          <td v-if="checkAuth(item.symbol)">{{ statusDict[item.status] }}</td>
          <td v-else>{{ $t('label.later_t') }}</td>
          <td v-if="checkAuth(item.symbol) && statusDict[item.status] === '可提取'"><button class="btn btn-text btn-sm" @click="toWithdraw(item)">{{ $t('label.hurry_t') }}</button></td>
          <td v-else>-</td>
        </tr>
      </tbody>
    </table>
    <div class="text-right">
      <pagination :total="dataCount" :current-page="pageno" @onPageClick="onPageClick"></pagination>
    </div>
  </div>
</template>

<script>
import {mapState} from 'vuex'
import Pagination from '@/components/pagination'

export default {
  components: {
    Pagination
  },
  data () {
    return {
      total: 85,
      perpage: 10,
      pageno: 1,
      dataCount: 0,
      dataList: [],
      protocolDict: {},
      statusDict: {}
    }
  },
  mounted () {
    this.updateData()
  },
  computed: {
    ...mapState({
      code: state => state.route.query.code
    }),
    totalAssetArr () {
      return this.dataList.reduce((prev, curr) => curr.amount * curr.price + prev, 0).toString().split('.')
    }
  },
  methods: {
    updateData () {
      this.$http.post('/api/getUserAsset', {
        perpage: this.perpage,
        pageno: this.pageno
      }).then(res => {
        if (res.data.errcode === 0) {
          this.dataCount = res.data.data.dataCount
          this.dataList = res.data.data.dataList
          this.protocolDict = res.data.data.protocolDict
          this.statusDict = res.data.data.statusDict
        }
      })
    },
    checkAuth (tokenSymbol) {
      // 管理员开通
      if (tokenSymbol === 'BCV' || tokenSymbol === 'EOS' || tokenSymbol === 'PXC' || tokenSymbol === 'ICST') {
        return true
      } else {
        return false
      }
    },
    onPageClick (pageno) {
      this.pageno = pageno
      this.updateData()
    },
    toWithdraw (item) {
      if (item.walletAddr) {
        this.$confirm('您的收币地址为' + item.walletAddr + ', 确认提币?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.$http.post('/api/withdraw', {
            assetId: item.id
          }).then(res => {
            if (res.data.errcode === 0) {
              this.$message({
                type: 'success',
                message: '提交成功!'
              })
              this.updateData()
            } else {
              alert(res.data.errmsg)
            }
          })
        }).catch(() => {
          this.$message({
            type: 'info',
            message: '已取消'
          })
        })
      } else {
        this.$router.push('/wallet/withdraw/' + item.id)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~@/styles/variables';

.table {
  > thead > tr > th,
  > tbody > tr > td {
    padding: 10px;
    line-height: 2;
  }

  .btn-text {
    &, &:hover {
      background-color: transparent;
    }
    &:not(:disabled) {
      color: $primary-color;
    }
  }
}
</style>
