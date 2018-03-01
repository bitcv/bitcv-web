<template>
  <div>
    <div class="poptips" :class="{'poptips-show': showTips}">
      <div class="container">
        <i class="icon poptips-icon"></i>
        <span>提示：本次矿工费由币威平台承担</span>
      </div>
    </div>

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
                  <input disabled type="text" class="form-control" :value="userInfo.mobile">
                  <span class="input-group-btn">
                    <button :disabled="disableSms" type="button" class="btn btn-gray-light" @click="getVcode">
                      <span v-if="disableSms"> 请等待({{ countDown }}s)</span>
                      <span v-else>获取验证码</span>
                    </button>
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

export default {
  computed: {
    ...mapState({
      id: state => state.route.params.id
    }),
    ...mapState({
      userInfo: state => state.userInfo
    })
  },
  data () {
    return {
      showTips: true,
      walletAddr: '',
      vcode: '',
      timerId: '',
      disableSms: false,
      countDown: 60
    }
  },
  methods: {
    getVcode () {
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
        mobile: this.userInfo.mobile
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
      if (!/^0x[0-9a-f]{40}/i.test(this.walletAddr)) {
        return alert('请填写正确的收币钱包地址')
      }
      // 创建钱包
      this.$http.post('/api/addUserWallet', {
        mobile: this.userInfo.mobile,
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

<style lang="scss" scoped>
@import '~@/styles/variables';

.poptips {
  position: relative;
  padding: 10px 0;
  margin-top: -40px;
  font-size: 14px;
  text-align: center;
  color: $gray-darker;
  background-color: #fce3bf;

  opacity: 0;
  visibility: hidden;
  transition: all .35s ease-in;
  transform: translateY(-40px);

  &.poptips-show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
  }
}
.poptips-icon {
  display: inline-block;
  vertical-align: middle;
  width: 20px;
  height: 20px;
  margin-top: -3px;
  margin-right: 10px;
  background-color: transparent;
  background-repeat: no-repeat;
  background-position: center;
  background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB0PSIxNTE5NDU1ODI3NzM4IiBjbGFzcz0iaWNvbiIgc3R5bGU9IiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHAtaWQ9IjE5MTYiIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iNDgiIGhlaWdodD0iNDgiPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PC9zdHlsZT48L2RlZnM+PHBhdGggZD0iTTUxMiAwYy0yODIuNTcyNzA0IDAtNTExLjY0Mzg4OSAyMjkuMDcxMTg2LTUxMS42NDM4ODkgNTExLjY0Mzg4OXMyMjkuMDcxMTg2IDUxMS42NDM4ODkgNTExLjY0Mzg4OSA1MTEuNjQzODg5IDUxMS42NDM4ODktMjI5LjA3MTE4NiA1MTEuNjQzODg5LTUxMS42NDM4ODlTNzk0LjU3MjcwNCAwIDUxMiAwek01MTIgOTU4LjgzNDgzOGMtMjQ2Ljk3Njk5OCAwLTQ0Ny4xOTA5NDktMjAwLjIxMzk1MS00NDcuMTkwOTQ5LTQ0Ny4xOTA5NDlTMjY1LjAyMzAwMiA2NC40NTI5NDEgNTEyIDY0LjQ1Mjk0MXM0NDcuMTkwOTQ5IDIwMC4yMTM5NTEgNDQ3LjE5MDk0OSA0NDcuMTkwOTQ5Uzc1OC45NzY5OTggOTU4LjgzNDgzOCA1MTIgOTU4LjgzNDgzOHoiIGZpbGw9IiNmNTg0MjciIHAtaWQ9IjE5MTciPjwvcGF0aD48cGF0aCBkPSJNNTExLjY5NjA3OCA2NzEuNzE0NTYyTDUxMS42OTYwNzggNjcxLjcxNDU2MmMtMTcuNjYxMjQyIDAtMzEuOTc4MzE5LTE0LjMxNzA3Ny0zMS45NzgzMTktMzEuOTc4MzE5TDQ3OS43MTc3NTkgMjU1LjkyMjc0YzAtMTcuNjYxMjQyIDE0LjMxNzA3Ny0zMS45NzgzMTkgMzEuOTc4MzE5LTMxLjk3ODMxOWwwIDBjMTcuNjYxMjQyIDAgMzEuOTc4MzE5IDE0LjMxNzA3NyAzMS45NzgzMTkgMzEuOTc4MzE5bDAgMzgzLjgxMjQ3OUM1NDMuNjc0Mzk3IDY1Ny4zOTY0NjEgNTI5LjM1NzMyIDY3MS43MTQ1NjIgNTExLjY5NjA3OCA2NzEuNzE0NTYyeiIgZmlsbD0iI2Y1ODQyNyIgcC1pZD0iMTkxOCI+PC9wYXRoPjxwYXRoIGQ9Ik01MTEuNjc5NzA1IDc2Ny42OTI0OTZtLTMxLjk3ODMxOSAwYTMxLjI1IDMxLjI1IDAgMSAwIDYzLjk1NjYzNyAwIDMxLjI1IDMxLjI1IDAgMSAwLTYzLjk1NjYzNyAwWiIgZmlsbD0iI2Y1ODQyNyIgcC1pZD0iMTkxOSI+PC9wYXRoPjwvc3ZnPg==);
  background-size: 100%;
}
</style>
