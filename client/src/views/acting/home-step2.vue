<template>
  <div class="step2">
    <div v-if="!isRecharge" class="confirm">
      <h5>确认发放</h5>
      <div class="confirm-box">
        <div class="confirm-content">
          <div class="confirm-top">
            <el-row>
              <el-col :span="12">
                <h6>发币总量</h6>
                <p>
                  <img src="/static/logo/bcv.png" alt="logo">
                  <b>{{info.total}}</b>
                  <small>{{['ETH', 'BCV'][info.itype]}}</small>
                </p>
              </el-col>
              <el-col :span="12">
                <h6>手续费</h6>
                <p>
                  <img src="/static/logo/bcv.png" alt="logo">
                  <b>{{info.cost}}</b>
                  <small>
                    <el-radio-group v-model="info.type" @change="handleChange">
                      <el-radio :label="0">ETH</el-radio>
                      <el-radio :label="1">BCV</el-radio>
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
                <h6>可用BCV余额</h6>
                <p>
                  <img src="/static/logo/bcv.png" alt="logo">
                  <span>{{info.BCVnumber}}</span>
                  <small>枚</small>
                  <br>
                  <i v-if="info.total > info.BCVnumber">余额不足，请先充值</i>
                </p>
              </el-col>
              <el-col :span="12">
                <h6>可用ETH余额</h6>
                <p>
                  <img src="/static/logo/bcv.png" alt="logo">
                  <span>{{info.ETHnumber}}</span>
                  <small>
                    枚
                  </small>
                  <br>
                  <i v-if="info.cost > info.ETHnumber">余额不足，请先充值</i>
                </p>
              </el-col>
            </el-row>
          </div>
        </div>
        <footer class="confirm-footer">
          <el-button type="warning" plain @click="handleRecharge">去充值</el-button>
          <el-button type="warning" class="btn-primary" @click="handleConfirm">确认发放</el-button>
        </footer>
      </div>
    </div>

    <div v-else>
      <div class="recharge-top">
        <el-row>
          <el-col :span="18" class="left">
            <h5>充值地址</h5>
            <div>
              <el-input v-model="recData.address" readonly ref="copyInput"></el-input>
              <i class="el-icon-document" @click="handleCopy"></i>
            </div>
            <p>
              提示：<span>单笔充值不得低于0.003BCV</span>，
              我们不会处理少于该金额的BCV充值要求。
            </p>
          </el-col>
          <el-col :span="6" class="right">
            <vue-qr :text="recData.address || ''" :margin="10" class="qrcode"></vue-qr>
            <p>或扫二维码立即充值</p>
          </el-col>
        </el-row>
      </div>

      <div class="recharge-bottom">
        <h5>
          资产余额<i class="el-icon-refresh" @click="handleRefresh"></i>
        </h5>
        <el-row>
          <el-col :span="8">
            <span>{{assets.BVC}}</span>
            <small>BVC</small>
          </el-col>
          <el-col :span="8">
            <span>{{assets.EHT}}</span>
            <small>EHT</small>
          </el-col>
          <el-col :span="8">
            <span>{{assets.BTC}}</span>
            <small>BTC</small>
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
import {mapState} from 'vuex'
import VueQr from 'vue-qr'
import bus from '@/utils/bus'
export default {
  components: {
    VueQr
  },
  data () {
    return {
      isRecharge: false, // 是否确认充值
      info: {
        total: 30000,
        cost: 1699999,
        type: 0,
        itype: 1,
        BCVnumber: 5000,
        ETHnumber: 6000
      },
      recData: { // 充值数据
        address: '0xsajaiaksoalksidujh8093jd84kd9s0a9djhvu87'
      },
      assets: { // 资产余额
        BVC: 2000,
        EHT: 378,
        BTC: 288
      }
    }
  },
  updated () {
    console.log('updated')
  },
  methods: {
    handleChange (val) {
      this.info.itype = val === 0 ? 1 : 0
    },
    handleRecharge () {
      let that = this
      bus.$on('handleEmit', (data) => {
        if (data) that.recData = data
      })
      this.isRecharge = true
    },
    handleConfirm () {
      this.$emit('finished')
    },
    handleCopy () {
      let eInput = this.$refs.copyInput.$el.firstElementChild
      eInput.select()
      document.execCommand('Copy')
      this.$message.success('复制成功!')
    },
    handleRefresh () {
      console.log('刷新')
    },
    handleFinished () {
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
      font-size: 28px;
      color: $primary-color;
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
