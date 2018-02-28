<template>
  <div class="container">
    <div class="panel panel-custom">
      <div class="panel-body">
        <div class="col-xs-6 text-center">
          <div class="text-darker">
            <span>总资产 (CNY) ≈ </span>
            <span class="text-primary" style="font-size:48px">6987</span>
            <span class="text-primary">.57</span>
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
          <th>最新价</th>
          <th>数量</th>
          <th>状态</th>
          <th style="width:120px">操作</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0.126200 ≈ <span class="text-dark small">7982.61</span></td>
          <td>可提取</td>
          <td><button class="btn btn-text btn-sm" @click="onItemClick">立即提取</button></td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0.126200 ≈ <span class="text-dark small">7982.61</span></td>
          <td>可提取</td>
          <td><button class="btn btn-text btn-sm">立即提取</button></td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0.126200 ≈ <span class="text-dark small">7982.61</span></td>
          <td>可提取</td>
          <td><button class="btn btn-text btn-sm">立即提取</button></td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0.126200 ≈ <span class="text-dark small">7982.61</span></td>
          <td>可提取</td>
          <td><button class="btn btn-text btn-sm">立即提取</button></td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0.126200 ≈ <span class="text-dark small">7982.61</span></td>
          <td>可提取</td>
          <td><button class="btn btn-text btn-sm">立即提取</button></td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0.126200 ≈ <span class="text-dark small">7982.61</span></td>
          <td>可提取</td>
          <td><button class="btn btn-text btn-sm">立即提取</button></td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0.126200 ≈ <span class="text-dark small">7982.61</span></td>
          <td>可提取</td>
          <td><button class="btn btn-text btn-sm" disabled>立即提取</button></td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0.126200 ≈ <span class="text-dark small">7982.61</span></td>
          <td>可提取</td>
          <td><button class="btn btn-text btn-sm" disabled>立即提取</button></td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0.126200 ≈ <span class="text-dark small">7982.61</span></td>
          <td>可提取</td>
          <td>-</td>
        </tr>
        <tr>
          <td>BTC</td>
          <td>0.126200</td>
          <td>0.126200 ≈ <span class="text-dark small">7982.61</span></td>
          <td>可提取</td>
          <td>-</td>
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
    },
    onItemClick (item) {
      this.$router.push('/wallet/withdraw/1')
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
