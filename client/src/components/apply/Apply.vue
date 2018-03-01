<template>
  <div class="container">
    <div class="signin">
      <h3 class="panel-title center-title">项目申请</h3>
      <!-- <template> -->
        <span class="prompt">已经展示的项目请在项目详情页面直接认领！</span>
        <form>
          <input v-model="formData.nameCn" type="text" placeholder="项目中文名" required>
          <input v-model="formData.nameEn" type="text" placeholder="项目英文名" required>
          <input v-model="formData.tokenName" type="text" placeholder="通证名称" required>
          <input v-model="formData.tokenSymbol" type="text" placeholder="通证符号" required>
          <el-form-item :label="select.label" v-for="(select, field) in selectList" :key="field">
            <el-select v-model="formData[field]">
              <el-option v-for="(option, index) in select.optionList" :key="option.value" v-if="index" :label="option.label" :value="option.value"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="融资阶段">
            <el-select v-model="formData.fundStage">
              <el-option label="保密" :value="0"></el-option>
              <el-option label="未融资" :value="1"></el-option>
              <el-option label="融资中" :value="2"></el-option>
              <el-option label="已融资" :value="3"></el-option>
            </el-select>
          </el-form-item>
          <button @click.prevent="signin">创建项目</button>
        </form>
      <!-- </template> -->
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      selectList: {},
      formData: {
        nameCn: '',
        nameEn: '',
        tokenName: '',
        tokenSymbol: '',
        region: '',
        buzType: '',
        stage: '',
        fundStage: ''
      }
    }
  },
  mounted () {
    this.formData.projId = this.$route.params.id
    this.$http.get('/api/getProjTagList').then((res) => {
      if (res.data.errcode === 0) {
        this.selectList = res.data.data
      }
    })
  },
  methods: {
    signin () {
      this.$http.post('/api/apply', this.formData).then(function (res) {
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
      margin-top: -1px;
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
      margin-top: 10px;
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
