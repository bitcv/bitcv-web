<template>
  <div v-loading="loading" class="my-data">
    <div class="panel panel-custom">
      <div class="panel-body filter-list">
        <div><h3 style="margin:20px 0 30px;">我的余币宝订单</h3></div>
        <dl class="dl-horizontal">
          <dt>订单状态</dt>
          <dd>
            <a href="javascript:;"
              v-for="item in lockTime.items"
              :key="item.value"
              :class="{active: lockTime.value == item.value}"
              @click="onFilterClick(item.value)"
            >{{ item.label }}</a>
          </dd>
        </dl>
      </div>
    </div>
    <div class="panel panel-custom">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr class="text-dark">
              <th>项目</th>
              <th>下单时间</th>
              <th>充值数量</th>
              <th>锁仓期</th>
              <th>回报</th>
              <th>操作</th>
              <th>交易哈希</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in list" :key="item.id">
              <td>
                <img class="small-image" :src="item.logoUrl" alt="图片" height="30">
                <span>{{ item.nameCn }}</span>
              </td>
              <td>{{convertDate(item.orderTime)}}</td>
              <td>{{item.orderAmount}}</td>
              <td>{{item.lockTime}}个月</td>
              <td>{{item.interestRate * item.orderAmount * item.lockTime / 12}}枚</td>
              <td>
                <span v-if="item.status === 0" class="btn-box">
                  <button class="btn btn-warning" @click="handleConfirm(item.id)">确认订单</button><br>
                  <button class="btn btn-default" @click="handleCancel(item.id)" style="margin-top:10px;">取消订单</button>
                </span>
                <span v-else :class="{'text-muted': item.status === 2}">
                  {{['', '订单完成', '已取消'][item.status]}}
                </span>
              </td>
              <td>
                <div v-if="item.txHashList && item.txHashList.length">
                  <p v-for="(hash, index) in item.txHashList" :key="index">
                    {{maskStr(hash, 5)}}
                  </p>
                </div>
                <p v-else>-</p>
              </td>
            </tr>
          </tbody>
        </table>
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
import {maskStr} from '@/utils/utils'

export default {
  components: {
    Pagination
  },
  data () {
    return {
      loading: false,
      lockTime: {
        value: 3,
        items: [
          {label: '全部', value: 3},
          {label: '已充值', value: 0},
          {label: '未充值', value: 1}
        ]
      },
      total: 0,
      currentPage: 1,
      list: []
    }
  },
  computed: {
    params () {
      return {
        pageno: this.currentPage,
        perpage: 10,
        status: this.lockTime.value
      }
    }
  },
  created () {
    this.bitcv = this.$route.query
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
}
</style>
