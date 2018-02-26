<template>
  <div class="candy-list">
    <div class="main-area">
      <div class="title-box">
        <!--<img src="/static/img/logo.png" alt="">-->
        <span class="title">余币宝计划</span>
      </div>
      <div class="filter-box">
        <span class="title">锁仓期限</span>
        <ul class="select">
          <li :class="{cur: inputLockTime===0}" @click="inputLockTime=0">全部</li>
          <li :class="{cur: inputLockTime===1}" @click="inputLockTime=1">1个月</li>
          <li :class="{cur: inputLockTime===3}" @click="inputLockTime=3">3个月</li>
          <li :class="{cur: inputLockTime===6}" @click="inputLockTime=6">6个月</li>
          <li :class="{cur: inputLockTime===12}" @click="inputLockTime=12">12个月</li>
        </ul>
      </div>
      <div class="table-box" v-if="depositBoxList.length">
        <table>
          <tr class="table-header">
            <th colspan="2">项目</th>
            <th>回报（每万枚）</th>
            <th class="mobile-hide">锁仓期</th>
            <th class="mobile-hide">起始额度</th>
            <th class="mobile-hide">剩余额度</th>
            <th></th>
          </tr>
          <tr class="table-row" v-for="depositBox in depositBoxList" :key="depositBox.id">
            <td>
              <img :src="depositBox.logoUrl" alt="">
            </td>
            <td>
              <div class="info-box">
                <span class="title">{{ depositBox.nameCn }}</span>
                <span class="text">{{ depositBox.tokenSymbol }}</span>
              </div>
            </td>
            <td>
              <div class="table-cell" :class="mediaClass()">
                <span class="main">{{ getInterest(10000, depositBox.interestRate, depositBox.lockTime) }}枚</span>
                <span class="footer">{{ depositBox.lockTime }}个月</span>
              </div>
            </td>
            <td class="mobile-hide">{{ depositBox.lockTime }}个月</td>
            <td class="mobile-hide">{{ depositBox.minAmount }}枚</td>
            <td class="mobile-hide">{{ depositBox.remainAmount }}枚</td>
            <td>
              <div>
                <span :class="mediaClass()" @click="toDeposit(depositBox)">立即抢购</span>
              </div>
            </td>
          </tr>
        </table>
      </div>
      <div class="message" v-else>暂时没有人发起余币宝计划</div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      depositBoxList: [],
      inputLockTime: 0
    }
  },
  watch: {
    inputLockTime () {
      this.updateData()
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      var params = {
        pageno: 1,
        perpage: 10
      }
      if (this.inputLockTime) {
        params.lockTime = this.inputLockTime
      }
      this.$http.post('/api/getDepositBoxList', params).then((res) => {
        if (res.data.errcode === 0) {
          this.depositBoxList = res.data.data.dataList
        }
      })
    },
    toDeposit (depositBox) {
      this.$router.push({
        path: '/candyRoom/candyBuy',
        query: depositBox
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.candy-list {
  width: 100%;
  .main-area {
    background-color: #FFF;
    padding-top: 30px;
    .title-box {
      color: #4A4A4A;
      font-size: 20px;
      margin-bottom: 25px;
      margin-left: 20px;
      .title {
        vertical-align: middle;
      }
      img {
        width: 26px;
        height: 26px;
        border-radius: 4px;
        vertical-align: middle;
      }
    }
    .filter-box {
      margin-bottom: 20px;
      margin-left: 20px;
      color: #5D5D5D;
      font-size: 0;
      .title {
        font-family:PingFangSC-Medium;
        font-size: 14px;
        margin-right: 24px;
      }
      .select {
        display: inline-flex;
        justify-content: space-around;
        align-items: center;
        font-size: 14px;
        width: 300px;
        min-width: 200px;
        max-width: calc(100% - 80px);
        li {
          cursor: pointer;
          &.cur {
            color: #F5A623;
          }
        }
      }
    }
    .message {
      width: 100%;
      color: #9B9B9B;
      text-align: center;
      line-height: 60px;
      border-top: 1px solid #E4E4E4;
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
            text-align: center;
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
          td:nth-child(3) {
            .table-cell {
              height: 40px;
              vertical-align: top;
              display: inline-flex;
              flex-direction: column;
              justify-content: space-around;
              align-items: center;
              font-size: 20px;
              .main {
                color: #FF6276;
              }
              .footer {
                display: none;
                font-size: 12px;
                color: #4A4A4A;
              }
              &.media-mobile {
                font-size: 16px;
                .footer {
                  display: inline;
                }
              }
            }
          }
          td:nth-child(4) {
            color: #000;
            font-size: 14px;
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
            color: #F5A623;
            font-size: 16px;
          }
          td:nth-child(7) {
            span {
              cursor: pointer;
              display: inline-block;
              padding: 0 15px;
              background: linear-gradient(#FF3E3E, #FF977B);
              color: #FFF;
              border-radius: 14px;
              text-align: center;
              line-height: 28px;
              &.media-mobile {
                font-size: 12px;
                line-height: 24px;
                padding: 0 10px;
              }
            }
          }
        }
      }
    }
  }
}
</style>
