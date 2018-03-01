<template>
  <div class="container signup-container">
    <div class="findpwd">
      <h3 class = "panel-title center-title">重置密码</h3>
        <form>
          <template>
            <div class="row" style="margin: 0;">
              <div class="col-xs-4" style="padding:0;">
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
                  <el-option value="886">台湾 (+886)</el-option>
                </el-select>
              </div>
              <div class="col-xs-8" style="padding:0;">
                <input v-model="mobile" type="text" placeholder="请输入你的手机号码" style="width:100%;">
              </div>
            </div>
          </template>
          <div class = smspanel>
            <input class = "sms" v-model="vcode" type="text" placeholder="短信验证码" >
            <el-button :disabled="disableSms" class="sms-btn" :class="{disabled : disableSms}" type="primary" @click="getVcode">发送验证码<span v-show="timerId"> ({{ countDown }}s)</span></el-button>
            <!-- <span class = "smscode" @click="getVcode">发送短信验证码</span> -->
          </div>
          <input v-model="passwd" type="password" placeholder="请输入新的密码">
          <button @click.prevent="findPwd">找回密码</button>
        </form>
    </div>
  </div>
</template>
<script>
export default {
  data () {
    return {
      mobile: '',
      vcode: '',
      timerId: '',
      disableSms: false,
      countDown: 60,
      passwd: '',
      selected: 65
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
    findPwd () {
      var mobileReg = new RegExp(/^0?(13|14|15|16|17|18)[0-9]{9}$/)
      if (!this.selected) {
        return alert('请选择手机国家号')
      }
      if (!mobileReg.test(this.mobile)) {
        return alert('请填写正确手机号')
      }
      if (!this.vcode) {
        return alert('验证码不能为空')
      }
      if (this.passwd.length < 6) {
        return alert('密码长度至少需要6位')
      }
      var that = this
      this.$http.post('/api/resetPwd', {
        mobile: this.mobile,
        vcode: this.vcode,
        passwd: this.passwd,
        nation: this.selected
      }).then(function (res) {
        var resData = res.data
        if (resData.errcode === 0) {
          that.$router.push('/signin')
        } else {
          alert(resData.errmsg)
        }
      }).catch(function (err) {
        console.log(err)
      })
    }
  }
}
</script>
<style lang="scss">
.signup-container {
.findpwd {
  box-sizing: border-box;
  width: 530px;
  margin: 0 auto;
  background-color: #FFF;
  padding: 20px;
  position: relative;
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
    height: 240px;
    margin-bottom: 25px;
    input {
      display: block;
      box-sizing: border-box;
      width: 426px;
      height: 50px;
      border: 1px solid #4A4A4A;
      padding: 0 20px;
      font-size: 14px;
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
    }
    .smscode{
      display: block;
      box-sizing: border-box;
      width: 151px;
      height: 50px;
      border: 1px solid #4A4A4A;
      padding: 16px 20px;
      font-size: 14px;
      color: #9B9B9B;
      margin-left: 4px;
      text-align: center;
    }
    .sms {
      height: 50px;
      width: 275px;
    }
    button {
      width: 426px;
      height: 50px;
      text-align: center;
      color: #FFCF81;
      font-size: 18px;
      line-height: 25px;
      background-color: #000;
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
.phone-suffix .el-input__inner{
  height:50px;
}
  .el-select .el-input {
    .el-input__inner {
      width: 100%;
      border-radius: 0;
      border-right-width: 0;
    }

    &.is-focus {
      .el-input__inner {
        border-right-width: 1px;
      }
    }

  }
}
</style>
