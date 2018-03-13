<template>
  <div v-loading="loading" class="my-data">
    <div class="panel panel-custom">
      <div class="panel-body filter-list">
        <div><h3 style="margin:20px 0 30px;">{{ $t('label.my_list') }}</h3></div>
        <dl class="dl-horizontal">
          <dt>{{ $t('label.order_status') }}</dt>
          <dd v-if="language === 'cn'">
            <a href="javascript:;"
              v-for="item in lockTime.items"
              :key="item.value"
              :class="{active: lockTime.value == item.value}"
              @click="onFilterClick(item.value)"
            >{{ item.label }}</a>
          </dd>
          <dd v-else>
            <a href="javascript:;"
              v-for="item in lockTime.enitems"
              :key="item.value"
              :class="{active: lockTime.value == item.value}"
              @click="onFilterClick(item.value)"
            >{{ item.label }}</a>
          </dd>
        </dl>
      </div>
    </div>
    <div class="panel panel-custom">
      <div class="table-responsive" v-if="list.length">
        <table class="table table-hover hidden-xs">
          <thead>
            <tr class="text-dark">
              <th>{{ $t('label.candy_project') }}</th>
              <th>{{ $t('label.order_time') }}</th>
              <th>{{ $t('label.buy_q') }}</th>
              <th>{{ $t('label.lock') }}</th>
              <th>{{ $t('label.return') }}</th>
              <th>{{ $t('label.operation') }}</th>
              <th>{{ $t('label.hash') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in list" :key="item.id">
              <td>
                <img v-if="language === 'cn'" class="small-image" :src="item.logoUrl" alt="图片" height="30">
                <img v-else class="small-image" :src="item.logoUrl" alt="Image" height="30">
                <span>{{ item.nameCn }}<span class="text-gray small">{{ item.tokenSymbol }}</span></span>
              </td>
              <td>{{convertDate(item.orderTime)}}</td>
              <td>{{item.orderAmount}}</td>
              <td>{{item.lockTime}} {{ $t('label.month') }}</td>
              <td>{{item.interestRate * item.orderAmount * item.lockTime / 12}} {{ $t('label.coin_amount') }}</td>
              <td>
                <span v-if="item.status === 0" class="btn-box">
                  <button class="btn btn-warning" @click="handleConfirm(item.id)">{{ $t('label.confirm_o') }}</button><br>
                  <button class="btn btn-default" @click="handleCancel(item.id)" style="margin-top:10px;">{{ $t('label.cancel_order') }}</button>
                </span>
                <span v-else :class="{'text-muted': item.status === 2}">
                  <span v-if="language === 'cn'">{{['', '订单完成', '已取消'][item.status]}}</span>
                  <span v-else>{{['', 'Order Completed', 'Cancelled'][item.status]}}</span>
                </span>
              </td>
              <td>
                <div v-if="item.txHashList && item.txHashList.length">
                  <p v-for="(hash, index) in item.txHashList" :key="index">
                    <a :href="'https://etherscan.io/tx/' + hash" target="_blank">{{maskStr(hash, 5)}}</a>
                  </p>
                </div>
                <p v-else>-</p>
              </td>
            </tr>
          </tbody>
        </table>
        <!-- 小屏幕时显示的模拟table -->
        <div class="visible-xs xs-box">
          <div class="xs-header text-gray">
            <span>{{ $t('label.candy_project') }}</span>
            <span>{{ $t('label.in_amount') }}</span>
            <span>{{ $t('label.operation') }}</span>
          </div>
          <div class="xs-body">
            <div class="xs-item" v-for="(item, index) in list" :key="item.id">
              <div class="xs-row" @click="handleShow(index)">
                <span :style="{lineHeight: item.status === 0 ? '80px' : '50px'}">
                  <img :src="item.logoUrl" height="30" class="img-rounded">
                  <b>{{ item.nameCn }}<i class="text-gray small">{{ item.tokenSymbol }}</i></b>
                </span>
                <span :style="{lineHeight: item.status === 0 ? '80px' : '50px'}">{{ item.orderAmount }} {{ $t('label.coin_amount') }}</span>
                <span :style="{lineHeight: item.status === 0 ? '33px' : '50px', padding: item.status === 0 ? '5px 0' : '0'}">
                  <em v-if="item.status === 0" class="btn-box">
                    <button class="btn btn-warning" @click="handleConfirm(item.id)">{{ $t('label.confirm_o') }}</button>
                    <button class="btn btn-default" @click="handleCancel(item.id)">{{ $t('label.cancel_order') }}</button>
                  </em>
                  <em v-else :class="{'text-muted': item.status === 2}">
                    <span v-if="language === 'cn'">{{['', '订单完成', '已取消'][item.status]}}</span>
                    <span v-else>{{['', 'Order Completed', 'Cancelled'][item.status]}}</span>
                  </em>
                </span>
              </div>
              <div class="xs-detail" v-if="item.isDetail">
                <p>
                  <span>{{ $t('label.order_time') }}：</span>
                  {{ convertDate(item.orderTime) }} {{ $t('label.month') }}
                </p>
                <p>
                  <span>{{ $t('label.lock') }}：</span>
                  {{ item.orderAmount }} {{ $t('label.coin_amount') }}
                </p>
                <p>
                  <span>{{ $t('label.return') }}：</span>
                  {{ getInterest(item.orderAmount, item.interestRate, item.lockTime) }} {{ $t('label.coin_amount') }}
                </p>
                <p>
                  <span>{{ $t('label.hash') }}：</span>

                  <i v-if="item.txHashList && item.txHashList.length">
                    <em v-for="(hash, index) in item.txHashList" :key="index">
                      {{maskStr(hash, 5)}}
                    </em>
                  </i>
                  <i v-else>-</i>

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
import {maskStr} from '@/utils/utils'

export default {
  components: {
    Pagination
  },
  data () {
    return {
      loading: false,
      lockTime: {
        value: -1,
        items: [
          {label: '全部', value: -1},
          {label: '已充值', value: 0},
          {label: '未充值', value: 1}
        ],
        enitems: [
          {label: 'All', value: -1},
          {label: 'Completed', value: 0},
          {label: 'Not Recharged', value: 1}
        ]
      },
      total: 0,
      currentPage: 1,
      list: [],
      bitcv: {}
    }
  },
  computed: {
    params () {
      return {
        pageno: this.currentPage,
        perpage: 10,
        status: this.lockTime.value
      }
    },
    language () {
      return this.$i18n.locale
    }
  },
  created () {
    // this.bitcv = this.$route.query
    this.fetch()
  },
  methods: {
    ...mapActions(['getUserOrderList', 'cancelDepositOrder']),
    maskStr (str, number) {
      return maskStr(str, number)
    },
    fetch () {
      if (this.params.status === 3) delete this.params.status
      this.loading = true
      this.getUserOrderList(this.params)
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
    handleConfirm (id) {
      this.bitcv.orderId = id
      this.$router.push({
        path: '/candyRoom/candyDetails',
        query: this.bitcv
      })
    },
    handleCancel (id) {
      this.loading = true
      this.cancelDepositOrder({depositOrderId: id})
        .then((data = {}) => {
          this.loading = false
          this.fetch()
        })
        .catch(() => {
          this.loading = false
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
  padding-top: 12px;
  padding-bottom: 12px;
  font-weight: normal;
}
.table > tbody > tr > td {
  vertical-align: middle;
  &:nth-child(1) {
    padding-left: 20px;
  }
  &:last-child{
    p{
      // max-width: 200px;
      word-break: break-all;
    }
  }

  .text-danger {
    color: darken($primary-color, 20%);
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
.my-data{
  .btn-default{
    background: #ccc;
    color: #fff;
  }
  .btn-box{
    .btn{
      padding: 5px 8px;
      font-size: 12px;
    }
  }
  .xs-box{
    background: #fff;
    .xs-header, .xs-row{
      display: flex;
      padding: 0 15px;
      border-bottom: 1px solid #e6e6e6;
      span{
        line-height: 50px;
        flex: 2;
        &:last-child{
          flex: 1;
        }
      }
    }
    .xs-header{
      // border-bottom: 1px solid #e6e6e6;
      line-height: 50px;
    }
    .xs-body{
      em{
        font-style: normal;
      }
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
}
</style>
