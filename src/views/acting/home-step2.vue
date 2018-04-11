<template>
  <div class="step2" v-loading="loading">
    <div v-if="!isRecharge" v-loading="balanceLoad" class="confirm">
      <h5>确认发放</h5>
      <div class="confirm-box">
        <div class="confirm-content">
          <div class="confirm-top">
            <el-row>
              <el-col :span="12">
                <h6>发币总量</h6>
                <p>
                  <img :src="tokenData.logoUrl" alt="logo">
                  <b>{{orderData.totalAmount}}</b>
                  <small>{{tokenData.symbol}}</small>
                </p>
              </el-col>
              <el-col :span="12">
                <h6>手续费</h6>
                <p v-loading="feeLoad">
                  <img :src="feeTokenData.logoUrl" alt="logo">
                  <b>{{orderData.totalCount * feeTokenData.price}}</b>
                  <small>
                    <el-radio-group v-model="curFeeIndex">
                      <el-radio :label="index" v-for="(feeToken, index) in feeTokenList">{{feeToken.symbol}}</el-radio>
                    </el-radio-group>
                  </small>
                </p>
              </el-col>
            </el-row>
          </div>
          <div class="confirm-line"></div>
          <div class="confirm-bottom">
            <el-row>
              <el-col :span="12">
                <h6>可用{{tokenData.symbol}}余额</h6>
                <p>
                  <img :src="tokenData.logoUrl" alt="logo">
                  <span>{{tokenData.amount}}</span>
                  <small>枚</small>
                  <br>
                  <i v-if="tokenNeeded > 0">余额不足，请先充值</i>
                </p>
              </el-col>
              <el-col :span="12" v-if="!sameToken">
                <h6>可用{{feeTokenData.symbol}}余额</h6>
                <p>
                  <img :src="feeTokenData.logoUrl" alt="logo">
                  <span>{{feeTokenData.amount}}</span>
                  <small>
                    枚
                  </small>
                  <br>
                  <i v-if="feeNeeded > 0">余额不足，请先充值</i>
                </p>
              </el-col>
            </el-row>
          </div>
        </div>
        <footer class="confirm-footer">
          <el-button type="warning" :disabled="false && tokenNeeded === 0 && feeNeeded === 0" @click="toDeposit">去充值</el-button>
          <el-button type="warning" :disabled="tokenNeeded > 0 || feeNeeded > 0"  @click="handleConfirm">确认发放</el-button>
        </footer>
      </div>
    </div>

    <div v-else>
      <div class="recharge-top" v-loading="rechareLoad">
        <el-row>
          <el-col :span="18" class="left">
            <h5>充值地址</h5>
            <div>
              <el-input v-model="walletAddr" readonly ref="copyInput"></el-input>
              <i class="el-icon-document" @click="handleCopy"></i>
            </div>
            <p style="width:497px;">
            温馨提示：请勿向上述地址充值任何非BCV资产，否则资产将不可找回。您充值至上述地址后，需要整个网络节点的确认，1次网络确认后到账，6次网络确认后可提币。<span>单笔充值不得低于0.003{{tokenData.symbol}}</span>，我们不会处理少于该金额的充值要求。
            </p>
          </el-col>
          <el-col :span="6" class="right">
            <vue-qr :text="walletAddr" :margin="10" class="qrcode"></vue-qr>
            <p>或扫二维码立即充值</p>
          </el-col>
        </el-row>
      </div>

      <div class="recharge-bottom" v-loading="balanceLoad">
        <h5>
          资产余额<i class="el-icon-refresh" @click="handleRefresh"></i>
        </h5>
        <el-row>
          <el-col :span="8">
            <span>{{tokenData.symbol}}</span>
          </el-col>
          <el-col :span="8">
            <span>{{tokenData.amount}}</span>
          </el-col>
          <el-col :span="8" v-if="tokenNeeded > 0">
            <span style="display:inline;font-size:12px;color:rgba(255,1,1,1);">还需充值</span>
            <span>{{tokenNeeded}}</span>
          </el-col>
          <el-col :span="8" v-else>
            <img src="/static/img/完成充值@2x.png" style="width:16px;height:16px;" alt="">
            <span style="display:inline;font-size:12px;color:#67C23A;">充值完成</span>
          </el-col>
        </el-row>
        <el-row v-if="!sameToken">
          <el-col :span="8">
            <span>{{feeTokenData.symbol}}</span>
          </el-col>
          <el-col :span="8">
            <span>{{feeTokenData.amount}}</span>
          </el-col>
          <el-col :span="8" v-if="feeNeeded > 0">
            <span style="display:inline;font-size:12px;color:rgba(255,1,1,1);">还需充值</span>
            <span style="font-weight:bold">{{feeNeeded}}</span>
          </el-col>
          <el-col :span="8" v-else>
            <img src="/static/img/完成充值@2x.png" style="width:16px;height:16px;" alt="">
            <span style="display:inline;font-size:12px;color:#67C23A;">充值完成</span>
          </el-col>
        </el-row>
      </div>

      <footer class="recharge-footer">
        <el-button type="warning" class="btn-primary" @click="handleFinished">充值完成</el-button>
      </footer>
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
  props: {
    orderData: Object
  },
  data () {
    return {
      isRecharge: false, // 是否确认充值
      tokenProtocol: 1,
      assetList: [],
      feeTokenList: [],
      curFeeIndex: 0,
      walletAddr: '',
      balanceLoad: false,
      rechareLoad: false,
      feeLoad: false,
      loading: false
    }
  },
  mounted () {
    this.fetchBalance()
    this.getFeeList()
  },
  computed: {
    sameToken () {
      return this.tokenData.tokenId === this.feeTokenData.tokenId
    },
    tokenData () {
      console.log('orderData')
      console.log(this.orderData)
      let tokenData = {
        tokenId: this.orderData.tokenId,
        symbol: this.orderData.tokenSymbol,
        logoUrl: this.orderData.logoUrl,
        amount: 0
      }
      this.assetList.forEach(item => {
        if (item.tokenId === this.orderData.tokenId) {
          tokenData.amount = item.amount
        }
      }, this)
      console.log('tokenData')
      console.log(tokenData)
      return tokenData
    },
    feeTokenData () {
      let tokenData = this.feeTokenList[this.curFeeIndex] || {}
      tokenData.amount = 0
      this.assetList.forEach(item => {
        if (item.tokenId === tokenData.tokenId) {
          tokenData.amount = item.amount
        }
      })
      console.log('feeTokenData')
      console.log(tokenData)
      return tokenData
    },
    feeNeeded () {
      let diffAmount = this.orderData.totalCount * this.feeTokenData.price - this.feeTokenData.amount
      return diffAmount > 0 ? diffAmount : 0
    },
    tokenNeeded () {
      let diffAmount = 0
      if (this.sameToken) {
        diffAmount = this.orderData.totalAmount - this.tokenData.amount - this.feeTokenData.amount
      } else {
        diffAmount = this.orderData.totalAmount - this.tokenData.amount
      }
      return diffAmount > 0 ? diffAmount : 0
    }
  },
  methods: {
    ...mapActions(['getDispenseBalance', 'getDispenseWallet', 'confirmDispense', 'getDispenseFee']),
    fetchBalance () {
      this.balanceLoad = true
      this.getDispenseBalance().then(res => {
        this.assetList = res.dataList
        this.balanceLoad = false
      }).catch(() => {
        this.balanceLoad = false
      })
    },
    getFeeList () {
      this.feeLoad = true
      this.getDispenseFee({
        discountCode: ''
      }).then(data => {
        this.feeTokenList = data
        this.feeLoad = false
      }).catch(() => {
        this.feeLoad = false
      })
    },
    toDeposit () {
      this.rechargeLoad = true
      this.getDispenseWallet({
        tokenProtocol: this.tokenProtocol
      }).then((data = {}) => {
        this.walletAddr = data.walletAddr
        this.isRecharge = true
        this.rechargeLoad = false
      })
    },
    handleConfirm () {
      this.loading = true
      this.confirmDispense({
        feeSymbol: this.feeTokenData.symbol
      }).then(data => {
        this.loading = false
        this.$emit('finished', {
          taskId: data.taskId
        })
      }).catch(() => {
        this.loading = false
      })
    },
    handleCopy () {
      let eInput = this.$refs.copyInput.$el.firstElementChild
      eInput.select()
      document.execCommand('Copy')
      this.$message.success('复制成功!')
    },
    handleRefresh () {
      this.fetchBalance()
    },
    handleFinished () {
      this.fetchBalance()
      this.isRecharge = false
    }
  }
}
</script>

