<template>
  <div class="signin">
    <h3 class="panel-title center-title">登录</h3>
    <div class="nav">
      <span class="passwd" :class="{active : curIndex === 0}" @click="curIndex = 0">密码登录</span>
      <span class="qrcode" :class="{active : curIndex === 1}" @click="curIndex = 1">扫码登录</span>
    </div>
    <template v-if="curIndex === 0">
      <span class="prompt">lianbi会员可直接使用会员名登录</span>
      <form>
        <input v-model="mobile" type="text" placeholder="手机号">
        <input v-model="passwd" type="password" placeholder="登录密码">
        <button @click.prevent="signin">登录</button>
      </form>
      <div class="btn-area">
        <a>忘记密码</a>
        <router-link to="signup">
          <a>注册会员</a>
        </router-link>
      </div>
    </template>
    <div class="qrcode-area" v-else>
      <img src="/static/img/logo.png" alt="">
      <span>打开<em>微信</em>扫一扫</span>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      curIndex: 0,
      mobile: '',
      passwd: ''
    }
  },
  methods: {
    signin () {
      var mobileReg = new RegExp(/^0?(13|14|15|17|18)[0-9]{9}$/)
      if (!mobileReg.test(this.mobile)) {
        return alert('请填写正确的手机号')
      }
      if (this.passwd.length < 6) {
        return alert('账号或密码错误')
      }
      var that = this
      this.$http.post('/api/signin', {
        mobile: this.mobile,
        passwd: this.passwd
      }).then(function (res) {
        var resData = res.data
        if (resData.errcode === 0) {
          localStorage.setItem('userId', resData.data.userId)
          localStorage.setItem('mobile', resData.data.mobile)
          localStorage.setItem('avatarUrl', resData.data.avatarUrl)
          that.$router.push('/')
          that.$router.go(0)
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
.signin {
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
  .nav {
    box-sizing: border-box;
    padding: 0 40px;
    width: 100%;
    display: flex;
    justify-content: space-around;
    font-size: 20px;
    color: #9B9B9B;
    span {
      display: block;
      padding: 0 5px;
      height: 50px;
      line-height: 50px;
      border-bottom: 1px solid #FFF;
      cursor: pointer;
      &.active {
        color: #F5A623;
        border-bottom: 1px solid #F5A623;
      }
    }
  }
  .prompt {
    display: block;
    width: 100%;
    text-align: center;
    font-size: 14px;
    line-height: 20px;
    color: #4A4A4A;
    margin-top: 15px;
  }
  form {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: center;
    width: 100%;
    height: 220px;
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
  .qrcode-area {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: center;
    .qrcode-box {
      width: 180px;
      height: 180px;
    }
    img {
      width: 180px;
      height: 180px;
      margin: 35px 0;
    }
    span {
      font-size: 20px;
      line-height: 28px;
      color: #9B9B9B;
      em {
        color: #FFCF81;
      }
      margin-bottom: 10px;
    }
  }
}
</style>
