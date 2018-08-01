<template>
  <div v-loading="loading">
    <div class="nav">
      <router-link :to="{path: '/candyRoom/candyList'}">{{ $t('label.ybb') }}</router-link> >
      <router-link :to="'/'">{{ $t('label.puchase_ybb') }}</router-link>
    </div>
    <div class="panel panel-custom list-plan">
      <h3>{{ $t('label.puchase_ybb') }}</h3>
      <div class="table-responsive" v-if="list.length">
        <table class="table hidden-xs">
          <thead>
            <tr class="text-dark">
              <th>{{ $t('label.assest_num') }}</th>
              <th>{{ $t('label.candy_project') }}</th>
              <th>{{ $t('label.return') }}({{ $t('label.coin_amount') }})</th>
              <th>{{ $t('label.buy_q') }}({{ $t('label.coin_amount') }})</th>
              <th>{{ $t('label.sheng_qi') }}/{{ $t('label.lock') }}</th>
              <th>{{ $t('label.buy_time') }}</th>
              <th style="width:100px;">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in list" :key="index">
              <th>{{ index + 1 }}</th>
              <td>
                <img :src="item.logoUrl" height="30" class="img-rounded">
                <span>{{ item.nameCn }}<span class="text-gray small">{{ item.symbol }}</span></span>
              </td>
              <td><span class="text-danger">{{ getInterest(10000, item.interestRate, item.lockTime) }}</span></td>
              <td>{{item.amount}}</td>
              <td>{{item.remainTime}}{{ $t('label.day') }} / <span class="text-gray">{{item.lockTime}}{{ $t('label.day') }}</span></td>
              <td><span class="text-gray small">{{item.buyTime}}</span></td>
              <td>
                <span v-if="item.status === 1">{{ $t('label.waitting') }}</span>
                <button v-else-if="item.status === 2" class="btn btn-warning" @click="toWithdraw(item)">{{ $t('label.common_t') }}</button>
                <span v-else-if="item.status === 3">{{ $t('label.yi_tiqu') }}</span>
              </td>
            </tr>
          </tbody>
        </table>
        <!-- 小屏幕时显示的模拟table -->
        <div class="visible-xs xs-box">
          <div class="xs-header text-gray">
            <span>{{ $t('label.candy_project') }}</span>
            <span>{{ $t('label.buy_q') }}</span>
            <span>&nbsp;</span>
          </div>
          <div class="xs-body">
            <div class="xs-item" v-for="(item, index) in list" :key="item.id">
              <div class="xs-row" @click="handleShow(index)">
                <span>
                  <!-- <img :src="item.logoUrl" height="30" class="img-rounded"> -->
                  <b>{{ item.nameCn }}<i class="text-gray">{{ item.tokenSymbol }}</i></b>
                </span>
                <span>{{ item.number }}</span>
                <span>
                  <button class="btn btn-warning">{{ $t('label.common_t') }}</button>
                </span>
              </div>
              <div class="xs-detail" v-if="item.isDetail">
                <p>
                  <span>{{ $t('label.assest_num') }}: </span>
                  {{ item.id }}
                </p>
                <p>
                  <span>{{ $t('label.return') }}({{ $t('label.coin_amount') }}): </span>
                  {{ getInterest(10000, item.interestRate, item.lockTime) }}
                </p>
                <p>
                  <span>{{ $t('label.sheng_qi') }}：</span>
                  {{ item.remainderTime }}
                </p>
                <p>
                  <span>{{ $t('label.lock') }}：</span>
                  {{ item.lockTime }}个月前
                </p>
                <p>
                  <span>{{ $t('label.buy_time') }}：</span>
                  {{ item.buyTime }}个月
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="nodat">
        <!--<img src="/static/img/nodata.png" alt="无数据">-->
        {{ $t('label.no_ybb') }}
      </div>
    </div>
    <div class="text-right">
      <pagination :total="total" :current-page="currentPage" @onPageClick="onPageClick"></pagination>
    </div>
  </div>
</template>

<script>
import {mapActions} from 'vuex'
import Pagination from '@/components/pagination'

export default {
  components: {
    Pagination
  },
  data () {
    return {
      loading: false,
      total: 0,
      currentPage: 1,
      list: []
    }
  },
  computed: {
    params () {
      return {
        pageno: this.currentPage,
        perpage: 10
      }
    },
    language () {
      return this.$i18n.locale
    }
  },
  created () {
    this.fetch()
  },
  methods: {
    ...mapActions(['getUserDepositBoxList']),
    fetch () {
      // 请求数据
      this.loading = true
      this.getUserDepositBoxList(this.params)
        .then(({dataCount = 0, dataList = []} = {}) => {
          this.total = dataCount
          this.list = dataList
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
    handleShow (index) {
      console.log(index)
      this.list.map((item, i) => {
        if (i !== index) item.isDetail = false
      })
      this.list[index].isDetail = !this.list[index].isDetail
    },
    onPageClick (page) {
      this.currentPage = page
      this.fetch()
    },
    toWithdraw (item) {
      console.log(item)
      if (item.userAddr) {
        // this.$confirm('您的余币宝本金和利息将返还至' + item.userAddr + ', 请核对地址信息，由于地址信息错误造成的损失，本平台概不负责，确认提取余币宝?', '提示', {
        this.$confirm('网站暂停提取功能，下载币威钱包即刻提取，下载地址：https://bitcv.app', '提示', {
          confirmButtonText: '前往提取',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          window.open('https://www.bitcv.app/?from=bitcvCom')
        })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~@/styles/variables';
.nav{
  line-height: 50px;
  a:last-child{
    color: $primary-color;
  }
}
.panel-custom.list-plan{
  padding: 20px 30px;
  margin-bottom: 30px;
  h3{
    padding-left: 10px;
  }
}
.table > thead > tr > th {
  padding-top: 12px;
  padding-bottom: 12px;
  font-weight: normal;
}
.table > tbody > tr > td {
  vertical-align: middle;

  .text-danger {
    color: darken($primary-color, 20%);
  }
}
.xs-box{
  background: #fff;
  .xs-header, .xs-row{
    display: flex;
    padding: 0 15px;
    line-height: 50px;
    border-bottom: 1px solid #ccc;
    span{
      flex: 2;
      &:last-child{
        flex: 1;
      }
    }
  }
  .xs-header{
    border-bottom: 1px solid #ccc;
    line-height: 50px;
  }
  .xs-body{
    .xs-row{
      &:active{
        background: #eee;
      }
      b{
        font-weight: 500;
      }
      i{
        font-style: normal;
      }
    }
    .xs-detail{
      padding: 10px 20px;
      background: #f5f5f5;
      line-height: 30px;
      p{
        margin: 0;
        overflow: hidden;
      }
      .b-hidden{
        float: right;
        padding: 0 10px;
        color: #999;
      }
    }
  }
}
.nodat{
  width: 100%;
  line-height: 50px;
  text-align: center;
}
.panel-custom {
  margin-bottom: 0;

  & ~ .panel-custom,
  & + .panel-custom {
    margin-bottom: 15px;
    border-top: 1px solid $gray-light;
  }
}
@media (max-width: 767px) {
  .panel-custom.list-plan{
    padding: 10px 0;
  }
}
</style>