<style lang="scss">
@import '~@/styles/variables';
.step2{
  padding: 80px 0;
  .confirm{
    .confirm-box{
      width: 80%;
      margin: 0 10%;
      border-top: 2px solid $primary-color;
      padding: 0 20px;
    }
    .confirm-content{
      background-color: #fcf7f1;
      border-radius: 5px;
      // line-height: 40px;
      h6{
        color: $gray-dark;
        font-size: 14px;
      }
      img{
        width: 20px;
        border-radius: 50%;
        vertical-align: baseline;
      }
      small{
        font-size: 12px;
      }
    }
    .confirm-line{
      height: 1px;
      border-top: 1px dashed $primary-color;
      position: relative;
      &::before, &::after{
        content: ' ';
        width: 20px;
        height: 20px;
        background: #fff;
        border-radius: 50%;
        position: absolute;
        top: -10px;
        left: -10px;
      }
      &::after{
        left: calc(100% - 10px);
      }
    }
    .confirm-top{
      padding: 30px 50px;
      b{
        font-size: 28px;
        color: $primary-color;
        font-weight: 200;
      }
      .el-radio-group{
        margin-left: 10px;
      }
      .el-radio+.el-radio{
        margin-left: 10px;
      }
      .el-radio__label{
        font-size: 12px;
      }
    }
    .confirm-bottom{
      padding: 20px 50px;
      span{
        font-size: 20px;
      }
      img{
        vertical-align: sub;
      }
      i{
        font-size: 12px;
        font-style: normal;
        color: salmon;
      }
    }
    .confirm-footer{
      margin: 30px 0;
      text-align: center;
    }
  }

  .recharge-top{
    .left{
      & > div{
        padding: 30px;
        overflow: hidden;
        .el-input{
          width: 80%;
          float: left;
        }
        span, input{
          background: #fcf7f1;
          border: 1px solid $primary-color;
          height: 40px;
          line-height: 40px;
          padding: 0 20px;
        }
        i{
          color: $primary-color;
          font-size: 30px;
          float: left;
          margin-left: 10px;
          margin-top: 10px;
          cursor: pointer;
        }
      }
      p{
        padding: 0 0 30px 30px;
        margin-top: 10px;
        color: $gray;
        font-size: 12px;
        span{
          color: salmon;
        }
      }
    }
    .right{
      .qrcode, .qrcode img{
        width: 120px;
        height: 120px;
      }
      p{
        font-size: 12px;
        color: #ccc;
        line-height: 40px;
      }
    }
  }
  .recharge-bottom{
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    .el-row{
      padding: 30px;
    }
    h5{
      margin-top: 50px;
      i{
        margin-left: 10px;
        padding: 3px;
        background: #fcf7f1;
        border: 1px solid $primary-color;
        color: $primary-color;
      }
    }
    span{
      font-size: 16px;
      color: rgba(51,51,51,1);
    }
  }
  .recharge-footer{
    text-align: right;
    padding: 20px;
  }
}
@media (max-width: 767px) {
  .step2{
    padding: 10px 0;
    .confirm{
      .confirm-box{
        width: 90%;
        margin: 0 5%;
        padding: 0;
      }
      .confirm-top{
        padding: 20px 10px;
        b{
          font-size: 20px;
        }
        .el-radio-group{
          margin-left: 0;
        }
        p{
          margin: 0;
        }
      }
      .confirm-bottom{
        padding: 15px 10px;
      }
    }
    .recharge-top{
      .left{
        width: 100%;
        p{
          padding: 0;
        }
        div{
          padding: 0px;
        }
      }
      .right{
        width: 100%;
        text-align: center;
        .qrcode{
          margin: 0 auto;
        }
      }
    }
    .recharge-bottom{
      .el-row{
        padding: 10px 0;
      }
    }
  }
}
</style>
