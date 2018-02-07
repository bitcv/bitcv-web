<template>
  <div class="candy-order-detail">
    <div class="title-area">
      <span>订单详情</span>
    </div>
    <div class="content-area">
      <div class="info-area">
        <div class="info-row">
          <span class="title">项目：</span>
          <div class="content-box">
            <img src="/static/img/logo.png" alt="">
            <div class="info-box">
              <span class="title">BCV</span>
              <span class="text">BitCV</span>
            </div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-item">
            <span class="title">充值数量：</span>
            <span class="content">3000枚</span>
          </div>
          <div class="info-item">
            <span class="title">锁仓期：</span>
            <span class="content">180天</span>
          </div>
          <div class="info-item">
            <span class="title">回报：</span>
            <span class="content">8000枚</span>
          </div>
        </div>
        <div class="info-row">
          <span class="title">接收地址：</span>
          <span class="content">dsksdgjsugsndnvsasgsage</span>
        </div>
        <div class="info-row">
          <span class="title">您的地址：</span>
          <span class="content">dsksdgjsugsndnvsasgsage</span>
        </div>
        <div class="status-row">
          <span class="title">订单状态：</span>
          <span class="content">等待确认</span>
        </div>
      </div>
      <div class="btn-box">
        <div class="btn-row">
          <input type="checkbox">
          <span>我已向目标接收地址充值<em>30000</em>枚</span>
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
    return {}
  },
  methods: {
    toOrderConfirm () {
      console.log('click')
      this.$router.push('/candyRoom/candyOrderConfirm')
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
</style>
