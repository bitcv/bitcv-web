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
          <b>{{bitcv.lockTime}}</b>
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
        <div>
          <p>343443dsdsfdfdsdsfdf</p>
          <p>此地址为项目方与平台共同认可的资金存管地址，回报已入账，请放心充值</p>
        </div>
      </div>
      <div class="buying-address-form">
        <div class="form-group" :class="addressError">
           <label for="address">您的支出地址</label>
          <input type="text" class="form-control" id="address" placeholder="请输入支出地址" v-model="form.address">
          <span v-if="addressError">请填写余币宝回报</span>
        </div>
        <div class="col-md-10 buying-address-form-submit">
          <button class="btn btn-warning" @click="handleSubmit">立即下单</button>
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
      this.numberError = ''
      this.addressError = ''
      if (!this.form.number) {
        this.numberError = 'has-error'
        return
      }
      if (!this.form.address) {
        this.addressError = 'has-error'
        return
      }
      if (this.form.number && this.form.money) {
        // this.numberError = ''
        // this.moneyError = ''
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~@/styles/variables';
.buying-address{
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
    padding: 30px 0 25px 0;
    box-sizing: border-box;
    .col-md-3{
      padding-top: 10px;
      &:nth-child(1){
        padding-left: 40px;
        padding-top: 0;
      }
      &:nth-child(2), &:nth-child(3), &:nth-child(4){
        b{
          font-weight: 500;
          font-size: 20px;
          span{
            font-size: 14px;
            color: $gray;
          }
        }
        p{
          color: $gray;
        }
      }
      &:nth-child(2){
        b{
          color: $primary-color;
        }
      }
    }
  }
  .content{
    background: #fff;
    padding: 40px 48px;
    .col-md-4, .col-md-10{
      padding: 0;
    }
    .col-md-2{
      text-align: center;
      line-height: 90px;
    }
    .buying-address-label{
      margin-bottom: 15px;
    }
    .buying-address-form{
      label{
        font-weight: normal;
        color: #7d7d7d;
      }
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
      margin-top:30px;
      button{
        padding: 15px 35px;
        font-size: 16px;
      }
    }
  }
}
</style>
