<template>
  <div>
    <div class="assets-panel">
      <div class="assets-box">
        <div class="assets-nav">
          <router-link :to="{path: '/candyRoom/candyMyData'}">我的余币宝订单 >></router-link>
        </div>
        <div class="assets-title">
          <router-link :to="{path: '/candyRoom/candyListAssets'}">
            <!--<img src="/static/logo/github-hover.png" alt="logo">-->
            我的资产 >
          </router-link>
        </div>
        <div class="assets-total">
          ≈ ${{asset.totalAsset.toFixed(2)}}
          <router-link :to="{path: '/candyRoom/candyListPlan'}">提取 ></router-link>
        </div>
        <div class="assets-profit">
          <el-row>
            <el-col :span="11" class="left">
              <h6>昨日收益</h6>
              <p>≈ ${{asset.lastDayProfit.toFixed(2)}}</p>
            </el-col>
            <el-col :span="2" class="line">
              <div></div>
            </el-col>
            <el-col :span="11" class="right">
              <h6>
                <router-link :to="{path: '/candyRoom/candyListProfit'}">累计收益 ></router-link>
              </h6>
              <p>≈ ${{asset.totalProfit.toFixed(2)}}</p>
            </el-col>
          </el-row>
        </div>
      </div>
    </div>
    <div class="panel panel-custom">
      <div class="panel-body filter-list">
        <div><h3 style="margin:20px 0 30px; padding: 20px 0; border-bottom: 2px solid #ccc;">{{ $t('label.ybb_plan') }}</h3></div>
        <dl class="dl-horizontal">
          <dt>{{ $t('label.lock') }}</dt>
          <dd  v-if="language === 'cn'">
          <a href="javascript:;"
             v-for="item in lockTime.items"
             :key="item.value"
             :class="{active: lockTime.value == item.value}"
             @click="onFilterClick(item.value)"
             >{{ item.label }}</a>
          </dd>
          <dd v-else>
          <a href="javascript:;"
             v-for="item in lockTime.e_items"
             :key="item.value"
             :class="{active: lockTime.value == item.value}"
             @click="onFilterClick(item.value)"
             >{{ item.label }}</a>
          </dd>
        </dl>
      </div>
    </div>
    <div>
      <div class="table-responsive" v-if="list.length">
        <table class="table hidden-xs">
          <thead>
            <tr class="text-dark">
              <th>{{ $t('label.candy_project') }}</th>
              <th>{{ $t('label.return') }}（{{ $t('label.million') }})</th>
              <th>{{ $t('label.lock') }}</th>
              <th>{{ $t('label.start_amount') }}</th>
              <th>{{ $t('label.leave_amount') }}</th>
              <th style="width:100px;">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in list" :key="item.id">
              <td>
                <img :src="item.logoUrl" height="40" class="img-rounded">
                <span>{{ item.nameCn }}<span class="text-gray small"> ({{ item.tokenSymbol }})</span></span>
              </td>
              <td><span class="text-danger">{{ getInterest(10000, item.interestRate, item.lockTime) }} {{ $t('label.coin_amount') }}</span></td>
              <td>{{ item.lockTime }} {{ $t('label.day') }}</td>
              <td>{{ item.minAmount }} {{ $t('label.coin_amount') }}</td>
              <td><span class="text-primary">{{ item.remainAmount }} {{ $t('label.coin_amount') }}</span></td>
              <td>
                <router-link class="btn btn-primary btn-sm btn-nocorner" :to="{path: '/candyRoom/candyBuy', query: item}">{{  $t('label.buy')}}</router-link>
              </td>
            </tr>
          </tbody>
        </table>
        <!-- 小屏幕时显示的模拟table -->
        <div class="visible-xs xs-box">
          <div class="xs-header text-gray">
            <span>{{ $t('label.candy_project') }}</span>
            <span>{{ $t('label.return') }}（{{ $t('label.million') }})</span>
            <span>{{ $t('label.operation') }}</span>
          </div>
          <div class="xs-body">
            <div class="xs-item" v-for="(item, index) in list" :key="item.id">
              <div class="xs-row" @click="handleShow(index)">
                <span>
                  <img :src="item.logoUrl" height="30" class="img-rounded">
                  <b>{{ item.nameCn }}<i class="text-gray small">{{ item.tokenSymbol }}</i></b>
                </span>
                <span>{{ getInterest(10000, item.interestRate, item.lockTime) }} {{ $t('label.coin_amount') }}</span>
                <span>
                  <router-link class="btn btn-primary btn-sm btn-nocorner" :to="{path: '/candyRoom/candyBuy', query: item}">{{ $t('label.buy') }}</router-link>
                </span>
              </div>
              <div class="xs-detail" v-if="item.isDetail">
                <p>
                <span>{{ $t('label.lock') }}: </span>
                {{ item.lockTime }} {{ $t('label.day') }}
                </p>
                <p>
                <span>{{ $t('label.start_amount') }}: </span>
                {{ item.minAmount }} {{ $t('label.coin_amount') }}
                </p>
                <p>
                <span>{{ $t('label.leave_amount') }}</span>
                {{ item.remainAmount }} {{ $t('label.coin_amount') }}

                <b class="b-hidden" @click="handleHidden(index)">{{ $t('label.shouqi') }}</b>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="nodat">{{ $t('label.no_data') }}</div>
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
      lockTime: {
        value: 0,
        items: [
          {label: '全部', value: 0},
          {label: '30天', value: 30},
          {label: '90天', value: 90},
          {label: '180天', value: 180},
          {label: '1年', value: 365}
        ],
        e_items: [
          {label: 'ALL', value: 0},
          {label: '30 day', value: 30},
          {label: '90 day', value: 90},
          {label: '180 day', value: 180},
          {label: '1 year', value: 365}
        ]
      },
      total: 0,
      currentPage: 1,
      list: [],
      projId: 1,
      asset: {
        totalAsset: 0,
        lastDayProfit: 0,
        totalProfit: 0
      }
    }
  },
  computed: {
    params () {
      return {
        pageno: this.currentPage,
        perpage: 10,
        lockTime: this.lockTime.value
      }
    },
    language () {
      return this.$i18n.locale
    }
  },
  mounted () {
    this.fetch()
    this.updateUserAsset()
  },
  methods: {
    ...mapActions(['getCandyList', 'getUserDepositAsset']),
    fetch () {
      this.loading = true
      this.getCandyList(this.params)
        .then(({dataCount = 0, dataList = []} = {}) => {
          this.total = dataCount
          this.list = dataList
          this.list.map((item) => {
            this.$set(item, 'isDetail', false)
          })
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
    updateUserAsset () {
      this.getUserDepositAsset({projId: this.projId})
        .then(({totalAsset = 0, totalProfit = 0, lastDayProfit = 0} = {}) => {
          this.asset.totalAsset = totalAsset
          this.asset.totalProfit = totalProfit
          this.asset.lastDayProfit = lastDayProfit
        })
    },
    onFilterClick (val) {
      this.lockTime.value = val
      this.currentPage = 1
      this.fetch()
    },
    handleShow (index) {
      this.list.map((item, i) => {
        if (i !== index) item.isDetail = false
      })
      this.list[index].isDetail = !this.list[index].isDetail
    },
    handleHidden (index) {
      this.list[index].isDetail = false
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

// 资产面板
.assets-panel{
  position: relative;
  background: #fff;
  height: 150px;
  width: 100%;
  margin-top: 150px;
  color: #fff;
  text-align: center;
  a{
    color: #fff;
  }
}
.assets-box{
  position: absolute;
  top: -158px;
  left: 10%;
  width: 80%;
  height:308px;
  background:linear-gradient(rgba(255,141,58,1),rgba(255,181,60,1));
  box-shadow:0px 6px 9px 0px rgba(255,153,112,0.28);
  margin: 0 auto;
  font-weight: 200;
  .assets-nav{
    text-align: right;
    font-size: 14px;
    line-height: 25px;
    padding: 15px 30px 0 0;
  }
  .assets-title{
    margin: 10px auto 0;
    a{
      width:245px;
      height:30px;
      line-height:30px;
      background:rgba(255,255,255,1);
      box-shadow: 0px 1px 7px 0px rgba(233,178,88,0.64);
      border-radius: 16px ;
      font-size: 14px;
      color: #FC9E3C;
      padding: 5px 20px;
    }
    img{
      width: 20px;
      height: 20px;
      border-radius: 50%;
    }
  }
  .assets-total{
    font-size: 48px;
    margin: 20px 0;
    a {
      display: inline-flex;
      justify-content: center;
      align-items: center;
      vertical-align: middle;
      width: 80px;
      height: 30px;
      border: 1px solid #FFF;
      border-radius: 15px;
      font-size: 14px;
    }
  }
  .assets-profit{
    h6{
      font-weight: 200;
      font-size: 14px;
    }
    p{
      font-size: 34px;
    }
    .left{
      p{
        margin-top: 18px;
      }
    }
    .right{
      h6{
        width:122px;
        height: 30px;
        line-height: 30px;
        background:rgba(255,255,255,1);
        box-shadow: 0px 1px 7px 0px rgba(233,178,88,0.64);
        border-radius: 15px;
        padding: 0 20px;
        margin: 0 auto;
        a{
          color: #FC9E3C;
        }
      }
      p{
        margin-top: 15px;
      }
    }
    .line{
      margin: 20px auto 0;
      background: #fff;
      width: 1px;
      height: 47px;
    }
  }
}

.filter-list {
  padding-bottom: 0;

  > .dl-horizontal {
    > dt {
      width: 80px;
      float: left;
      text-align: left;
      font-weight: normal;
      color: $gray-darker;
      line-height: 30px;
    }

    > dd {
      margin-left: 80px;

      a {
        display: inline-block;
        vertical-align: middle;
        padding: 6px 12px;
        margin-bottom: 10px;
        margin-right: 10px;
        line-height: 1.4;
        color: $black-light;

        &.active {
          color: #fff;
          background-color: $primary-color;
        }
      }
    }
  }
}
.table > thead > tr > th {
  background-color: #FFF;
  padding: 12px 8px;
  font-weight: normal;
}
.table > tbody > tr > td {
  background-color: #FFF;
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
</style>
