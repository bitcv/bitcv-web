<template>
  <div class="container buying-address">

    <!-- 路径导航 -->
    <ol class="breadcrumb">
      <li class="active">
        <router-link :to="{path: '/candyRoom/candyList'}">余币宝</router-link>
      </li>
      <li>地址设置</li>
    </ol>

    <!-- 币威 -->
    <div class="bitcv">
      <h4>抢购详情</h4>
      <div class="row">
        <div class="col-md-5">
          充值数量：<b>{{bitcv.number}}</b>枚
        </div>

        <div class="col-md-5">
          <b>{{bitcv.lockTime}}9</b>
          个月后回报
          <b>{{bitcv.money}}</b>
          枚
        </div>
      </div>
    </div>

    <!-- 内容 -->
    <div class="content">
      <div>
        <h4>充值接收地址</h4>
        <div class="buying-address-adr">
          <p>343443dsdsfdfdsdsfdf</p>
          <p>此地址为项目方与平台共同认可的资金存管地址，回报已入账，请放心充值</p>
        </div>
      </div>
      <h4>您的支出地址</h4>
      <div class="buying-address-form">
        <div class="form-group" :class="addressError">
          <input type="text" class="form-control" id="address" placeholder="请输入支出地址" v-model="form.address">
          <span v-if="addressError">请填写支出地址</span>
        </div>
        <div class="col-md-10 buying-address-form-submit">
          <button class="btn btn-warning" @click="handleSubmit">提交订单</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      bitcv: {},
      form: {
        address: ''
      },
      addressError: ''
    }
  },
  created () {
    this.fetch()
  },
  methods: {
    fetch () {
      this.bitcv = this.$route.query
    },
    handleSubmit () {
      console.log(111)
      this.addressError = ''
      if (!this.form.address) {
        this.addressError = 'has-error'
        return
      }
      if (this.form.address) {
        this.$router.push({
          path: '/candyRoom/candyDetails',
          query: {
            address: this.form.address,
            lockTime: this.bitcv.lockTime,
            number: this.bitcv.number,
            money: this.bitcv.money
          }
        })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~@/styles/variables';
.buying-address{
  h4{
    font-size: 16px;
    color: #333;
  }
  .row{
    margin: 0;
  }
  .breadcrumb{
    background: transparent;
    &>li+li:before{
     content: '>';
    }
  }
  .bitcv{
    background: #fcf7f1;
    padding: 25px;
    box-sizing: border-box;
    .col-md-5{
      padding-top: 10px;
      b{
        font-size: 18px;
        color: $primary-color;
      }
    }
  }
  .content{
    background: #fff;
    padding: 25px;
    .buying-address-adr{
      padding: 20px 50px;
      p{
        &:nth-child(2){
          color: $primary-color;
          font-size: 12px;
          font-weight: 500;
        }
      }
    }
    .buying-address-form{
      overflow: hidden;
      padding-left: 50px;
      input{
        width: 100%;
        height: 50px;
        line-height: 50px;
        margin-top: 15px;
        padding-left: 15px;
        padding-right: 0;
      }
      span{
        color: #a94442;
      }
    }
    .buying-address-form-submit{
      margin-top:20px;
      button{
        padding: 15px 35px;
        font-size: 16px;
      }
    }
  }
}
</style>
