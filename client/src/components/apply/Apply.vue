<template>
  <div class="signin">
    <h3 class="panel-title center-title">项目申请</h3>
    <template>
      <span class="prompt">已经展示的项目请在项目详情页面直接认领！</span>
      <form>
        <input v-model="name_cn" type="text" placeholder="项目中文名">
        <input v-model="name_en" type="text" placeholder="项目英文名">
        <button @click.prevent="signin">创建项目</button>
      </form>
    </template>
  </div>
</template>

<script>
export default {
  data () {
    return {
      name_cn: '',
      name_en: ''
    }
  },
  methods: {
    signin () {
      if (this.name_cn.length === 0 || this.name_en.length === 0) {
        return alert('请输入项目名称')
      }
      this.$http.post('/api/apply', {
        name_cn: this.name_cn,
        name_en: this.name_en
      }).then(function (res) {
        var resData = res.data
        if (resData.errcode === 0) {
          location.href = '/admin'
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
  width: 100%;
  max-width: 530px;
  background-color: #FFF;
  padding: 20px;
  position: relative;
  margin: 0 10px;
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
      width: 100%;
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
