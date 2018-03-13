<template>
  <div class="container buying-details" v-loading="loading">
    <!-- 币威 -->
    <div class="bitcv">
      <h4 v-if="language === 'cn'">{{isFinish ? '确认订单' : '订单详情'}}</h4>
      <h4 v-else>{{isFinish ? 'Confirm Order' : 'Order Detail'}}</h4>
      <div class="row">
        <div class="col-md-9">
          <div>
            <!-- <img src="/static/logo/bcv.png" alt="BitCV" height="60"> -->
            <img :src="orderData.projData.logoUrl" :alt="orderData.tokenSymbol" height="60">
            <b style="color: #333;">{{orderData.projData.tokenSymbol}}</b>
            <span>{{orderData.projData.tokenName}}</span>
          </div>
          <div class="row">
            <div class="col-md-3"><span>{{ $t('label.in_amount') }}：</span><b>{{orderData.orderAmount}}</b><i> {{ $t('label.coin_amount') }}</i></div>
            <div class="col-md-3"><span>{{ $t('label.lock') }}：</span><b>{{orderData.lockTime}}</b><i> {{ $t('label.day') }}</i></div>
            <div class="col-md-3"><span>{{ $t('label.return') }}：</span><b> {{getInterest(orderData.orderAmount, orderData.interestRate, orderData.lockTime)}}</b><i> {{ $t('label.coin_amount') }}</i></div>
          </div>
          <div>
            <span>{{ $t('label.re_address') }}：</span>
            <b class="bitcv-b">{{orderData.toAddr}}</b>
          </div>
          <div>
            <span>{{ $t('label.your_address') }}：</span>
            <b class="bitcv-b">{{orderData.fromAddr}}</b>
          </div>
        </div>
        <div class="col-md-3" style="text-align: center; padding-top:30px;">
          <vue-qr :text="qrTxt" height="160" width="160" :margin="10" class="qrcode"></vue-qr>
        </div>
      </div>
    </div>

    <!-- 内容 -->
    <div class="content">
      <div class="buying-details-form" v-if="!isFinish">
        <div class="form-group" :class="confirmError">
          <input type="checkbox" id="confirm" v-model="form.confirm">
          <label for="confirm">{{ $t('label.my_goal') }} {{orderData.orderAmount}} {{ $t('label.coin_amount') }}</label><br>
          <span v-if="confirmError">{{ $t('label.confirm_recharge') }}</span>
        </div>
        <div class="col-md-10 buying-details-form-submit">
          <button class="btn btn-warning" @click="handleSubmit">{{ $t('label.start_co') }}</button>
        </div>
      </div>
      <div v-else class="details-list">
        <p class="details-tip">
          <span class="update-btn" @click="fetch">{{ $t('label.refresh_data') }}</span>
          {{ $t('label.sys_notice') }}
        </p>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr class="text-dark">
                <th>{{ $t('label.jiaoyi') }}</th>
                <th>{{ $t('label.r_time') }}</th>
                <th>{{ $t('label.hash') }}</th>
                <th>{{ $t('label.operation') }}</th>
              </tr>
            </thead>
            <tbody v-if="list.length" >
              <tr v-for="item in list" :key="item.id">
                <td>{{ item.txAmount }}</td>
                <td>{{ item.txTime }}</td>
                <td><a :href="'https://etherscan.io/tx/' + item.txHash" target="_blank">{{ getShortStr(item.txHash, 12) }}</a></td>
                <td>
                  <input type="checkbox" v-model="item.checked" @change="handleChange">
                </td>
              </tr>
            </tbody>
            <div v-else class="nodata">
                {{ $t('label.no_t') }}<span class="update-btn" @click="fetch"> {{ $t('label.refresh_data') }}</span> {{ $t('label.retry') }}！
            </div>
          </table>
        </div>

        <div class="buying-details-form-submit">
          <button class="btn btn-warning" v-if="list.length" :disabled="isChecked" @click="handleFinish">{{ $t('label.co_co') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions} from 'vuex'
import VueQr from 'vue-qr'
export default {
  components: {
    VueQr
  },
  data () {
    return {
      loading: false,
      bitcv: {},
      orderData: {
        projData: {}
      },
      form: {
        confirm: ''
      },
      confirmError: '',
      isFinish: false,
      isChecked: true,
      list: [],
      qrTxt: '',
      txRecordIdList: []
    }
  },
  created () {
    this.orderId = this.$route.query.orderId
    this.bitcv = this.$route.query
    this.fetch()
    if (this.orderId) {
      this.fetchList(false)
      this.isFinish = true
    }
  },
  computed: {
    language () {
      return this.$i18n.locale
    }
  },
  methods: {
    ...mapActions(['getOrderDetail', 'getOrderTxRecordList', 'confirmDepositTx']),
    fetch () {
      this.loading = true
      this.getOrderDetail({depositOrderId: this.orderId || this.bitcv.id})
        .then((data = {}) => {
          this.loading = false
          this.orderData = data
          this.qrTxt = this.orderData.toAddr
        })
        .catch((err = '') => {
          this.loading = false
        })
    },
    fetchList () { // 获取交易列表
      this.loading = true
      this.getOrderTxRecordList({depositOrderId: this.orderId || this.bitcv.id})
        .then((data = {}) => {
          this.list = data.dataList
          this.list.length && this.list.map(item => {
            item.checked = false
          })
          this.isFinish = true
          this.loading = false
        })
        .catch((err = '') => {
          this.loading = false
        })
    },
    handleChange () {
      this.isChecked = true
      this.list.map(item => {
        if (item.checked) this.isChecked = false
      })
    },
    handleSubmit () {
      this.confirmError = ''
      if (!this.form.confirm) {
        this.confirmError = 'has-error'
      } else {
        this.fetchList()
      }
    },
    handleFinish () {
      this.loading = true
      this.list.map(item => {
        if (item.checked) {
          this.txRecordIdList.push(item.id)
        }
      })
      this.confirmDepositTx({depositOrderId: this.orderData.id, txRecordIdList: this.txRecordIdList})
        .then((data = {}) => {
          this.$router.push({
            path: '/candyRoom/candyMyData',
            query: this.bitcv
          })
          this.loading = false
        })
        .catch((err = '') => {
          this.loading = false
        })
    }
  }
}
</script>

<style lang="scss">
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
      }
      span{
        color: $gray-darker;
      }
      b{
        font-size: 18px;
        color: $primary-color;
        font-weight: 500;
      }
      .bitcv-b{
        font-size: 14px;
        word-wrap: break-word;
        word-break: normal;
        color: #333;
      }
      i{
        font-style: normal;
        font-size: 12px;
        color: $gray-darker;
        margin-left: 5px;
      }
    }
    .qrcode{
      width: 160px;
      height: 160px;
      img{
        width: 100%;
        height: 100%;
      }
    }
  }
  .content{
    background: #fff;
    padding: 25px;
    .table-responsive{
      border: 1px solid #ddd;
      border-left: 0;
      border-right: 0;
    }
    table{
      margin-bottom: 0;
    }
    .nodata{
      width: 170%;
      text-align: center;
      line-height: 50px;
    }
    .update-btn{
      color: #fd9801;
      cursor: pointer;
      &:hover{
        color: #ff6b34;
      }
    }
    .details-tip{
      color: $primary-color;
      text-align: center;
      font-size: 12px;
      overflow: hidden;
      span{
        float: left;
      }
    }
    .details-list{
      .buying-details-form-submit{
        text-align: center;
      }
    }
    .buying-details-form{
      overflow: hidden;
      label{
        font-weight: 500;
      }
      input{
        margin-right: 10px;
      }
      span{
        color: #a94442;
      }
    }
    .buying-details-form-submit{
      margin-top:15px;
      padding: 0;
      button{
        padding: 15px 35px;
        font-size: 16px;
        background: #fd9801;
      }
    }
  }
}
</style>
