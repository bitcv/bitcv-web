<template>
  <div class="my-candy-order">
    <div class="main-area">
      <div class="title-box">
        <span class="title">我的余币宝清单</span>
        <!--<img src="/static/img/logo.png" alt="">-->
      </div>
      <div class="filter-box">
        <span class="title">订单状态</span>
        <ul class="select">
          <li>全部</li>
          <li>已完成</li>
          <li>未充值</li>
        </ul>
      </div>
      <div class="table-box">
        <table>
          <tr class="table-header">
            <th colspan="2">项目</th>
            <th>下单时间</th>
            <th>充值数量</th>
            <th>锁仓期</th>
            <th>回报</th>
            <th>订单状态</th>
            <th>操作</th>
            <th>交易哈希</th>
          </tr>
          <tr class="table-row" v-for="order in orderList" :key="order.id">
            <td>
              <img :src="order.projData.logoUrl" alt="">
            </td>
            <td>
              <div class="info-box">
                <span class="title">{{ order.projData.tokenName }}</span>
                <span class="text">{{ order.projData.tokenSymbol }}</span>
              </div>
            </td>
            <td>{{ order.orderTime }}</td>
            <td>{{ order.buyAmount }}</td>
            <td>{{ order.lockTime }}</td>
            <td>{{ order.interest }}</td>
            <td>{{ order.orderStatus }}</td>
            <td>
              <div class="btn-box" v-if="true">
                <span @click="confirmOrder">确认订单</span>
                <span @click="cancelOrder">取消订单</span>
              </div>
              <div class="btn-box" v-else>
                <span class="text">订单完成</span>
              </div>
            </td>
            <td><a :href="order.orderHashUrl" target="_blank">{{ order.orderHash }}</a></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      orderList: [{
        id: 1,
        projData: {
          logoUrl: '/static/logo/bcv.png',
          tokenName: 'bitCV',
          tokenSymbol: 'BCV'
        },
        orderTime: '2018-01-01 09:20',
        buyAmount: '3000000',
        lockTime: '180天',
        interest: '300000',
        orderStatus: '等待确认',
        orderHashUrl: 'https://etherscan.io/tx/0x23bdcb9ff2e1e7dbe6e229c22e456dde985315e98995620606d3b59f61e36552',
        orderHash: '0x23bdcb9ff2e1...6d3b59f61e36552'
      }]
    }
  },
  methods: {
    confirmOrder () {
      this.$router.push('/candyRoom/candyOrderConfirm')
    },
    cancelOrder () {
      console.log('cancel')
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
      }
      .select {
        display: inline-block;
        font-size: 14px;
        li {
          display: inline-block;
          margin-right: 47px;
          cursor: pointer;
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
          td:nth-child(7) {
            color: #FF6276;
          }
          td:nth-child(8) {
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
        }
      }
    }
  }
}
</style>
