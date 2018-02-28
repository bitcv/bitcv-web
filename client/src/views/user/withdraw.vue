<template>
  <div>
    <poptips v-model="showTips" message="提示：本次服务费由平台承担" style="top:51px;"></poptips>

    <div class="container">
      <p><br></p>
      <p><br></p>
      <p><br></p>
      <p><br></p>
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <form class="form-horizontal">
            <div class="form-group no-corner">
              <label class="control-label col-md-4">收款地址:</label>
              <div class="col-md-8">
                <input type="text" class="form-control" v-model="walletAddr">
              </div>
            </div>
            <div class="form-group no-corner">
              <label class="control-label col-md-4">输入手机号:</label>
              <div class="col-md-8">
                <div class="input-group">
                  <input type="text" class="form-control" v-model="mobile">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-gray-light" @click="getVcode">获取验证码<span v-show="timerId"> ({{ countDown }}s)</span></button>
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group no-corner">
              <label class="control-label col-md-4">输入手机验证码:</label>
              <div class="col-md-8">
                <input type="text" class="form-control" v-model="vcode">
              </div>
            </div>
            <p><br></p>
            <p><br></p>
            <div class="form-group">
              <div class="col-md-8 col-md-offset-4">
                <button type="button" class="btn btn-primary" @click="onSubmit" style="padding:6px 20px;">提交</button>
                <button type="button" class="btn btn-gray" @click="onCancel" style="margin-left:20px;padding:6px 20px;color:#fff;">取消</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapState} from 'vuex'
import Poptips from '@/components/poptips'

export default {
  components: {
    Poptips
  },
  computed: {
    ...mapState({
      id: state => state.route.params.id
    })
  },
  data () {
    return {
      showTips: true,
      walletAddr: '',
      mobile: '',
      vcode: '',
      timerId: '',
      disableSms: false,
      countDown: 60
    }
  },
  methods: {
    getVcode () {
      var mobileReg = new RegExp(/^0?(13|14|15|17|18)[0-9]{9}$/)
      if (!mobileReg.test(this.mobile)) {
        return alert('请填写正确手机号')
      }
      this.disableSms = true
      this.timerId = setInterval(() => {
        if (this.countDown <= 1) {
          clearInterval(this.timerId)
          this.disableSms = false
          this.timerId = ''
          this.countDown = 60
        } else {
          this.countDown--
        }
      }, 1000)
      this.$http.post('/api/getVcode', {
        mobile: this.mobile
      }).then(res => {
        if (res.data.errcode === 0) {
        } else {
          alert(res.data.errmsg)
        }
      }).catch(function (err) {
        console.log(err)
      })
    },
    onSubmit () {
      var mobileReg = new RegExp(/^0?(13|14|15|17|18)[0-9]{9}$/)
      if (!mobileReg.test(this.mobile)) {
        return alert('请填写正确手机号')
      }
      console.log(this.walletAddr)
      if (!/^0x[0-9a-f]{40}/i.test(this.walletAddr)) {
        return alert('请填写正确的收币钱包地址')
      }
      // 创建钱包
      this.$http.post('/api/addUserWallet', {
        mobile: this.mobile,
        vcode: this.vcode,
        tokenProtocol: '1',
        walletAddr: this.walletAddr
      }).then(res => {
        if (res.data.errcode === 0) {
          // 提币
          console.log('withdraw')
          this.$http.post('/api/withdraw', {
            assetId: this.$route.params.id
          }).then(res => {
            if (res.data.errcode === 0) {
              this.$router.push('/wallet')
            } else {
              alert(res.data.errmsg)
            }
          })
        } else {
          alert(res.data.errmsg)
        }
      })
    },
    onCancel () {
      this.$router.push('/wallet')
    }
  }
}
</script>
