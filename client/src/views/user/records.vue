<template>
  <div class="container">
    <ol class="breadcrumb">
      <li><span>总资产</span></li>
      <li class="active">交易记录</li>
    </ol>

    <table class="table text-darker table-hover small" style="background: #fff;">
      <thead class="shadow-thead">
        <tr>
          <th>币种</th>
          <th>数量</th>
          <th>交易哈希</th>
          <th style="width:200px">交易时间</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in recordList" :key="item.id">
          <td>{{ item.symbol }}</td>
          <td>{{ item.amount }}</td>
          <td><a :href="'https://etherscan.io/tx/' + item.txHash" target="_blank">{{ item.txHash }}</a></td>
          <td>{{ item.txTime }}</td>
        </tr>
      </tbody>
    </table>
    <div class="text-right">
      <pagination :total="dataCount" :current-page="pageno" @onPageClick="onPageClick"></pagination>
    </div>
  </div>
</template>

<script>
import Pagination from '@/components/pagination'

export default {
  components: {
    Pagination
  },
  data () {
    return {
      perpage: 10,
      pageno: 1,
      dataCount: 0,
      recordList: []
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      this.$http.post('/api/getUserTransferRecord', {
        perpage: this.perpage,
        pageno: this.pageno
      }).then(res => {
        if (res.data.errcode === 0) {
          this.dataCount = res.data.data.dataCount
          this.recordList = res.data.data.dataList
        }
      })
    },
    onPageClick (pageno) {
      this.pageno = pageno
      this.updateData()
    }
  }
}
</script>

<style lang="scss" scoped>
  .table > thead > tr > th,
  .table > tbody > tr > td {
    padding: 10px;
    line-height: 2;
  }
</style>
