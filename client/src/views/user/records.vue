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
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0xce9eeb...fb03f29</td>
          <td>2018-02-23 19:08:49</td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0xce9eeb...fb03f29</td>
          <td>2018-02-23 19:08:49</td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0xce9eeb...fb03f29</td>
          <td>2018-02-23 19:08:49</td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0xce9eeb...fb03f29</td>
          <td>2018-02-23 19:08:49</td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0xce9eeb...fb03f29</td>
          <td>2018-02-23 19:08:49</td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0xce9eeb...fb03f29</td>
          <td>2018-02-23 19:08:49</td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0xce9eeb...fb03f29</td>
          <td>2018-02-23 19:08:49</td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0xce9eeb...fb03f29</td>
          <td>2018-02-23 19:08:49</td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0xce9eeb...fb03f29</td>
          <td>2018-02-23 19:08:49</td>
        </tr>
      </tbody>
    </table>
    <div class="text-right">
      <pagination :total="total" :current-page="currentPage" @onPageClick="onPageClick"></pagination>
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
      currentPage: 1
    }
  },
  methods: {
    onPageClick (page) {
      this.currentPage = page
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
