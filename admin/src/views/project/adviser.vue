<template>
  <div class="project-main" v-loading="loading">
    <div class="add-box">
      <el-button type="warning" plain @click="visible = true; validate = true">+添加顾问</el-button>
    </div>
    <el-table :data="list" style="width: 100%;">
      <el-table-column prop="logoUrl" label="照片" class-name="header">
        <template slot-scope="scope">
          <img :src="scope.row.logoUrl" :alt="scope.row.name + '头像'" width="60" />
        </template>
      </el-table-column>
      <el-table-column prop="name" label="姓名">
      </el-table-column>
      <el-table-column prop="company" label="公司">
      </el-table-column>
      <el-table-column prop="intro" label="简介" width="400" class-name="line-clamp-5">
      </el-table-column>
      <el-table-column label="操作" width="150">
        <template slot-scope="scope">
          <!-- 暂时去掉编辑的功能 -->
          <!-- <span class="text-link" @click="handleEdit(scope.row)">编辑</span> -->
          <span class="text-error" @click="handleDel(scope.row.id)">删除</span>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog title="项目顾问信息" :visible.sync="visible" center @close="handleClose" v-loading="dLoading">
      <el-tabs v-model="activeName">
        <el-tab-pane label="直接选择" name="first">
          <el-form label-width="80px" style="width: 70%; margin: 0 15%;" :model="form1" status-icon :rules="rules1" ref="form1">
            <el-form-item label="顾问信息" prop="memberId">
            <el-select v-model="form1.memberId" placeholder="请选择顾问姓名">
              <el-option v-for="(op, index) in options" :key="index" :value="op.id" :label="op.name"></el-option>
            </el-select>
            </el-form-item>
          </el-form>
        </el-tab-pane>
        <el-tab-pane label="手动输入" name="second">
          <el-form label-width="80px" :model="form" status-icon :rules="rules" ref="form">
            <el-form-item label="姓名" prop="name">
              <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="照片" prop="photoUrl">
              <el-upload
              class="upload-box"
              name="picture"
              action="/api/uploadFile"
              :on-progress="handleProgress"
              :on-success="handleSuccess"
              :on-error="handleError"
              :show-file-list="false"
              v-loading="pLoading">
                <i class="el-icon-plus"></i>
                <img :src="form.photoUrl" alt="" width="60">
              </el-upload>
            </el-form-item>
            <el-form-item label="职位" prop="company">
              <el-input v-model="form.company"></el-input>
            </el-form-item>
            <el-form-item label="简介" prop="intro">
              <el-input type="textarea" v-model="form.intro"></el-input>
            </el-form-item>
          </el-form>
          </el-tab-pane>
      </el-tabs>
      <div slot="footer">
        <el-button @click="visible = false">取消</el-button>
        <el-button type="primary" @click="handleSubmit">确定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex'
