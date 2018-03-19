<template>
  <el-row class="project-info" v-loading="loading">
    <el-col :span="16" :offset="2">
      <p>
        <br>
      </p>
      <el-form ref="form" :model="form" label-width="200px" style="margin-top: 30px;">
        <el-form-item label="姓名: ">
          <el-input v-model="form.uname" placeholder="请输入员工姓名"></el-input>
        </el-form-item>
        <el-form-item label="邮箱: ">
          <el-input v-model="form.email" placeholder="请输入员工邮箱"></el-input>
        </el-form-item>
        <el-form-item label="手机号: ">
          <el-input v-model="form.mobile" placeholder="请输入员工手机号"></el-input>
        </el-form-item>
        <el-form-item label="性别: ">
          <el-radio v-model="form.sexradio" label="0">男</el-radio>
          <el-radio v-model="form.sexradio" label="1">女</el-radio>
        </el-form-item>
        <el-form-item label="状态: ">
          <el-radio v-model="form.statusradio" label="0">激活</el-radio>
          <el-radio v-model="form.statusradio" label="1">禁用</el-radio>
        </el-form-item>
        <el-form-item label="">
          <el-button type="warning" style="width:400px;" @click="submitUser">添加用户</el-button>
        </el-form-item>
      </el-form>
    </el-col>
  </el-row>
</template>
<script type="text/javascript">
import VueQr from 'vue-qr'
import { mapActions } from 'vuex'

export default {
  components: {
    VueQr
  },
  data () {
    let valid = (rule, val, callback) => {
      if (this.validate) {
        if (!val) {
          callback(new Error('必填'))
        }
      }
      callback()
    }
    return {
      loading: false,
      form: {
        uname: '',
        email: '',
        mobile: '',
        sexradio: '0',
        statusradio: '0'
      },
      rules: {
        used: [{ validator: valid }]
      }
    }
  },
  computed: {
    params () {
      return {
        uname: this.form.uname,
        email: this.form.email,
        mobile: this.form.mobile,
        sexradio: this.form.sexradio,
        statusradio: this.form.statusradio
      }
    }
  },
  methods: {
    ...mapActions([
      'addUser'
    ]),
    handleAdd () {
      this.addUser(this.params)
        .then(data => {
          // this.$message.success('标记成功')
          // this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
    submitUser () {
      this.$refs.form.validate((valid) => {
        if (valid) {
          this.handleAdd()
        } else {
          return false
        }
      })
    }
  }
}

</script>
<style lang="scss">
.el-input__inner {
  width: 400px;
}

</style>
