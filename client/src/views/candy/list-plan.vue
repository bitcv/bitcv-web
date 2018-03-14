<template>
  <div v-loading="loading">
    <div class="nav">
      <router-link :to="{path: '/candyRoom/candyList'}">余币宝</router-link> >
      <router-link :to="'/'">已购余币宝计划</router-link>
    </div>
    <div class="panel panel-custom list-plan">
      <h3>已购余币宝计划</h3>
      <div class="table-responsive" v-if="list.length">
        <table class="table hidden-xs">
          <thead>
            <tr class="text-dark">
              <th>序号</th>
              <th>项目</th>
              <th>回报(枚)</th>
              <th>购买数量(枚)</th>
              <th>剩余期限/锁仓期</th>
              <th>购买时间</th>
              <th style="width:100px;">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in list" :key="item.id">
              <th>{{item.id}}</th>
              <td>
                <img :src="item.logoUrl" height="30" class="img-rounded">
                <span>{{ item.nameCn }}<span class="text-gray small">{{ item.tokenSymbol }}</span></span>
              </td>
              <td><span class="text-danger">{{ getInterest(10000, item.interestRate, item.lockTime) }}</span></td>
              <td>{{item.number}}</td>
              <td>{{item.remainderTime}}/<span class="text-gray">{{item.lockTime}}个月前</span></td>
              <td><span class="text-gray small">{{item.buyTime}}个月</span></td>
              <td>
                <button class="btn btn-warning">提取</button>
              </td>
            </tr>
          </tbody>
        </table>
        <!-- 小屏幕时显示的模拟table -->
        <div class="visible-xs xs-box">
          <div class="xs-header text-gray">
            <span>项目</span>
            <span>购买数量</span>
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
                  <button class="btn btn-warning">提取</button>
                </span>
              </div>
              <div class="xs-detail" v-if="item.isDetail">
                <p>
                  <span>序号: </span>
                  {{ item.id }}
                </p>
                <p>
                  <span>回报(枚): </span>
                  {{ getInterest(10000, item.interestRate, item.lockTime) }}
                </p>
                <p>
                  <span>剩余期限：</span>
                  {{ item.remainderTime }}
                </p>
                <p>
                  <span>锁仓期：</span>
                  {{ item.lockTime }}个月前
                </p>
                <p>
                  <span>购买时间：</span>
                  {{ item.buyTime }}个月
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="nodat">
        <img src="/static/img/nodata.png" alt="无数据">
        您未购买余币宝计划
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
      list: [
        // {
        //   id: 1,
        //   logoUrl: '/static/logo/bcv.png',
        //   nameCn: '币威',
        //   tokenSymbol: '(BCV)',
        //   interestRate: 200,
        //   lockTime: 3,
        //   number: 2999,
        //   remainderTime: '30天',
        //   buyTime: 8,
        //   isDetail: false
        // }
      ]
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
    ...mapActions(['getCandyList']),
    fetch () {
      // 请求数据
      // this.loading = true
      // this.getCandyList(this.params)
      //   .then(({dataCount = 0, dataList = []} = {}) => {
      //     this.total = dataCount
      //     this.list = dataList
      //     this.list.map((item) => {
      //       this.$set(item, 'isDetail', false)
      //     })
      //     this.loading = false
      //   })
      //   .catch(() => {
      //     this.loading = false
      //   })
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
  line-height: 500px;
  text-align: center;
  min-height: 500px;
  img{
    width: 147px;
    height: 96px;
  }
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
