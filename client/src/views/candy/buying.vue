<template>
  <div class="container buying">

    <!-- 路径导航 -->
    <ol class="breadcrumb">
      <li class="active">
        <router-link :to="{path: '/candyRoom/candyList'}">余币宝</router-link>
      </li>
      <li>抢购额度</li>
    </ol>

    <!-- 币威 -->
    <div class="row bitcv">
      <div class="col-xs-3 col-md-3">
        <img :src="bitcv.logoUrl" alt="BitCV" height="60">
        <div class="clear">&nbsp;</div>
        <b>{{bitcv.tokenSymbol}}<span>{{bitcv.nameCn}}</span></b>
      </div>
      <div class="col-xs-3 col-md-3">
        <b>{{bitcv.remainAmount}}</b>
        <p>剩余额度</p>
      </div>
      <div class="col-xs-3 col-md-3">
        <b>{{bitcv.minAmount}}</b>
        <p>起始额度</p>
      </div>
      <div class="col-xs-3 col-md-3">
        <b>{{bitcv.lockTime}}<span>个月</span></b>
        <p>锁仓期</p>
      </div>
    </div>

    <!-- 内容 -->
    <div class="content">
      <div class="form-inline row buying-form">
        <div class="form-group col-md-4" :class="numberError">
           <label for="number">您要充值的数量？</label>
          <input type="number" class="form-control" id="number" placeholder="请输入" min="1" v-model="form.number">
          <span v-if="numberError">下单数量必须大于起始额度</span>
        </div>
        <div class="col-md-2">——></div>
        <div class="form-group col-md-4">
           <label for="report">余币宝回报</label>
          <input type="number" class="form-control" id="report" placeholder="请输入" min="1" readonly :value="getInterest(form.number, bitcv.interestRate, bitcv.lockTime)">
        </div>
        <div class="col-md-10 buying-form-submit">
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
        number: ''
      },
      numberError: ''
    }
  },
  created () {
    this.fetch()
  },
  methods: {
    fetch () {
      this.bitcv = this.$route.query
      this.form.number = this.bitcv.minAmount
    },
    handleSubmit () {
      this.numberError = ''
      if (this.form.number < parseInt(this.bitcv.minAmount)) {
        this.numberError = 'has-error'
      } else {
        this.bitcv.number = this.form.number
        this.bitcv.report = this.getInterest(this.form.number, this.bitcv.interestRate, this.bitcv.lockTime)
        this.$router.push({
          path: '/candyRoom/candyOrder',
          query: this.bitcv
        })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~@/styles/variables';
.buying{
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
        .clear{
          height: 0;
          display: none;
        }
        span{
          font-weight: 400;
        }
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
      color: $primary-color;
    }
    .buying-label{
      margin-bottom: 15px;
    }
    .buying-form{
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
    .buying-form-submit{
      margin-top:15px;
      button{
        padding: 15px 35px;
        font-size: 16px;
        background: #fd9801;
      }
    }
  }
  @media screen and (max-width: 767px) {
    .bitcv{
      .col-md-3{
        padding: 0;
        text-align: center;
        p{
          font-size: 12px;
          margin-top: 10px;
        }
        &:nth-child(1){
          .clear{
            display: block;
          }
          img{
            height: 40px;
          }
          padding-left: 0;
          font-size: 12px;
        }
      }
    }
    .content{
      .col-md-2{
        line-height: 20px;
      }
    }
  }
}
</style>
