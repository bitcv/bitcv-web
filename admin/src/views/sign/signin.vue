<template>
  <div class="signin">
    <el-form :model="form" :rules="rules" class="signin-form" ref="form">
      <el-form-item prop="mobile">
        <el-input v-model="form.mobile" placeholder="请输入管理员账号"></el-input>
      </el-form-item>
      <el-form-item prop="passwd">
        <el-input v-model="form.passwd" type="password" placeholder="请输入管理员密码"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button @click="signin" type="primary" class="signin-btn">登录</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {mapMutations} from 'vuex'

const mobileValidator = (rule, value, callback) => {
  if (!/^1\d{10}$/.test(value)) {
    return callback(new Error('请输入正确的管理员账号'))
  }

  callback()
}

export default {
  data () {
    return {
      form: {
        mobile: '', // 13611146629
        passwd: '' // qqq111
      },
      rules: {
        mobile: [
          {required: true, message: '请输入管理员账号'},
          {validator: mobileValidator}
        ],
        passwd: [
          {required: true, message: '请输入密码'},
          {type: 'string', min: 6, max: 16, message: '密码长度6-16位'}
        ]
      }
    }
  },
  methods: {
    ...mapMutations(['updateUserInfo']),
    signin () {
      this.$refs.form.validate(valid => {
        if (valid) {
          this.$http.post('/api/signin', this.form)
            .then((res) => {
              if (res.data.errcode === 0) {
                this.updateUserInfo(res.data.data)
                this.$router.replace('/')
              } else {
                alert(res.data.errmsg)
              }
            })
        }
      })
    }
  }
}
</script>

<style lang="scss">
.signin {
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #F5F5F5;
  .signin-form {
    background-color: #FFF;
    padding: 30px;
    box-sizing: border-box;
    width: 400px;
    .signin-btn {
      width: 100%;
    }
  }
}

</style>
