<template>
  <div v-loading="loading">
    <div class="nav">
      <router-link :to="{path: '/candyRoom/candyList'}">余币宝</router-link> >
      <router-link :to="'/'">资产明细</router-link>
    </div>
    <div class="panel panel-custom list-plan">
      <h3>资产明细</h3>
      <div class="list-box" v-if="list.length">
        <ul>
          <li v-for="item in list" :key="item.id">
            <span class="left">
              <img :src="item.logoUrl" alt="logo">
              <i>
                <b>{{typeDict[item.type]}}</b><br>
                <small>{{item.createdAt}}</small>
              </i>
            </span>
            <span class="right">
              <b>{{item.amount > 0 ? '+' : ''}}{{item.amount|currency}}</b>  <small>{{item.tokenSymbol}}</small><br>
              <small>≈ ${{(item.amount * item.price)|currency}}</small>
            </span>
          </li>
        </ul>
      </div>
      <div v-else class="nodat">
        <!--<img src="/static/img/nodata.png" alt="无数据">-->
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
      list: [],
      typeDict: {}
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
    ...mapActions(['getUserDepositFinanceList']),
    fetch () {
      // 请求数据
      this.loading = true
      this.getUserDepositFinanceList(this.params)
        .then(({dataCount = 0, dataList = [], typeDict = {}} = {}) => {
          this.total = dataCount
          this.list = dataList
          this.typeDict = typeDict
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
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
  .list-box{
    border-top: 2px solid #ccc;
    ul{
      li{
        padding: 20px 30px;
        padding-right: 0;
        border-bottom: 1px solid #EAEAEA;
        overflow: hidden;
        small{
          color: #9B9B9B
        }
        i{
          font-style: normal;
        }
      }
      .left{
        float: left;
        img{
          width: 40px;
          height: 40px;
          float: left;
        }
        i{
          float: left;
          margin-left: 10px;
        }
      }
      .right{
        float: right;
        text-align: center;
        b{
          font-size: 20px;
        }
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
