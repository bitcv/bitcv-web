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
          <b>{{bitcv.report}}</b>
          枚
        </div>
      </div>
    </div>

    <!-- 内容 -->
    <div class="content">
      <div>
        <h4>充值接收地址</h4>
        <div class="buying-address-adr">
          <p>{{bitcv.toAddr}}</p>
          <p>此地址为项目方与平台共同认可的资金存管地址，回报已入账，请放心充值</p>
        </div>
      </div>
      <h4>您的支出地址</h4>
      <div class="buying-address-form">
        <div class="form-group" :class="addressError">
          <input type="text" class="form-control" id="address" placeholder="请输入支出地址" v-model="form.address">
          <span v-if="addressError">{{errorTip}}</span>
        </div>
        <div class="col-md-10 buying-address-form-submit">
          <button class="btn btn-warning" @click="handleSubmit">提交订单</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions} from 'vuex'
export default {
  data () {
    return {
      bitcv: {},
      form: {
        address: ''
      },
      addressError: '',
      errorTip: '请输入正确的支付钱包地址'
    }
  },
  created () {
    this.fetch()
  },
  computed: {
    params () {
      return {
        depositBoxId: this.bitcv.id,
        orderAmount: this.bitcv.number + '',
        fromAddr: this.form.address
      }
    }
  },
  methods: {
    ...mapActions(['postCandyOrder']),
    fetch () {
      this.bitcv = this.$route.query
    },
    handleSubmit () {
      this.addressError = ''
      if (!this.form.address) {
        this.addressError = 'has-error'
        return
      }
      if (this.form.address.indexOf('0x') !== 0 || this.form.address.length !== 42 || this.form.address === this.bitcv.toAddr) {
        this.addressError = 'has-error'
      } else {
        this.postCandyOrder(this.params)
          .then((data = {}) => {
            this.bitcv.address = this.form.address
            this.bitcv.id = data.id
            this.$router.push({
              path: '/candyRoom/candyDetails',
              query: this.bitcv
            })
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
    padding: 25px 30px;
    box-sizing: border-box;
    .col-md-5{
      padding-top: 10px;
      b{
        font-size: 24px;
        color: $primary-color;
      }
    }
  }
  .content{
    background: #fff;
    padding: 35px 30px;
    .buying-address-adr{
      padding: 30px 40px;
      p{
        word-wrap: break-word;
        word-break: normal;
        &:nth-child(2){
          color: $primary-color;
          font-size: 12px;
          font-weight: 500;
          margin-top: 20px;
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
      margin-top:15px;
      padding: 0;
      button{
        padding: 15px 35px;
        font-size: 16px;
        background: #fd9801;
      }
    }
  }
  @media screen and (max-width: 767px) {
    .content{
      .buying-address-adr{
        padding: 10px;
      }
      .buying-address-form{
        padding-left: 10px;
      }
    }
  }
}
</style>
