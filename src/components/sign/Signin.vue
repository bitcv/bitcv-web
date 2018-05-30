<template>
  <div class="container">
    <div class="signin">
      <h2 class="panel-title center-title" style="text-align: center;font-size: 23px;margin-bottom: 50px;">{{ $t('label.login') }}</h2>
      <!--<div class="nav">-->
        <!--<span class="passwd" :class="{active : curIndex === 0}" @click="curIndex = 0">密码登录</span>-->
        <!--<span class="qrcode" :class="{active : curIndex === 1}" @click="curIndex = 1">扫码登录</span>-->
      <!--</div>-->
      <template v-if="curIndex === 0">
        <!--<span class="prompt">lianbi会员可直接使用会员名登录</span>-->
        <form>
          <input v-model="mobile" type="text" :placeholder="$t('label.login_mobile_ph')">
          <input v-model="passwd" type="password" :placeholder="$t('label.login_pwd_ph')">
          <h5 style="margin-top: 10px;"><span style="color: #ddd;">{{ $t('label.festival') }}<router-link to="findpwd"><a style="cursor: pointer;"><span style="color: #ff8b13;"> {{ $t('label.reset_pwd') }}</span></a></router-link></span></h5>
          <button @click.prevent="signin">{{ $t('label.login') }}</button>
        </form>
        <div class="btn-area">
          <router-link to="findPwd">
            <a style="float: left">{{ $t('label.reset_pwd') }}?</a>
          </router-link>
          <router-link to="signup">
            <a style="float: right;">{{ $t('label.registered') }}</a>
          </router-link>
        </div>
      </template>
      <div class="qrcode-area" v-else>
        <img src="/static/img/logo.png" alt="">
        <span>打开<em>微信</em>扫一扫</span>
      </div>
    </div>
  </div>
</template>

<script>
import {mapMutations} from 'vuex'

export default {
  data () {
    return {
      curIndex: 0,
      mobile: '',
      passwd: ''
    }
  },
  computed: {
    language () {
      return this.$i18n.locale
    }
  },
  mounted () {
    // 初始化图形验证码
    this.captcha = new TencentCaptcha('2040862605', res => {
      // 人机校验完毕，请求后端校验
      this.$http.post('/api/passHunmanVerifyTest', {
        mobile: this.mobile,
        ticket: res.ticket,
        randstr: res.randstr
      })
        .then(res => {
          var resData = res.data
          if (resData.errcode === 0) {
            // 校验成功，重试登录
            this.signin()
          } else {
            return Promise.reject(res.data.errmsg)
          }
        })
        .catch(error => {
          alert(error.toString())
        })
    })
  },
  methods: {
    ...mapMutations(['updateUserInfo']),
    signin () {
      var mobileReg = new RegExp(/^\d{7,11}$/)
      if (!mobileReg.test(this.mobile)) {
        if (this.language === 'cn') {
          return alert('请填写正确的手机号')
        } else if (this.language === 'en') {
          return alert('Please fill in the correct phone number')
        } else {
          return alert('正しい電話番号を入力してください')
        }
      }
      if (this.passwd.length < 6) {
        if (this.language === 'cn') {
          return alert('账号或密码错误')
        } else if (this.language === 'en') {
          return alert('Account or password is wrong')
        } else {
          return alert('ユーザー名あるいはパスワードが正しくありません')
        }
      }

      var that = this
      this.$http.post('/api/signin', {
        mobile: this.mobile,
        passwd: this.passwd
      })
        .then(res => {
          var resData = res.data
          if (resData.errcode === 0) {
            // localStorage.setItem('userId', resData.data.userId)
            // localStorage.setItem('mobile', resData.data.mobile)
            // localStorage.setItem('avatarUrl', resData.data.avatarUrl)
            // that.$root.eventHub.$emit('checkLoginStatus')
            that.updateUserInfo(resData.data)
            that.$router.push('/')
          } else if (resData.errcode === 218) {
            // 218 登录失败次数过多，请先输入验证码
            this.captcha.show()
          } else {
            return Promise.reject(res.data.errmsg)
          }
        })
        .catch(error => {
          alert(error.toString())
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.signin {
  box-sizing: border-box;
  width: 100%;
  max-width: 530px;
  background-color: #FFF;
  padding: 20px;
  position: relative;
  margin: 0 auto;
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
      width: 100%;
      height: 50px;
      border: 1px solid #ddd;
      border-radius:2px;
      padding: 0 20px;
      font-size: 14px;
      &:focus {
        border: 1px solid #FFCF81;
      }
    }
    input::-webkit-input-placeholder {
      color: #ddd;
    }
    button {
      width: 426px;
      height: 50px;
      width: 100%;
      text-align: center;
      color: #fff;
      font-size: 18px;
      line-height: 25px;
      background-color: #ff8b13;
      border-color: #ff8b13;
      border-radius: 2px;
    }
  }
  .btn-area {
    /*position: absolute;*/
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
  .btn-area a {
    color: #bbb;
    cursor: pointer;
  }
}
</style>
