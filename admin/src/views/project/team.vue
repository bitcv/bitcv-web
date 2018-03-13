<template>
  <div class="project-team" v-loading="loading">
    <div class="add-box">
      <el-button type="warning" plain @click="visible = true; validate = true">+添加成员</el-button>
    </div>
    <el-table :data="list" style="width: 100%;">
      <el-table-column prop="photoUrl" label="照片" class-name="header">
        <template slot-scope="scope">
          <img :src="scope.row.photoUrl" :alt="scope.row.name + '头像'" width="60" />
        </template>
      </el-table-column>
      <el-table-column prop="name" label="姓名">
      </el-table-column>
      <el-table-column prop="position" label="职位">
      </el-table-column>
      <el-table-column prop="intro" label="简介" width="400" class-name="line-clamp-5">
      </el-table-column>
      <el-table-column label="操作" width="150">
        <template slot-scope="scope">
          <span class="text-link" @click="handleEdit(scope.row)">编辑</span>
          <span class="text-error" @click="handleDel(scope.row.id)" style="margin-left: 20px;">删除</span>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog title="项目成员信息" :visible.sync="visible" center @close="handleClose" v-loading="dLoading">
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
          <el-form-item label="职位" prop="position">
            <el-input v-model="form.position"></el-input>
          </el-form-item>
          <el-form-item label="简介" prop="intro">
            <el-input type="textarea" v-model="form.intro" :autosize="{minRows: 3, maxRows: 5}"></el-input>
          </el-form-item>
        </el-form>
        <!-- </el-tab-pane>
      </el-tabs> -->
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
      list: [],
      form: {
        name: '',
        photoUrl: '',
        position: '',
        intro: ''
      },
      rules: {
        name: [ {validator: valid} ],
        photoUrl: [ {validator: valid} ],
        position: [ {validator: valid} ],
        intro: [ {validator: valid} ]
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
        memberId: this.form.id,
        name: this.form.name,
        photoUrl: this.form.photoUrl,
        position: this.form.position,
        intro: this.form.intro
      }
    }
  },
  created () {
    this.fetch()
  },
  methods: {
    ...mapActions(['getProjMemberList', 'addProjIMember', 'updProjMember', 'delProjMember']),
    fetch () {
      this.loading = true
      this.getProjMemberList({projId: this.userInfo.userId})
        .then(({dataList = []} = {}) => {
          this.list = dataList
          this.loading = false
        })
        .catch(() => {
          this.loading = false
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
        this.delProjMember({memberId: mId})
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
      if (this.params.memberId === undefined) delete this.params.memberId
      this[this.params.memberId !== undefined ? 'updProjMember' : 'addProjIMember'](this.params)
        .then(data => {
          this.$message.success(this.params.memberId !== undefined ? '更新成功' : '添加成功')
          this.dLoading = false
          this.visible = false
          this.fetch()
        })
        .catch(() => {
          this.dLoading = false
        })
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
      this.$refs.form.validate((valid) => {
        if (valid) {
          if (this.form.id) this.form.memberId = this.form.id
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
        position: '',
        intro: ''
      }
      this.validate = false
    }
  }
}
</script>

<style lang="scss">
  @import '~@/styles/variables.scss';
  .project-team{
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
  }
</style>
