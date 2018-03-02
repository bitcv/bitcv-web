<template>
  <div class="container signup-container">
    <div class="findpwd">
      <h2 class="panel-title center-title" style="text-align: center;font-size: 23px;margin-bottom: 50px;">重置密码</h2>
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
                  <el-option value="1809">多米尼加 (+1809)</el-option>
                  <el-option value="593">厄瓜多尔 (+593)</el-option>
                  <el-option value="20">埃及 (+20)</el-option>
                  <el-option value="503">萨尔瓦多 (+503)</el-option>
                  <el-option value="240">赤道几内亚 (+240)</el-option>
                  <el-option value="291">厄立特里亚 (+291)</el-option>
                  <el-option value="372">爱沙尼亚 (+372)</el-option>
                  <el-option value="251">埃塞俄比亚 (+251)</el-option>
                  <el-option value="500">福克兰群岛（马尔维纳斯） (+500)</el-option>
                  <el-option value="298">法罗群岛 (+298)</el-option>
                  <el-option value="679">斐济 (+679)</el-option>
                  <el-option value="358">芬兰 (+358)</el-option>
                  <el-option value="33">法国 (+33)</el-option>
                  <el-option value="594">法属圭亚那 (+594)</el-option>
                  <el-option value="689">法属波利尼西亚 (+689)</el-option>
                  <el-option value="241">加蓬 (+241)</el-option>
                  <el-option value="995">格鲁吉亚 (+995)</el-option>
                  <el-option value="49">德国 (+49)</el-option>
                  <el-option value="233">加纳 (+233)</el-option>
                  <el-option value="350">直布罗陀 (+350)</el-option>
                  <el-option value="30">希腊 (+30)</el-option>
                  <el-option value="299">格陵兰 (+299)</el-option>
                  <el-option value="1473">格林纳达 (+1473)</el-option>
                  <el-option value="590">瓜德罗普 (+590)</el-option>
                  <el-option value="1671">关岛 (+1671)</el-option>
                  <el-option value="502">危地马拉 (+502)</el-option>
                  <el-option value="1481">根西岛 (+1481)</el-option>
                  <el-option value="224">几内亚 (+224)</el-option>
                  <el-option value="245">几内亚比绍 (+245)</el-option>
                  <el-option value="592">圭亚那 (+592)</el-option>
                  <el-option value="509">海地 (+509)</el-option>
                  <el-option value="379">梵蒂冈 (+379)</el-option>
                  <el-option value="504">洪都拉斯 (+504)</el-option>
                  <el-option value="36">匈牙利 (+36)</el-option>
                  <el-option value="354">冰岛 (+354)</el-option>
                  <el-option value="91">印度 (+91)</el-option>
                  <el-option value="62">印度尼西亚 (+62)</el-option>
                  <el-option value="98">伊朗 (+98)</el-option>
                  <el-option value="964">伊拉克 (+964)</el-option>
                  <el-option value="353">爱尔兰 (+353)</el-option>
                  <el-option value="84">越南 (+84)</el-option>
                  <el-option value="44">英国 (+44)</el-option>
                  <el-option value="971">阿拉伯联合酋长国 (+971)</el-option>
                  <el-option value="380">乌克兰 (+380)</el-option>
                  <el-option value="90">土耳其 (+90)</el-option>
                  <el-option value="66">泰国 (+66)</el-option>
                  <el-option value="992">塔吉克斯坦 (+992)</el-option>
                  <el-option value="46">瑞典 (+46)</el-option>
                  <el-option value="41">瑞士 (+41)</el-option>
                  <el-option value="963">叙利亚 (+963)</el-option>
                  <el-option value="252">索马里 (+252)</el-option>
                  <el-option value="27">南非 (+27)</el-option>
                  <el-option value="34">西班牙 (+34)</el-option>
                  <el-option value="966">沙特阿拉伯 (+966)</el-option>
                  <el-option value="7">俄罗斯 (+7)</el-option>
                  <el-option value="40">罗马尼亚 (+40)</el-option>
                  <el-option value="92">巴基斯坦 (+92)</el-option>
                  <el-option value="64">新西兰 (+64)</el-option>
                  <el-option value="977">尼泊尔 (+977)</el-option>
                  <el-option value="31">荷兰 (+31)</el-option>
                  <el-option value="52">墨西哥 (+52)</el-option>
                  <el-option value="60">马来西亚 (+60)</el-option>
                </el-select>
              </div>
              <div class="col-xs-8" style="padding:0;">
                <input v-model="mobile" type="text" placeholder="请输入你的手机号码" style="width:100%;">
              </div>
            </div>
          </template>
          <div class = smspanel>
            <input class = "sms" v-model="vcode" type="text" style="border-right-width: 0" placeholder="请输入验证码" >
            <div class="floatboder"></div>
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
    findPwd () {
      if (!this.selected) {
        return alert('请选择手机国家号')
      }
      var mobileReg = new RegExp(/^\d{7,11}$/)
      if (!mobileReg.test(this.mobile)) {
        return alert('请填写正确的手机号')
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
      border: 1px solid #ddd;
      padding: 0 20px;
      font-size: 14px;
      border-radius:2px;
      &:focus {
        border: 1px solid #FFCF81;
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
        width: 150px;
        height: 50px;
        border: 1px solid #ddd;
        border-radius: 0;
        text-align: center;
        color: #FFCF81;
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
      color: #fff;
      border-radius: 2px;
      font-size: 18px;
      line-height: 25px;
      background-color: #ff8b13;
      border-color: #ff8b13;
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
  .floatboder {
    border-right: 2px solid #eee;
    height: 30px;
    margin-top: 10px;
  }
}
</style>
