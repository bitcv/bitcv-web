<template>
  <div class="candy-order-detail">
    <div class="title-area">
      <span>订单详情</span>
    </div>
    <div class="content-area">
      <div class="info-area">
        <div v-if="orderData.projData" class="info-row">
          <span class="title">项目：</span>
          <div class="content-box">
            <img :src="orderData.projData.logoUrl" alt="">
            <div class="info-box">
              <span class="title">{{ orderData.projData.tokenSymbol }}</span>
              <span class="text">{{ orderData.projData.tokenName }}</span>
            </div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-item">
            <span class="title">充值数量：</span>
            <span class="content">{{ orderData.orderAmount }}枚</span>
          </div>
          <div class="info-item">
            <span class="title">锁仓期：</span>
            <span class="content">{{ orderData.lockTime }}个月</span>
          </div>
          <div class="info-item">
            <span class="title">回报：</span>
            <span class="content">{{ orderData.interestRate * orderData.orderAmount }}枚</span>
          </div>
        </div>
        <div class="info-row">
          <span class="title">接收地址：</span>
          <span class="content">{{ orderData.toAddr }}</span>
        </div>
        <div class="info-row">
          <span class="title">您的地址：</span>
          <span class="content">{{ orderData.fromAddr }}</span>
        </div>
        <div class="status-row">
          <span class="title">订单状态：</span>
          <span class="content">等待确认</span>
        </div>
        <div class="qrcode"></div>
      </div>
      <div class="btn-box">
        <div class="btn-row">
          <input type="checkbox">
          <span>我已向目标接收地址充值<em>{{ orderData.orderAmount }}</em>枚</span>
        </div>
        <div class="btn" @click="toOrderConfirm">
          <span>开始确认</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      orderData: {}
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
    // this.depositBoxData = this.$route.query
    this.$http.post('/api/getOrderDetail', {
      depositOrderId: this.$route.params.id
    }).then((res) => {
      if (res.data.errcode === 0) {
        this.orderData = res.data.data
        this.$nextTick(() => {
          require('@/components/share/jquery.min.js')
          require('@/components/share/qrcode.min.js')
          this.getQrcode()
        })
      }
    })
  },
  methods: {
    toOrderConfirm () {
      console.log('click')
      this.$router.push({
        path: '/candyRoom/candyOrderConfirm',
        query: this.orderData
      })
    },
    getQrcode () {
      // eslint-disable-next-line
      $('.qrcode').qrcode({
        text: this.orderData.toAddr,
        width: 150,
        height: 150
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.candy-order-detail {
  .title-area {
    width: 100%;
    height: 108px;
    background-color: #FFF;
    font-size: 24px;
    color: #4A4A4A;
    text-align: center;
    line-height: 108px;
    margin-bottom: 10px;
  }
  .content-area {
    background-color: #FFF;
    padding: 20px;
    position: relative;
    .info-area {
      box-sizing: border-box;
      width: 100%;
      border-bottom: 1px solid #C2C2C2;
      padding: 30px 0 30px 80px;
      position: relative;
      .info-row {
        height: 46px;
        font-size: 16px;
        color: #4A4A4A;
        &:first-child {
          margin-bottom: 20px;
        }
        >.title {
          display: inline-block;
          width: 80px;
          text-align: right;
        }
        .info-item {
          margin-right: 40px;
          display: inline-block;
          .content {
            font-size: 16px;
            color: #FF6276;
          }
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
            display: inline-flex;
            vertical-align: top;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            height: 40px;
            font-size: 14px;
            color: #9B9B9B;
            .title {
              font-size: 18px;
              color: #4A4A4A;
              text-align: left;
            }
          }
        }
      }
      .status-row {
        position: absolute;
        top: 20px;
        right: 20px;
        .content {
          color: #FF6276;
        }
      }
    }
    .btn-box {
      text-align: center;
      .btn-row {
        color: #9B9B9B;
        line-height: 70px;
        font-size: 0;
        input {
          vertical-align: middle;
          width: 22px;
          height: 22px;
          position: relative;
          bottom: 6px;
        }
        span {
          font-size: 16px;
          em {
            color: #FF6276;
          }
        }
      }
      .btn {
        display: inline-block;
        width: 160px;
        height: 40px;
        border-radius: 23px;
        background-color: #FF6276;
        color: #FFF;
        line-height: 40px;
        cursor: pointer;
        margin-bottom: 10px;
      }
    }
  }
}
.qrcode {
  position: absolute;
  width: 150px;
  height: 150px;
  right: 110px;
  top: 80px;
}
</style>