export default {
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
      visible: false,
      pLoading: false, // 上传图片loading
      dLoading: false, // dialog loading
      validate: true, // 用于识别是否需要验证（dialog关闭时，form赋空值，所以不需要验证）
      activeName: 'first',
      list: [],
      options: [],
      form: {
        name: '',
        photoUrl: '',
        company: '',
        intro: ''
      },
      rules: {
        name: [ {validator: valid} ],
        photoUrl: [ {validator: valid} ],
        company: [ {validator: valid} ],
        intro: [ {validator: valid} ]
      },
      form1: {
        memberId: ''
      },
      rules1: {
        memberId: [ {validator: valid} ]
      }
    }
  },
  computed: {
    ...mapState({
      userInfo: state => state.userInfo
    }),
    params () {
      return {
        projId: this.userInfo.userId,
        memberId: this.form1.memberId, // 直接选择传的参数
        name: this.form.name,
        photoUrl: this.form.photoUrl,
        company: this.form.company,
        intro: this.form.intro,
        advisorId: this.form.id // 更新时传的参数
      }
    }
  },
  created () {
    this.fetch()
  },
  methods: {
    ...mapActions(['getProjAdvisorList', 'getAdvList', 'addProjAdvisor', 'addProjIAdvisor', 'updProjAdvisor', 'delProjAdvisor']),
    fetch () {
      this.loading = true
      this.getProjAdvisorList({projId: this.userInfo.userId})
        .then(({dataList = []} = {}) => {
          this.list = dataList
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
      this.fetchOptions()
    },
    fetchOptions () {
      this.getAdvList()
        .then(({perList = []} = {}) => {
          this.options = perList
        })
    },
    handleEdit (row) {
      this.visible = true
      this.validate = true
      this.form = Object.assign(this.form, row)
    },
    handleDel (mId) {
      this.$confirm('删除后无法恢复， 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        center: true
      }).then(() => {
        this.loading = true
        this.delProjAdvisor({advisorId: mId})
          .then((data = {}) => {
            this.loading = false
            this.$message({ type: 'success', message: '删除成功' })
            this.fetch()
          })
          .catch(() => {
            this.loading = false
          })
      }).catch(() => {})
    },
    handleAdd () {
      this.dLoading = true
      if (this.params.memberId !== undefined && this.params.memberId !== '') { // 直接选择（新增）
        this.addProjAdvisor({projId: this.params.projId, memberId: this.params.memberId})
          .then(data => {
            this.$message.success('添加成功')
            this.dLoading = false
            this.visible = false
            this.fetch()
          })
          .catch(() => {
            this.dLoading = false
          })
      } else { // 有advisorId为编辑，无则为手动输入新增
        delete this.params.memberId
        if (this.params.advisorId === undefined) delete this.params.advisorId
        this[this.params.advisorId !== undefined ? 'updProjAdvisor' : 'addProjIAdvisor'](this.params)
          .then(data => {
            this.$message.success(this.params.advisorId !== undefined ? '更新成功' : '添加成功')
            this.dLoading = false
            this.visible = false
            this.fetch()
          })
          .catch(() => {
            this.dLoading = false
          })
      }
    },
    handleSuccess (res) {
      this.pLoading = false
      if (res.errcode === 0) this.form.photoUrl = res.data.url
    },
    handleProgress () {
      this.pLoading = true
    },
    handleError () {
      this.pLoading = false
      this.$message.warning('图片上传失败，请重试')
    },
    handleSubmit () {
      this.$refs[this.activeName === 'first' ? 'form1' : 'form'].validate((valid) => {
        if (valid) {
          this.handleAdd()
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    handleClose () {
      this.form = {
        name: '',
        photoUrl: '',
        company: '',
        intro: ''
      }
      this.form1.memberId = ''
      this.validate = false
    }
  }
}
</script>

<style lang="scss">
  @import '~@/styles/variables.scss';
  .project-main{
    .header{
      img{
        border-radius: 50%;
        width: 60px;
        height: 60px;
      }
    }
    .add-box{
      overflow: hidden;
      text-align: right;
      margin: 20px 0 25px;
    }
    .line-clamp-5{
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 5;
      overflow: hidden;
      font-size: 12px;
    }
    tbody{
      .line-clamp-5{
        min-height: 91px;
      }
    }
    .upload-box{
      font-size: 40px;
      color: $outline-color;
      width: 100px;
      height: 100px;
      line-height: 100px;
      text-align: center;
      border: 1px dashed $outline-color;
      border-radius: 5px;
      .el-upload{
        position: relative;
        width: 100%;
        height: 100%;
      }
      img{
        position: absolute;
        top: 0;
        left: 0;
        width: 100px;
        height: 100px;
      }
      .el-loading-spinner{
        margin-top: 0;
        top: 0;
      }
    }

    .text-link, .text-error{
      cursor: pointer;
      &:hover{
        color: $main-color;
      }
    }

    .el-tabs__nav{
      margin: 0 auto;
      float: none;
      width: 152px;
    }
    .el-select{
      width: 100%;
    }
  }
</style>
