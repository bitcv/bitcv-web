<template>
    <div class="resetpwd">
    <h3 class="panel-title center-title">重设密码</h3>
    <form>
        <input v-model="passwd" type="password" placeholder="请输入新的密码">
        <input v-model="repasswd" type="password" placeholder="请再次输入新的密码">
        <button @click.prevent="resetPwd">确定</button>
      </form>
    </div>
</template>
<script>
export default {
  data () {
    return {
      passwd: '',
      repasswd: ''
    }
  },
  methods: {
    resetPwd () {
      if (this.passwd.length < 6) {
        return alert('密码长度至少需要6位')
      }
      if (this.passwd !== this.repasswd) {
        return alert('两次输入的密码不一致')
      }
      var userId = localStorage.getItem('userId')
      this.$http.post('/api/resetPwd', {
        userId: userId,
        passwd: this.passwd,
        repasswd: this.repasswd
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
.resetpwd {
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