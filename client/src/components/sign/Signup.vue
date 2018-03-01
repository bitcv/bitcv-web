<template>
  <div class="container signup-container">
    <div class="signup">
      <h3 class="panel-title center-title">欢迎注册 BCV</h3>
        <!--<span class="prompt">链币会员可直接使用会员名登录</span>-->
        <form>
          <template>
            <div class='row'>
              <div class='col'>
                <el-select class='phone-suffix' v-model="selected" placeholder="请选择">
                  <el-option value="65">新加坡 (+65)</el-option>
                  <el-option value="1">美国 (+1)</el-option>
                  <el-option value="61">澳大利亚 (+61)</el-option>
                  <el-option value="81">日本 (+81)</el-option>
                  <el-option value="82">韩国 (+82)</el-option>
                  <el-option value="86">中国 (+86)</el-option>
                  <el-option value="852">香港  (SAR) (+852)</el-option>
                  <el-option value="853">澳门 (+853)</el-option>
                  <el-option value="856">老挝 (+856)</el-option>
                </el-select>
              </div>
              <div class='col'>
                <input class='phone' v-model="mobile" type="text" placeholder="手机号">
              </div>

            </div>
          </template>
          <div class = smspanel>
          <input class = "sms-input" v-model="vcode" type="text" placeholder="短信验证码" >
          <el-button :disabled="disableSms" class="sms-btn" :class="{disabled : disableSms}" type="primary" @click="getVcode">发送验证码<span v-show="timerId"> ({{ countDown }}s)</span></el-button>
          </div>
          <input v-model="passwd" type="password" placeholder="密码">
          <button class="signup-btn" @click.prevent="signup">注册</button>
        </form>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      mobile: '',
      passwd: '',
      vcode: '',
      checked: true,
      timerId: '',
      disableSms: false,
      countDown: 60,
      selected: '65'
    }
  },
  methods: {
    signup () {
      var mobileReg = new RegExp(/^0?(13|14|15|17|18)[0-9]{9}$/)
      if (!this.selected) {
        return alert('请选择手机国家号')
      }
      if (!mobileReg.test(this.mobile)) {
        return alert('请填写正确手机号')
      }
      if (this.passwd.length < 6) {
        return alert('密码长度至少需要6位')
      }
      if (!this.vcode) {
        return alert('验证码不能为空')
      }
      this.$http.post('/api/signup', {
        mobile: this.mobile,
        passwd: this.passwd,
        vcode: this.vcode,
        nation: this.selected
      }).then((res) => {
        var resData = res.data
        if (resData.errcode === 0) {
          this.$router.push('/signin')
        } else {
          alert(resData.errmsg)
        }
      }).catch(function (err) {
        console.log(err)
      })
    },
    getVcode () {
      var mobileReg = new RegExp(/^0?(13|14|15|16|17|18)[0-9]{9}$/)
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
      }).then(function (res) {
        var resData = res.data
        if (resData.errcode === 0) {
        } else {
          alert(resData.errmsg)
        }
      }).catch(function (err) {
        console.log(err)
      })
    },
    change () {
      this.checked = !this.checked
    }
  }
}
</script>

<style lang="scss">
.signup-container {
  .signup {
    box-sizing: border-box;
    max-width: 530px;
    width: 100%;
    background-color: #FFF;
    box-sizing: border-box;
    padding: 20px;
    position: relative;
    margin: 0 auto;
    font-size: 0;
    .panel-title {
      font-size: 30px;
      margin: 12px 0 32px;
    }
    form {
      display: flex;
      flex-direction: column;
      justify-content: space-around;
      align-items: center;
      width: 100%;
      // height: 439px;
      margin-bottom: 25px;
      input {
        display: block;
        box-sizing: border-box;
        width: 100%;
        max-width: 426px;
        height: 50px;
        border: 1px solid #4A4A4A;
        padding: 0 20px;
        font-size: 14px;
        margin-bottom: 15px;
        &:focus {
          border: 1px solid #FFCF81;
        }
      }
      .smspanel{
        display: flex;
        max-width: 426px;
        width: 100%;
        .sms-btn {
          display: block;
          box-sizing: border-box;
          width: 145px;
          height: 50px;
          border: 1px solid #4A4A4A;
          border-radius: 0;
          margin-left: 5px;
          text-align: center;
          color: #FFCF81;
          font-size: 16px;
          line-height: 25px;
          background-color: #000;
          padding: 0;
          &.disabled {
            background-color: #909399;
            color: #FFF;
          }
        }
        .sms-input {
          height: 50px;
          width: calc(100% - 150px);
        }
      }
      .signup-btn {
        max-width: 426px;
        width: 100%;
        height: 50px;
        text-align: center;
        color: #FFCF81;
        font-size: 18px;
        line-height: 25px;
        background-color: #000;
      }
      .protocl {
        width: 182px;
        height: 20px;
        font-size: 14px;
        font-family: PingFangSC-Regular;
        color: rgba(155,155,155,1);
        line-height: 20px;
        margin-left: 4px;
        a {
          color: #FFCF81;
        }
      }
    }
    .btn-area {
      position: absolute;
      right: 52px;
      bottom: 33px;
      font-size: 14px;
      color: #9B9B9B;
      >a {
        margin-left: 38px;
      }
    }
  }
  .row{
    width:100%;max-width:426px;
    .col{width:50%;float:left;}
  }
  .phone-suffix .el-input__inner{height:50px;}
}
</style>
