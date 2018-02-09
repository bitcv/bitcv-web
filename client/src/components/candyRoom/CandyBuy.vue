<template>
  <div class="candy-buy">
    <div class="header-nav">
      <span>余币宝</span>
      >
      <span>抢购额度</span>
    </div>
    <div class="title-box">
      <span class="title">剩余额度</span>
      <span class="text">9000000.00</span>
    </div>
    <div class="content-area">
      <div class="left-area">
        <div class="row-box">
          <span class="title">项目：</span>
          <div class="content-box">
            <img src="/static/logo/bcv.png" alt="">
            <div class="info-box">
              <span class="title">BCV</span>
              <span class="text">Bitcv</span>
            </div>
          </div>
        </div>
        <div class="row-box">
          <span class="title">锁仓期：</span>
          <span class="content">180天</span>
        </div>
        <div class="row-box">
          <span class="title">起始额度：</span>
          <span class="content">30000</span>
        </div>
      </div>
      <div class="right-area">
        <div class="top-row">
          <div class="input-box">
            <span class="title">充值数量</span>
            <input v-model="inputAmount">
          </div>
          <div class="arrow-box">
            <span class="arrow">-></span>
          </div>
          <div class="output-box">
            <span class="title">糖果回报</span>
            <span class="output">{{ outputInterest }}</span>
          </div>
        </div>
        <div class="bottom-row">
          <input v-model="inputAmount">
          <span class="buy-btn" @click="toCandyOrder">抢糖果</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      inputAmount: 30000
    }
  },
  computed: {
    outputInterest () {
      return this.inputAmount * 0.1
    }
  },
  methods: {
    toCandyOrder () {
      this.$router.push('/candyRoom/candyOrder')
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
    .title {
      font-size: 24px;
    }
    margin: 43px 0 79px;
  }
  .content-area {
    width: 100%;
    height: 320px;
    background-color: #FFF;
    font-size: 0;
    .left-area {
      width: 34%;
      height: 100%;
      display: inline-flex;
      flex-direction: column;
      justify-content: space-around;
      align-items: flex-start;
      font-size: 14px;
      padding: 30px 0 30px 50px;
      border-right: 1px solid #FF6276;
      box-sizing: border-box;
      color: #4A4A4A;
      .row-box {
        .title {
          display: inline-block;
          width: 86px;
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
        width: 100%;
        text-align: center;
        input {
          display: inline-block;
          width: 244px;
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
          width: 88px;
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
    }
  }
}
</style>
