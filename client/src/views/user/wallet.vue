<template>
  <div class="container">
    <div class="panel panel-custom">
      <div class="panel-body">
        <div class="col-xs-6 text-center">
          <div class="text-darker">
            <span>总资产 (CNY) ≈ </span>
            <span class="text-primary" style="font-size:48px">{{ totalAssetArr[0] }}</span>
            <span class="text-primary">.{{ totalAssetArr[1] }}</span>
          </div>
        </div>
        <div class="col-xs-6 text-right" style="padding-top:30px;">
          <router-link to="/wallet/records" class="text-primary">查看交易记录</router-link>
        </div>
      </div>
    </div>

    <table class="table text-darker table-hover small" style="background: #fff;">
      <thead class="shadow-thead">
        <tr>
          <th>币种</th>
          <th>参考价</th>
          <th>数量</th>
          <th>状态</th>
          <th style="width:120px">操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in dataList" :key="item.id">
          <td><img :src="item.logoUrl" class="img-circle" style="max-width: 40px;max-height: 40px;"/>&nbsp;&nbsp;{{ item.symbol }}</td>
          <td>{{ item.price }}</td>
          <td>{{ item.amount }} ≈ <span class="text-dark small">{{ parseInt(item.amount * item.price * 10000) / 10000 }}</span></td>
          <td v-if="protocolDict[item.symbol] === 'BCV'">{{ statusDict[item.status] }}</td>
          <td v-else>稍后提取</td>
          <!--<td >稍后提取</td>-->
          <td v-if="statusDict[item.status] === '可提取'"><button class="btn btn-text btn-sm" @click="toWithdraw(item)">立即提取</button></td>
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
import Pagination from '@/components/pagination/pagination'

export default {
  components: {
    Pagination
  },
  data () {
    return {
      total: 85,
      currentPage: 1,
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
    totalAssetArr () {
      var totalAsset = 0
      this.dataList.forEach(item => {
        totalAsset += item.amount * item.price
      })
      return totalAsset.toString().split('.')
    }
  },
  methods: {
    updateData () {
      this.$http.post('/api/getUserAsset', {
        perpage: 10,
        pageno: 1
      }).then(res => {
        if (res.data.errcode === 0) {
          this.dataCount = res.data.data.dataCount
          this.dataList = res.data.data.dataList
          this.protocolDict = res.data.data.protocolDict
          this.statusDict = res.data.data.statusDict
        }
      })
    },
    onPageClick (pageno) {
      this.pageno = pageno
    },
    toWithdraw (item) {
      if (item.walletAddr) {
        this.$http.post('/api/withdraw', {
          assetId: item.id
        }).then(res => {
          if (res.data.errcode === 0) {
            alert('提交成功')
            this.updateData()
          } else {
            alert(res.data.errmsg)
          }
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
