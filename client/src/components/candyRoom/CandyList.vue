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
          <li :class="{cur: inputLockTime===3}" @click="inputLockTime=3">3个月</li>
          <li :class="{cur: inputLockTime===6}" @click="inputLockTime=6">6个月</li>
          <li :class="{cur: inputLockTime===12}" @click="inputLockTime=12">12个月</li>
        </ul>
      </div>
      <div class="table-box">
        <table>
          <tr class="table-header">
            <th>回报（每万枚）</th>
            <th>锁仓期</th>
            <th colspan="2">项目</th>
            <th>起始额度</th>
            <th>剩余额度</th>
            <th></th>
          </tr>
          <tr class="table-row" v-for="depositBox in depositBoxList" :key="depositBox.id">
            <td>{{ depositBox.interestRate * 10000 }}枚</td>
            <td>{{ depositBox.lockTime }}个月</td>
            <td>
              <img :src="depositBox.logoUrl" alt="">
            </td>
            <td>
              <div class="info-box">
                <span class="title">{{ depositBox.tokenName }}</span>
                <span class="text">{{ depositBox.tokenSymbol }}</span>
              </div>
            </td>
            <td>{{ depositBox.minAmount }}枚</td>
            <td>{{ depositBox.remainAmount }}枚</td>
            <td>
              <div>
                <span @click="toDeposit(depositBox)">立即抢购</span>
              </div>
            </td>
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
        display: inline-block;
        font-size: 14px;
        li {
          display: inline-block;
          margin-right: 47px;
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
            color: #FF6276;
            font-size: 20px;
          }
          td:nth-child(2) {
            color: #000;
            font-size: 14px;
          }
          td:nth-child(3) {
            text-align: right;
            img {
              width: 40px;
              height: 40px;
              margin-right: 10px;
            }
          }
          td:nth-child(4) {
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
              width: 110px;
              height: 28px;
              background: linear-gradient(#FF3E3E, #FF977B);
              color: #FFF;
              border-radius: 14px;
              text-align: center;
              line-height: 28px;
            }
          }
        }
      }
    }
  }
}
</style>
