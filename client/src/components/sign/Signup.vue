<template>
  <div class="container signup-container">
    <div class="signup">
      <h2 class="panel-title center-title" style="text-align: center;font-size: 23px;margin-bottom: 50px;">欢迎注册 BCV</h2>
        <!--<span class="prompt">链币会员可直接使用会员名登录</span>-->
        <form>
          <template>
            <div class='row' style="margin: 0;">
              <div class='col-xs-4' style="padding:0;">
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
                  <el-option value="93">阿富汗 (+93)</el-option>
                  <el-option value="355">阿尔巴尼亚 (+355)</el-option>
                  <el-option value="213">阿尔及利亚 (+213)</el-option>
                  <el-option value="684">美属萨摩亚 (+684)</el-option>
                  <el-option value="376">安道尔 (+376)</el-option>
                  <el-option value="244">安哥拉 (+244)</el-option>
                  <el-option value="1264">安圭拉 (+1264)</el-option>
                  <el-option value="672">南极洲 (+672)</el-option>
                  <el-option value="1268">安提瓜和巴布达 (+1268)</el-option>
                  <el-option value="54">阿根廷 (+54)</el-option>
                  <el-option value="374">亚美尼亚 (+374)</el-option>
                  <el-option value="297">阿鲁巴 (+297)</el-option>
                  <el-option value="61">澳大利亚 (+61)</el-option>
                  <el-option value="43">奥地利 (+43)</el-option>
                  <el-option value="994">阿塞拜疆 (+994)</el-option>
                  <el-option value="973">巴林 (+973)</el-option>
                  <el-option value="880">孟加拉国 (+880)</el-option>
                  <el-option value="1246">巴巴多斯 (+1246)</el-option>
                  <el-option value="375">白俄罗斯 (+375)</el-option>
                  <el-option value="32">比利时 (+32)</el-option>
                  <el-option value="501">伯利兹 (+501)</el-option>
                  <el-option value="229">贝宁 (+229)</el-option>
                  <el-option value="1441">百慕大 (+1441)</el-option>
                  <el-option value="975">不丹 (+975)</el-option>
                  <el-option value="591">玻利维亚 (+591)</el-option>
                  <el-option value="387">波黑 (+387)</el-option>
                  <el-option value="267">博茨瓦纳 (+267)</el-option>
                  <el-option value="55">巴西 (+55)</el-option>
                  <el-option value="1284">英属维尔京群岛 (+1284)</el-option>
                  <el-option value="673">文莱 (+673)</el-option>
                  <el-option value="359">保加利亚 (+359)</el-option>
                  <el-option value="226">布基纳法索 (+226)</el-option>
                  <el-option value="95">缅甸 (+95)</el-option>
                  <el-option value="257">布隆迪 (+257)</el-option>
                  <el-option value="855">柬埔寨 (+855)</el-option>
                  <el-option value="237">喀麦隆 (+237)</el-option>
                  <el-option value="1">加拿大 (+1)</el-option>
                  <el-option value="238">佛得角 (+238)</el-option>
                  <el-option value="1345">开曼群岛 (+1345)</el-option>
                  <el-option value="236">中非 (+236)</el-option>
                  <el-option value="235">乍得 (+235)</el-option>
                  <el-option value="56">智利 (+56)</el-option>
                  <el-option value="61">圣诞岛 (+61)</el-option>
                  <el-option value="61">科科斯（基林）群岛 (+61)</el-option>
                  <el-option value="57">哥伦比亚 (+57)</el-option>
                  <el-option value="269">科摩罗 (+269)</el-option>
                  <el-option value="243">刚果（金） (+243)</el-option>
                  <el-option value="242">刚果（布） (+242)</el-option>
                  <el-option value="682">库克群岛 (+682)</el-option>
                  <el-option value="506">哥斯达黎加 (+506)</el-option>
                  <el-option value="225">科特迪瓦 (+225)</el-option>
                  <el-option value="385">克罗地亚 (+385)</el-option>
                  <el-option value="53">古巴 (+53)</el-option>
                  <el-option value="420">捷克 (+420)</el-option>
                  <el-option value="45">丹麦 (+45)</el-option>
                  <el-option value="253">吉布提 (+253)</el-option>
                  <el-option value="1767">多米尼克 (+1767)</el-option>
                </el-select>
              </div>
              <div class='col-xs-8' style="padding:0;">
                <input class='phone' v-model="mobile" type="text" placeholder="请输入手机号" style="width:100%;">
              </div>
            </div>
          </template>
          <div class = smspanel>
          <input class = "sms-input" v-model="vcode" type="text" style="border-right-width: 0" placeholder="短信验证码" >
            <div class="floatboder"></div>
          <el-button :disabled="disableSms" class="sms-btn" :class="{disabled : disableSms}" type="primary" @click="getVcode">获取验证码<span v-show="timerId"> ({{ countDown }}s)</span></el-button>
          </div>
          <input v-model="passwd" type="password" placeholder="密码">
          <button class="signup-btn" @click.prevent="signup">注册</button>
          <h5 style="margin-top: 25px;"><span style="color: #aaa;">已有账号?</span><a href="/signin" style="cursor: pointer;"><span style="color: #ff8b13;"> 去登陆</span></a></h5>
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
      if (!this.selected) {
        return alert('请选择手机国家号')
      }
      var mobileReg = new RegExp(/^\d{7,11}$/)
      if (!mobileReg.test(this.mobile)) {
        return alert('请填写正确的手机号')
      }
      // if (this.mobile.length < 7) {
      //   return alert('请填写正确的手机号')
      // }
      // if (this.mobile.length > 11) {
      //   return alert('请填写正确的手机号')
      // }
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
      // if (this.mobile.length < 7) {
      //   return alert('请填写正确的手机号')
      // }
      // if (this.mobile.length > 11) {
      //   return alert('请填写正确的手机号')
      // }
      var mobileReg = new RegExp(/^\d{7,11}$/)
      if (!mobileReg.test(this.mobile)) {
        return alert('请填写正确的手机号')
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
          border: 1px solid #ddd;
          padding: 0 20px;
          font-size: 14px;
          margin-bottom: 15px;
          border-radius:2px;
          &:focus {
            border-color: #409EFF;
          }
        }
        input::-webkit-input-placeholder {
          color: #ddd;
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
            border: 1px solid #ddd;
            border-radius: 0;
            text-align: center;
            color: #ff8b13;
            font-size: 16px;
            line-height: 25px;
            background-color: #fff;
            border-left-width: 0;
            padding: 0;
            &.disabled {
              background-color: #909399;
              color: #FFF;
            }
          }
          .sms-input {
            height: 50px;
            width: calc(100% - 150px);
            /*border-right-width: 0;*/
            border-right: 1px solid red;
          }
        }
        .signup-btn {
          max-width: 426px;
          width: 100%;
          height: 50px;
          text-align: center;
          color: #fff;
          font-size: 18px;
          line-height: 25px;
          background-color: #ff8b13;
          border-color: #ff8b13;
          border-radius:2px;
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
    .phone-suffix .el-input__inner{height:50px;border-right-width: 0}
    .floatboder {
      border-right: 2px solid #eee;
      height: 30px;
      margin-top: 10px;
    }
  }
</style>
