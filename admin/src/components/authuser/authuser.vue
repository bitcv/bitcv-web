<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <div class="header-btn-area">
        <el-button type="primary" icon="el-icon-plus" @click="showAdd">添加</el-button>
      </div>
      <el-tab-pane label="用户列表">
        <el-table :data="authuserlist">
          <el-table-column label="ID">
            <template slot-scope="scope">{{ scope.row.uid }}</template>
          </el-table-column>
          <el-table-column label="姓名">
            <template slot-scope="scope">{{ scope.row.uname }}</template>
          </el-table-column>
          <el-table-column label="手机号">
            <template slot-scope="scope">{{ scope.row.mobile }}</template>
          </el-table-column>
          <el-table-column label="邮箱">
            <template slot-scope="scope">{{ scope.row.email }}</template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
                <el-button type="success" size="mini" @click="showEdit(scope.$index)">编辑</el-button>
                <el-button size="mini" type="danger" @click="showDelAuthUser(scope.row.uid)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-tab-pane>

      <el-dialog :title="this.uid ? '编辑用户' : '添加用户'" :visible.sync="showDialog" center>
        <el-form label-width="120px">
          <el-form-item label="姓名: ">
            <el-input type="text" placeholder="请输入姓名" v-model="username"></el-input>
          </el-form-item>
          <el-form-item label="手机号: ">
            <el-input type="text" placeholder="请输入手机号" v-model="usermobile"></el-input>
          </el-form-item>
          <el-form-item label="邮箱：">
            <el-input type="text" placeholder="请输入公司邮箱" v-model="useremail"></el-input>
          </el-form-item>
          <el-form-item label="密码：">
            <el-input type="password" placeholder="请输入6～10位密码" v-model="userpwd"></el-input>
          </el-form-item>
          <el-form-item label="状态：">
            <template>
              <el-radio v-model="useractive" label="0">激活</el-radio>
              <el-radio v-model="useractive" label="1">禁用</el-radio>
            </template>
          </el-form-item>
          <el-form-item label="性别：">
            <template>
              <el-radio v-model="gender" label="0">男</el-radio>
              <el-radio v-model="gender" label="1">女</el-radio>
            </template>
          </el-form-item>
          <el-form-item label="岗位权限：">
            <template>
              <el-checkbox-group v-model="checked">
                <el-checkbox v-for="(item, index) in roles" :key="index" :label="index">{{ item }}</el-checkbox>
              </el-checkbox-group>
            </template>
          </el-form-item>
          <el-form-item label="">
            <el-button type="primary" @click="submit">确定</el-button>
          </el-form-item>
        </el-form>
      </el-dialog>
    </el-tabs>
  </div>
</template>
<script>
export default {
  data () {
    return {
      useractive: '0',
      username: '',
      usermobile: '',
      useremail: '',
      userpwd: '',
      uid: '',
      showDialog: false,
      pageno: 1,
      perpage: 10,
      authuserlist: [],
      gender: '0',
      checked: [''],
      roles: []
    }
  },
  mounted () {
    this.userData()
  },
  methods: {
    userData () {
      this.$http.post('/api/getAuthUserList', {
        pageno: this.pageno,
        perpage: this.perpage
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.authuserlist = res.data.data.authuserlist
          this.roles = res.data.data.roles
        }
      })
    },
    showAdd () {
      this.uid = ''
      this.username = ''
      this.usermobile = ''
      this.useremail = ''
      this.useractive = '0'
      this.gender = '0'
      this.userpwd = ''
      this.checked = ['']
      this.showDialog = true
    },
    showEdit (index) {
      var userData = this.authuserlist[index]
      this.uid = userData.uid
      this.username = userData.uname
      this.usermobile = userData.mobile
      this.useremail = userData.email
      this.useractive = userData.is_active + ''
      this.gender = userData.gender + ''
      this.checked = userData.roles.split(',')
      this.showDialog = true
    },
    showDelAuthUser (uid) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delAuthUser(uid)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    delAuthUser (uid) {
      this.$http.post('/api/deleteAuthUser', {
        uid: uid
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '删除成功!' })
          this.userData()
        }
      })
    },
    submit () {
      if (!this.username) {
        return alert('请输入用户名')
      }
      var mobileReg = new RegExp(/^\d{7,11}$/)
      if (!mobileReg.test(this.usermobile)) {
        return alert('请填写正确的手机号')
      }
      var emailReg = RegExp('^([a-zA-Z0-9]+[_|\\_|\\.]?)*[a-zA-Z0-9_]+@([a-zA-Z0-9\\-]+[_|\\_|\\.]?)+[a-zA-Z0-9]+\\.[a-zA-Z]{2,4}$')
      if (!emailReg.test(this.useremail)) {
        return alert('请输入正确的邮箱地址')
      }
      if (!this.uid && (!this.userpwd || this.userpwd.length < 6 || this.userpwd.length > 10)) {
        return alert('请输入6～10位密码')
      }
      this.addAuthUser()
    },
    addAuthUser () {
      this.$http.post('/api/addAuthUser', {
        uid: this.uid,
        uname: this.username,
        umobile: this.usermobile,
        uemail: this.useremail,
        uactive: this.useractive,
        gender: this.gender,
        passwd: this.userpwd,
        roles: this.checked
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: this.uid ? '更新成功' : '添加成功' })
          this.showDialog = false
          this.userData()
        }
      })
    }
  }
}

</script>
<style lang="scss" scoped>
  .user_add {
    .el-input {
      width: 60%;
    }
  }
</style>
