<template>
  <div class="candy-order">
    <div class="header-nav">
      <span>余币宝</span>
      >
      <span>地址设置</span>
    </div>
    <div class="info-area" :class="mediaClass()">
      <span>抢购详情</span>
      <span>需充值{{ depositBoxData.orderAmount }}枚</span>
      <span>{{ depositBoxData.lockTime }}个月后获得回报<em>{{ getInterest(depositBoxData.orderAmount, depositBoxData.interestRate, depositBoxData.lockTime) }}</em>枚</span>
    </div>
    <div class="content-area">
      <div class="form-row" :class="mediaClass()">
        <span class="step-index mobile-hide">1</span>
        <div class="input-box" :class="mediaClass()">
          <span class="title">充值接收地址</span>
          <span class="text">{{ depositBoxData.toAddr }}</span>
          <span class="foot-text">此地址为项目方与平台共同认可的资金存管地址，回报已入账，请放心充值</span>
        </div>
      </div>
      <div class="form-row" :class="mediaClass()">
        <span class="step-index mobile-hide">2</span>
        <div class="input-box" :class="mediaClass()">
          <span class="title">您的支出地址</span>
          <input v-model="inputFromAddr">
        </div>
      </div>
      <div class="form-row" :class="mediaClass()">
        <span class="step-index mobile-hide">3</span>
        <div class="btn-box" :class="mediaClass()">
          <span @click="submitOrder">提交订单</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      inputFromAddr: '',
      depositBoxData: {}
    }
  },
  mounted () {
    this.depositBoxData = this.$route.query
  },
  computed: {
    interestAmount () {
      return this.depositBoxData.interestRate * this.depositBoxData.orderAmount
    }
  },
  methods: {
    submitOrder () {
      if (this.inputFromAddr.indexOf('0x') !== 0 || this.inputFromAddr.length !== 42 || this.inputFromAddr === this.depositBoxData.toAddr) {
        return alert('请输入正确的支付钱包地址')
      }
      this.$http.post('/api/addDepositOrder', {
        'depositBoxId': this.depositBoxData.id,
        'orderAmount': this.depositBoxData.orderAmount + '',
        'fromAddr': this.inputFromAddr
      }).then((res) => {
        if (res.data.errcode === 0) {
          var orderData = res.data.data
          this.$router.push('/candyRoom/candyOrderConfirm/' + orderData.id)
        } else {
          alert(res.data.errmsg)
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.candy-order {
  width: 100%;
  height: 737px;
  box-sizing: border-box;
  padding: 20px;
  background-color: #FF6276;
  .header-nav {
    color: #FFACB7;
    font-size: 16px;
  }
  .info-area {
    width: 646px;
    max-width: 90%;
    height: 222px;
    display: flex;
    box-sizing: border-box;
    flex-direction: column;
    justify-content: space-between;
    padding: 20px 0 60px;
    align-items: center;
    margin: 5px auto;
    background-color: #FFF;
    color: #FF6276;
    font-size: 20px;
    position: relative;
    z-index: 2;
    border: 1px solid #FF6276;
    :first-child {
      font-size: 18px;
      line-height: 30px;
      border-bottom: 1px solid #FF6276;
    }
    &.media-mobile {
      height: 150px;
      padding: 20px 0;
      :first-child {
        font-size: 16px;
      }
      font-size: 16px;
      :last-child {
        font-size: 20px;
      }
    }
    :last-child {
      font-size: 30px;
      color: #4A4A4A;
      em {
        color: #FF6276;
      }
    }
  }
  .content-area {
    width: 100%;
    background-color: #FFF;
    box-sizing: border-box;
    padding: 110px 40px 0;
    position: relative;
    bottom: 110px;
    .form-row {
      position: relative;
      height: 137px;
      width: 100%;
      display: flex;
      &.media-mobile {
        height: 100px;
      }
      justify-content: center;
      .step-index {
        position: absolute;
        left: 0;
        top: calc(50% - 25px);
        display: inline-block;
        width: 50px;
        height: 50px;
        line-height: 50px;
        text-align: center;
        border-radius: 50%;
        color: #FF6276;
        border: 1px solid #4A4A4A;
        margin-right: 150px;
      }
      .input-box {
        max-width: 100%;
        display: inline-flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        color: #4A4A4A;
        .title {
          font-size: 18px;
        }
        &.media-mobile {
          .title {
            font-size: 16px;
          }
          .text {
            font-size: 12px;
          }
          input {
            font-size: 12px;
            height: 40px;
          }
          .foote-text {
            font-size: 12px;
          }
        }
        input {
          border-bottom: 1px solid #4A4A4A;
          width: 570px;
          max-width: 90%;
          height: 50px;
          font-size: 24px;
          line-height: 50px;
          text-align: left;
        }
        .text {
          width: 570px;
          max-width: 90%;
          height: 50px;
          font-size: 24px;
          line-height: 50px;
          text-align: left;
        }
        .foot-text {
          font-size: 12px;
          color: #FF6276;
        }
      }
      .btn-box {
        display: inline-block;
        width: 570px;
        max-width: 90%;
        text-align: center;
        line-height: 137px;
        &.media-mobile {
          line-height: 100px;
          span {
            width: 180px;
            height: 46px;
            line-height: 46px;
          }
        }
        span {
          display: inline-block;
          width: 258px;
          height: 54px;
          font-size: 18px;
          line-height: 54px;
          border-radius: 27px;
          background: linear-gradient(#FF3E3E, #FF977B);
          color: #FFF;
          cursor: pointer;
        }
      }
    }
  }
}
</style>
