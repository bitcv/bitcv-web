<template>
  <div v-loading="loading">
    <div class="panel panel-custom">
      <div class="panel-body filter-list">
        <div><h3 style="margin:20px 0 30px;">余币宝计划</h3></div>
        <dl class="dl-horizontal">
          <dt>锁仓期限</dt>
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
      <div class="table-responsive" v-if="list.length">
        <table class="table hidden-xs">
          <thead>
            <tr class="text-dark">
              <th>项目</th>
              <th>回报（每万枚）</th>
              <th>锁仓期</th>
              <th>起始额度</th>
              <th>剩余额度</th>
              <th style="width:100px;">操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in list" :key="item.id">
              <td>
                <img :src="item.logoUrl" height="30" class="img-rounded">
                <span>{{ item.nameCn }}<span class="text-gray small">{{ item.tokenSymbol }}</span></span>
              </td>
              <td><span class="text-danger">{{ getInterest(10000, item.interestRate, item.lockTime) }}枚</span></td>
              <td>{{ item.lockTime }}个月</td>
              <td>{{ item.minAmount }}枚</td>
              <td><span class="text-primary">{{ item.remainAmount }}枚</span></td>
              <td>
                <router-link class="btn btn-primary btn-sm btn-nocorner" :to="{path: '/candyRoom/candyBuy', query: item}">立即抢购</router-link>
              </td>
            </tr>
          </tbody>
        </table>
        <!-- 小屏幕时显示的模拟table -->
        <div class="visible-xs xs-box">
          <div class="xs-header text-gray">
            <span>项目</span>
            <span>回报（每万枚）</span>
            <span>操作</span>
          </div>
          <div class="xs-body">
            <div class="xs-item" v-for="(item, index) in list" :key="item.id">
              <div class="xs-row" @click="handleShow(index)">
                <span>
                  <img :src="item.logoUrl" height="30" class="img-rounded">
                  <b>{{ item.nameCn }}<i class="text-gray small">{{ item.tokenSymbol }}</i></b>
                </span>
                <span>{{ getInterest(10000, item.interestRate, item.lockTime) }}枚</span>
                <span>
                  <router-link class="btn btn-primary btn-sm btn-nocorner" :to="{path: '/candyRoom/candyBuy', query: item}">立即抢购</router-link>
                </span>
              </div>
              <div class="xs-detail" v-if="item.isDetail">
                <p>
                  <span>锁仓期：</span>
                  {{ item.lockTime }}个月
                </p>
                <p>
                  <span>起始额度：</span>
                  {{ item.minAmount }}枚
                </p>
                <p>
                  <span>剩余额度：</span>
                  {{ item.remainAmount }}枚

                  <b class="b-hidden" @click="handleHidden(index)">收起</b>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="nodat">暂无数据</div>
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
          {label: '1个月', value: 1},
          {label: '3个月', value: 3},
          {label: '6个月', value: 6},
          {label: '12个月', value: 12}
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
        lockTime: this.lockTime.value
      }
    }
  },
  created () {
    this.fetch()
  },
  methods: {
    ...mapActions(['getCandyList']),
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
    getInterest (amount, interestRate, lockTime) {
      return parseInt(amount * interestRate * lockTime / 12 * 100) / 100
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
