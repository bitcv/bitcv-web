<template>
  <div class="signup">
    <h3 class="panel-title center-title">欢迎注册链币网</h3>
      <span class="prompt">lianbi会员可直接使用会员名登录</span>
      <form>
        <input v-model="email" type="text" placeholder="邮箱">
        <input v-model="passwd" type="password" placeholder="密码">
        <input v-model="confirm" type="password" placeholder="再次输入密码">
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
      confirm: ''
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
      var that = this
      this.$http.post('/api/signup', {
        account: this.email,
        passwd: this.passwd
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
    height: 250px;
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
</style>
