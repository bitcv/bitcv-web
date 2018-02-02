<template>
  <div class="signup">
    <h3 class="panel-title center-title">欢迎注册链币网</h3>
      <span class="prompt">lianbi会员可直接使用会员名登录</span>
      <form>
        <input v-model="email" type="text" placeholder="邮箱">
        <div class = smspanel>
        <input class = "sms" v-model="vcode" type="text" placeholder="短信验证码" >
        <span class = "smscode" @click="getVcode">发送短信验证码</span>
        </div>
        <input v-model="passwd" type="password" placeholder="密码">
        <input v-model="confirm" type="password" placeholder="再次输入密码">
        <div> 
        <el-checkbox></el-checkbox>  
        <span class="protocl">我已阅读并同意链币注册协议</span>
        </div>
        <button @click.prevent="signup">注册</button>
      </form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      email: '',
      passwd: '',
      confirm: '',
      vcode: ''
    }
  },
  methods: {
    signup () {
      var emailReg = new RegExp(/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/)
      if (!emailReg.test(this.email)) {
        return alert('请填写正确邮箱地址')
      }
      if (this.passwd.length < 6) {
        return alert('密码长度至少需要6位')
      }
      if (this.passwd !== this.confirm) {
        return alert('两次输入的密码不一致')
      }
      if (this.vcode == null) {
        return alert('验证码不能为空')
      }
      var that = this
      this.$http.post('/api/signup', {
        account: this.email,
        passwd: this.passwd,
        vcode: this.vcode
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
    },
    getVcode () {
      var emailReg = new RegExp(/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/)
      if (!emailReg.test(this.email)) {
        return alert('请填写正确邮箱地址')
      }
      this.$http.post('/api/getVcode', {
        account: this.account
      }).then(function (res) {
        var resData = res.data
        if (resData.errcode === 0) {

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

<style lang="scss" scoped>
.signup {
  box-sizing: border-box;
  width: 530px;
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
    height: 439px;
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
        width: 273px;
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
    .protocl {
      width:182px;
      height:20px; 
      font-size:14px;
      font-family:PingFangSC-Regular;
      color:rgba(155,155,155,1);
      line-height:20px;
      margin-left: 4px;
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
</style>
