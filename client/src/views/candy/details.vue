<template>
  <div class="container buying-details">
    <!-- 币威 -->
    <div class="bitcv">
      <h4>{{isFinish ? '确认订单' : '订单详情'}}</h4>
      <div class="row">
        <div class="col-md-9">
          <div>
            <img src="/static/logo/bcv.png" alt="BitCV" height="60">
            <!-- <img :src="bitcv.logoUrl" alt="BitCV" height="60"> -->
            <b>币威</b>
            <span>_BitCV</span>
          </div>
          <div class="row">
            <div class="col-md-3"><span>充值数量：</span><b>{{bitcv.number}}</b><i>枚</i></div>
            <div class="col-md-3"><span>锁仓期：</span><b>{{bitcv.lockTime}}</b><i>个月</i></div>
            <div class="col-md-3"><span>回报：</span><b>{{bitcv.money}}</b><i>枚</i></div>
          </div>
          <div>
            <span>接收地址：</span>
            asshsdaj2823393289huihui
          </div>
          <div>
            <span>您的地址：</span>
            asshsdaj2823393289huihui
          </div>
        </div>
        <div class="col-md-3" style="text-align: center; padding-top:50px;">
          <img src="/static/logo/qrcode.png" height="150" />
        </div>
      </div>
    </div>

    <!-- 内容 -->
    <div class="content">
      <div class="buying-details-form" v-if="!isFinish">
        <div class="form-group" :class="confirmError">
          <input type="checkbox" v-model="form.confirm">我已向目标接收地址充值{{bitcv.number}}枚<br>
          <span v-if="confirmError">请确认充值数量</span>
        </div>
        <div class="col-md-10 buying-details-form-submit">
          <button class="btn btn-warning" @click="handleSubmit">开始确认</button>
        </div>
      </div>
      <div v-else class="details-list">
        <p class="details-tip">以下为系统自动检测到的交易记录，请勾选此笔订单相关的交易记录进行确认！</p>
        <table class="table table-hover">
          <thead>
            <tr class="text-dark">
              <th>交易数量</th>
              <th>交易时间</th>
              <th>交易哈希</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in list" :key="item.id">
              <td>{{ item.number }}</td>
              <td>{{ item.time }}</td>
              <td>{{ item.hash }}</td>
              <td>
                <input type="checkbox" v-model="item.checked" @change="handleChange">
              </td>
            </tr>
          </tbody>
        </table>

        <div class="buying-details-form-submit">
          <button class="btn btn-warning" :disabled="isChecked" @click="handleFinish">确认完成</button>
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
        confirm: ''
      },
      confirmError: '',
      isFinish: false,
      isChecked: true,
      list: [{
        number: 20000,
        time: new Date(),
        hash: '7897897adsdasasdasads8798',
        checked: false
      }]
    }
  },
  created () {
    this.fetch()
  },
  methods: {
    fetch () {
      this.bitcv = this.$route.query
    },
    handleChange () {
      this.isChecked = true
      this.list.map(item => {
        if (item.checked) {
          this.isChecked = false
        }
      })
    },
    handleSubmit () {
      this.confirmError = ''
      if (!this.form.confirm) {
        this.confirmError = 'has-error'
      } else {
        this.isFinish = true
      }
    },
    handleFinish () {
      console.log('确认完成')
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~@/styles/variables';
.buying-details{
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
    h4{
      border-bottom: 1px solid $primary-color;
      padding-bottom: 10px;
    }
    .col-md-3{
      padding: 0;
    }
    div{
      padding-top: 20px;
      &:nth-child(1){
        padding-top: 0;
        b{
          color: #333;
        }
      }
      span{
        color: $gray-dark;
      }
      b{
        font-size: 18px;
        color: $primary-color;
        font-weight: 500;
      }
      i{
        font-style: normal;
        font-size: 12px;
        color: $gray-dark;
      }
    }
  }
  .content{
    background: #fff;
    padding: 25px;
    .details-tip{
      color: $primary-color;
      text-align: center;
      font-size: 12px;
    }
    .details-list{
      .buying-details-form-submit{
        text-align: center;
      }
      .table{
        border: 1px solid #ddd;
        border-left: 0;
        border-right: 0;
      }
    }
    .buying-details-form{
      overflow: hidden;
      input{
        margin-right: 10px;
      }
      span{
        color: #a94442;
      }
    }
    .buying-details-form-submit{
      margin-top:20px;
      button{
        padding: 15px 35px;
        font-size: 16px;
      }
    }
  }
}
</style>
