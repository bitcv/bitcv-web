<template>
  <div class="candy-buy">
    <div class="header-nav">
      <span>余币宝</span>
      >
      <span>抢购额度</span>
    </div>
    <div class="title-box" :class="mediaClass()">
      <span class="title">剩余额度</span>
      <span class="text">{{ depositBoxData.remainAmount }}</span>
    </div>
    <div class="content-area">
      <div class="left-area" :class="mediaClass()">
        <div class="row-box">
          <span class="left-title">项目：</span>
          <div class="content-box">
            <img :src="depositBoxData.logoUrl" alt="">
            <div class="info-box">
              <span class="title">{{ depositBoxData.tokenSymbol }}</span>
              <span class="text">{{ depositBoxData.nameCn }}</span>
            </div>
          </div>
        </div>
        <div class="row-box" :class="mediaClass()">
          <span class="left-title">锁仓期：</span>
          <span class="content">{{ depositBoxData.lockTime }}个月</span>
        </div>
        <div class="row-box">
          <span class="left-title">起始额度：</span>
          <span class="content">{{ depositBoxData.minAmount }}</span>
        </div>
      </div>
      <div class="right-area" :class="mediaClass()">
        <div class="top-row">
          <div class="input-box">
            <span class="title">充值数量</span>
            <input v-model="inputAmount">
          </div>
          <div class="arrow-box">
            <span class="arrow">-></span>
          </div>
          <div class="output-box">
            <span class="title">余币宝回报</span>
            <span class="output">{{ getInterest(inputAmount, depositBoxData.interestRate, depositBoxData.lockTime) }}</span>
          </div>
        </div>
        <div class="bottom-row">
          <input v-model="inputAmount">
          <span class="buy-btn" @click="toDepositOrder">去下单</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      inputAmount: '',
      depositBoxData: {}
    }
  },
  mounted () {
    this.depositBoxData = this.$route.query
    this.inputAmount = this.depositBoxData.minAmount
  },
  methods: {
    toDepositOrder () {
      this.depositBoxData.orderAmount = this.inputAmount
      this.$router.push({
        path: '/candyRoom/candyOrder',
        query: this.depositBoxData
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.candy-buy {
  width: 100%;
  height: 580px;
  box-sizing: border-box;
  padding: 20px;
  background-color: #FF6276;
  .header-nav {
    color: #FFACB7;
    font-size: 16px;
  }
  .title-box {
    width: 100%;
    text-align: center;
    color: #FFF;
    font-size: 72px;
    position: relative;
    .title {
      font-size: 24px;
    }
    margin: 43px 0 79px;
    &.media-mobile {
      margin: 20px 0 150px;
      font-size: 36px;
      .title {
        position: absolute;
        font-size: 14px;
        top: 43px;
        margin: auto;
        left: 0;
        right: 0;
      }
    }
  }
  .content-area {
    width: 100%;
    height: 320px;
    background-color: #FFF;
    font-size: 0;
    position: relative;
    .left-area {
      width: 34%;
      height: 100%;
      display: inline-flex;
      flex-direction: column;
      justify-content: space-around;
      align-items: flex-start;
      font-size: 14px;
      padding: 30px 0 30px 30px;
      border-right: 1px solid #FF6276;
      box-sizing: border-box;
      color: #4A4A4A;
      &.media-mobile {
        position: absolute;
        left: 10%;
        top: -120px;
        width: 80%;
        height: 170px;
        background-color: #FFF;
        box-sizing: border-box;
        box-shadow: -4px 0px 23px 0px rgba(255, 98, 118, 0.25);
        border: none;
        border-radius: 4px ;
      }
      .row-box {
        .left-title {
          display: inline-block;
          width: 70px;
        }
        .content-box {
          display: inline-block;
          vertical-align: middle;
          font-size: 0;
          img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
          }
          .info-box {
            height: 40px;
            display: inline-flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            vertical-align: top;
            color: #9B9B9B;
            font-size: 14px;
            .title {
              font-size: 18px;
              color: #4A4A4A;
            }
          }
        }
      }
    }
    .right-area {
      display: inline-block;
      vertical-align: top;
      width: 66%;
      height: 100%;
      font-size: 16px;
      color: #4A4A4A;
      padding-top: 60px;
      .top-row {
        height: 110px;
        display: flex;
        width:100%;
        justify-content: center;
        align-items: center;
        margin-bottom: 50px;
        .input-box {
          display: flex;
          flex-direction: column;
          justify-content: space-around;
          align-items: center;
          height: 100%;
          .title {

          }
          input {
            width: 200px;
            height: 56px;
            font-size: 24px;
            color: #4A4A4A;
            line-height: 56px;
            text-align: center;
            border-bottom: 1px solid #FF6276;
          }
        }
        .arrow-box {
          width: 175px;
          height: 100%;
          color: #FF6276;
          font-size: 40px;
          display: flex;
          justify-content: center;
          align-items: center;
        }
        .output-box {
          display: flex;
          flex-direction: column;
          justify-content: space-around;
          align-items: center;
          height: 100%;
          .output {
            display: block;
            width: 200px;
            height: 56px;
            font-size: 24px;
            color: #FF6276;
            line-height: 56px;
            text-align: center;
            border-bottom: 1px solid #FF6276;
          }
        }
      }
      .bottom-row {
        font-size: 0;
        width: 330px;
        max-width: 90%;
        margin: auto;
        text-align: center;
        input {
          display: inline-block;
          width: 70%;
          height: 40px;
          font-size: 24px;
          color: #4A4A4A;
          text-align: center;
          line-height: 40px;
          box-sizing: border-box;
          border: 1px solid #FF6276;
          border-radius: 28px 0 0 28px;
        }
        .buy-btn {
          display: inline-block;
          vertical-align: top;
          width: 30%;
          height: 40px;
          font-size: 16px;
          line-height: 40px;
          text-align: center;
          color: #FFF;
          background: linear-gradient(#FF3E3E, #FF977B);
          border-radius: 0 28px 28px 0;
          cursor: pointer;
        }
      }
      &.media-mobile {
        box-sizing: border-box;
        width: 100%;
        .top-row {
          margin-top: 10px;
          height: 80px;
          .title {
            font-size: 16px;
          }
          input {
            width: 120px;
            font-size: 16px;
          }
        }
        .arrow-box {
          width: 60px;
          .arrow {
            font-size: 30px;
          }
        }
        .output-box {
          .title {

          }
          .output {
            font-size: 16px;
            width: 120px;
          }
        }
        .bottom-row {
          max-width: 80%;
          input {
            font-size: 18px;
            height: 32px;
          }
          .buy-btn {
            height: 32px;
            font-size: 14px;
            line-height: 32px;
          }
        }
      }
    }
  }
}
</style>
