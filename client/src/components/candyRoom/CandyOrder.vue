<template>
  <div class="candy-order">
    <div class="header-nav">
      <span>余币宝</span>
      >
      <span>地址设置</span>
    </div>
    <div class="info-area">
      <span>抢购详情</span>
      <span>需充值{{ depositBoxData.orderAmount }}枚</span>
      <span>{{ depositBoxData.lockTime }}个月后获得糖果<em>{{ interestAmount }}</em>枚</span>
    </div>
    <div class="content-area">
      <div class="form-row">
        <span class="step-index">1</span>
        <div class="input-box">
          <span class="title">接收钱包地址</span>
          <!--<span class="text">0x7dfffb38b871fda8a820378d6531a8267cc414a5</span>-->
          <span class="text">{{ depositBoxData.toAddr }}</span>
        </div>
      </div>
      <div class="form-row">
        <span class="step-index">2</span>
        <div class="input-box">
          <span class="title">支付钱包地址</span>
          <input v-model="inputFromAddr">
        </div>
      </div>
      <div class="form-row">
        <span class="step-index">3</span>
        <div class="btn-box">
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
      if (!this.inputFromAddr) {
        console.log(this.depositBoxData.orderAmount)
        return alert('请输入正确的支付钱包地址')
      }
      this.$http.post('/api/addDepositOrder', {
        'depositBoxId': this.depositBoxData.id,
        'orderAmount': this.depositBoxData.orderAmount,
        'fromAddr': this.inputFromAddr
      }).then((res) => {
        if (res.data.errcode === 0) {
          var orderData = res.data.data
          this.depositBoxData.depositOrderId = orderData.id
          this.depositBoxData.fromAddr = orderData.fromAddr
          this.$router.push({
            path: '/candyRoom/candyOrderDetail',
            query: this.depositBoxData
          })
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
    height: 556px;
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
        display: inline-flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        color: #4A4A4A;
        .title {
          font-size: 18px;
        }
        input {
          border-bottom: 1px solid #4A4A4A;
          width: 570px;
          height: 50px;
          font-size: 24px;
          line-height: 50px;
          text-align: left;
        }
        .text {
          width: 570px;
          height: 50px;
          font-size: 24px;
          line-height: 50px;
          text-align: left;
        }
      }
      .btn-box {
        display: inline-block;
        width: 570px;
        text-align: center;
        line-height: 137px;
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
