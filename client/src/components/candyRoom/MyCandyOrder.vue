<template>
  <div class="my-candy-order">
    <div class="main-area">
      <div class="title-box" :class="mediaClass()">
        <span class="title">我的余币宝清单</span>
        <!--<img src="/static/img/logo.png" alt="">-->
      </div>
      <div class="filter-box">
        <span class="title" :class="mediaClass()">订单状态</span>
        <ul class="select" :class="mediaClass()">
          <li :class="{cur: inputStatus===null}" @click="inputStatus=null">全部</li>
          <li :class="{cur: inputStatus===1}" @click="inputStatus=1">已完成</li>
          <li :class="{cur: inputStatus===0}" @click="inputStatus=0">待充值</li>
        </ul>
      </div>
      <div class="table-box">
        <el-table :data="orderList" v-if="mediaClass() === 'media-mobile'">
          <el-table-column type="expand">
            <template slot-scope="props">
              <el-form label-position="left" inline>
                <el-form-item label="项目名称">{{ props.row.nameCn }}</el-form-item>
                <el-form-item label="符号">{{ props.row.tokenSymbol }}</el-form-item>
                <el-form-item label="购买数量">{{ props.row.orderAmount }}</el-form-item>
                <el-form-item label="锁仓期">{{ props.row.lockTime }}个月</el-form-item>
                <el-form-item label="总回报">{{ props.row.interestRate * props.row.orderAmount * props.row.lockTime / 12 }}枚</el-form-item>
                <el-form-item label="交易哈希">{{ props.row.txHash }}</el-form-item>
                <el-form-item label="下单时间">{{ convertDate(props.row.orderTime) }}</el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column label="项目符号" prop="tokenSymbol">
            <template slot-scope="scope">
              <img class="small-image" :src="scope.row.logoUrl" alt="">
              <span>{{ scope.row.tokenSymbol }}</span>
            </template>
          </el-table-column>
          <el-table-column label="订单金额" prop="orderAmount"></el-table-column>
          <el-table-column label="操作" prop="orderAmount">
            <template slot-scope="scope">
              <div v-if="scope.row.status === 0">
              <el-button @click="confirmOrder(scope.row.id)" size="mini" type="primary" style="margin:0 0 10px;display:inline;">确认</el-button>
              <el-button @click="cancelOrder(scope.row.id)" size="mini" type="danger" style="margin:0">删除</el-button>
              </div>
              <div class="btn-box" v-else-if="scope.row.status === 1">
                <span class="text">订单完成</span>
              </div>
              <div class="btn-box" v-else-if="scope.row.status === 2">
                <span class="text">已取消</span>
              </div>
            </template>
          </el-table-column>
        </el-table>
        <table v-else>
          <tr class="table-header">
            <th colspan="2">项目</th>
            <th class="mobile-hide">下单时间</th>
            <th>充值数量</th>
            <th class="mobile-hide">锁仓期</th>
            <th class="mobile-hide">总回报</th>
            <th>操作</th>
            <th class="mobile-hide">交易哈希</th>
          </tr>
          <template v-for="order in orderList">
          <tr class="table-row" :key="order.id">
            <td>
              <img :src="order.logoUrl" alt="">
            </td>
            <td>
              <div class="info-box">
                <span class="title">{{ order.nameCn }}</span>
                <span class="text">{{ order.tokenSymbol }}</span>
              </div>
            </td>
            <td class="mobile-hide">{{ convertDate(order.orderTime) }}</td>
            <td>{{ order.orderAmount }}枚</td>
            <td class="mobile-hide">{{ order.lockTime }}个月</td>
            <td class="mobile-hide">{{ order.interestRate * order.orderAmount * order.lockTime / 12 }}枚</td>
            <td>
              <div class="btn-box" v-if="order.status === 0">
                <span @click="confirmOrder(order.id)">确认订单</span>
                <span @click="cancelOrder(order.id)">取消订单</span>
              </div>
              <div class="btn-box" v-else-if="order.status === 1">
                <span class="text">订单完成</span>
              </div>
              <div class="btn-box" v-else-if="order.status === 2">
                <span class="text">已取消</span>
              </div>
            </td>
            <td class="mobile-hide">
              <template v-if="order.txHashList.length">
                <a v-for="(txHash, index) in order.txHashList" :key="index" :href="'https://etherscan.io/tx/' + txHash" target="_blank">{{ getShortStr(txHash, 10) }}</a>
              </template>
              <span v-else>暂无交易</span>
            </td>
          </tr>
          </template>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      orderList: [],
      inputStatus: null
    }
  },
  beforeCreate () {
    var userId = localStorage.getItem('userId')
    if (!userId) {
      alert('请登录')
      this.$router.push('/candyRoom/candyList')
    }
  },
  mounted () {
    this.updateData()
  },
  watch: {
    inputStatus () {
      this.updateData()
    }
  },
  methods: {
    confirmOrder (orderId) {
      this.$router.push('/candyRoom/candyOrderConfirm/' + orderId)
    },
    updateData () {
      var params = {
        pageno: 1,
        perpage: 10
      }
      if (this.inputStatus !== null) {
        params.status = this.inputStatus + ''
      }
      console.log(params)
      this.$http.post('/api/getUserOrderList', params).then((res) => {
        if (res.data.errcode === 0) {
          this.orderList = res.data.data.dataList
        }
      })
    },
    cancelOrder (orderId) {
      this.$http.post('/api/cancelDepositOrder', {
        depositOrderId: orderId
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.updateData()
        } else {
          alert(res.data.errmsg)
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.my-candy-order {
  width: 100%;
  .main-area {
    padding: 30px 20px 0 20px;
    background-color: #FFF;
    .title-box {
      &.media-mobile {
        .title {
          font-size: 16px;
        }
      }
      color: #4A4A4A;
      margin-bottom: 25px;
      font-size: 0;
      .title {
        font-size: 20px;
        vertical-align: middle;
      }
      img {
        width: 26px;
        height: 26px;
        border-radius: 4px;
        vertical-align: middle;
        margin-left: 20px;
      }
    }
    .filter-box {
      margin-bottom: 20px;
      color: #5D5D5D;
      font-size: 0;
      .title {
        font-family:PingFangSC-Medium;
        font-size: 14px;
        margin-right: 24px;
        &.media-mobile {
          margin-right: 10px;
        }
      }
      .select {
        display: inline-flex;
        justify-content: space-around;
        align-items: center;
        font-size: 14px;
        width: 300px;
        min-width: 200px;
        max-width: calc(100% - 80px);
        &.media-mobile {
          width: 120px !important;
          min-width: 140px;
        }
        li {
          cursor: pointer;
          &.cur {
            color: #F5A623;
          }
        }
      }
    }
    .table-box {
      table {
        width: 100%;
        text-align: center;
        .table-header {
          th {
            color: #9B9B9B;
            font-size: 14px;
            line-height: 54px;
            border-top: 1px solid #E4E4E4;
          }
        }
        .table-row {
          height: 80px;
          border-top: 1px solid #E4E4E4;
          font-size: 16px;
          td {
            vertical-align: middle;
          }
          td:nth-child(1) {
            text-align: right;
            img {
              width: 40px;
              height: 40px;
              margin-right: 10px;
            }
          }
          td:nth-child(2) {
            text-align: left;
            .info-box {
              vertical-align: top;
              height: 40px;
              display: inline-flex;
              flex-direction: column;
              justify-content: space-between;
              align-items: flex-start;
              font-size: 14px;
              color: #9B9B9B;
              .title {
                color: #4A4A4A;
              }
            }
          }
          td:nth-child(6) {
            color: #FF6276;
          }
          td:nth-child(7) {
            span {
              display: block;
              cursor: pointer;
              width: 110px;
              height: 28px;
              background-color: #FF6276;
              color: #FFF;
              border-radius: 14px;
              text-align: center;
              line-height: 28px;
              margin: 3px auto;
              &:last-child {
                background-color: #9B9B9B;
                &.text {
                  color: #F5A623;
                  background-color: #FFF;
                  cursor: text;
                }
              }
            }
          }
          td:nth-child(8) {
            padding: 5px 0;
            font-size: 14px;
            max-width: 100px;
            a {
              color: #000;
              display: block;
              line-height: 20px;
              &:hover {
                color: #F5A623;
              }
            }
          }
        }
      }
    }
  }
}
img {
  width: 20px !important;
  height: 20px !important;
  vertical-align: middle;
}
</style>
