<template>
  <div class="project-main" v-loading="loading">
    <div class="add-box">
      <el-button type="warning" plain @click="visible = true; validate = true">+添加信息</el-button>
    </div>
    <el-table :data="list" style="width: 100%;">
      <el-table-column prop="fontClass" label="Logo">
        <template slot-scope="scope">
          <i class="fab" :class="scope.row.fontClass"></i>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="名称">
      </el-table-column>
      <el-table-column prop="linkUrl" label="主页url">
      </el-table-column>
      <el-table-column label="操作" width="150">
        <template slot-scope="scope">
          <span class="text-link" @click="handleEdit(scope.row)">编辑</span>
          <span class="text-error" @click="handleDel(scope.row.id)" style="margin-left: 20px;">删除</span>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog title="社交账号信息" :visible.sync="visible" center @close="handleClose" v-loading="dLoading">
        <el-form label-width="80px" :model="form" status-icon :rules="rules" ref="form">
          <el-form-item label="平台名称" prop="socialId">
            <el-select v-model="form.socialId" placeholder="请选择社交平台">
              <el-option v-for="(op, index) in options" :key="index" :label="op.name" :value="op.id">
                <i class="fab select-font-icon" :class="op.fontClass"></i>
                <span>{{ op.name }}</span>
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="主页url" prop="linkUrl">
            <el-input v-model="form.linkUrl" placeholder="请输入社交账号的主页地址">
              <template slot="prepend">http://</template>
            </el-input>
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
      dLoading: false, // dialog loading
      validate: true, // 用于识别是否需要验证（dialog关闭时，form赋空值，所以不需要验证）
      list: [],
      options: [],
      form: {
        fontClass: '',
        socialId: '',
        linkUrl: ''
      },
      rules: {
        fontClass: [ {validator: valid} ],
        socialId: [ {validator: valid} ],
        linkUrl: [ {validator: valid} ]
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
        socialId: this.form.socialId,
        linkUrl: this.form.linkUrl,
        projSocialId: this.form.id
      }
    }
  },
  created () {
    this.fetch()
  },
  methods: {
    ...mapActions(['getProjSocialList', 'getSocialList', 'addProjSocial', 'updProjSocial', 'delProjSocial']),
    fetch () {
      this.loading = true
      this.getProjSocialList({projId: this.userInfo.userId})
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
      this.getSocialList()
        .then(({dataList = []} = {}) => {
          this.options = dataList
        })
    },
    handleEdit (row) {
      this.visible = true
      this.validate = true
      this.form = Object.assign(this.form, row)
      let hasIndex = this.form.linkUrl.indexOf('http://')
      if (hasIndex > -1) this.form.linkUrl = this.form.linkUrl.substr(7)
    },
    handleDel (mId) {
      this.$confirm('删除后无法恢复， 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        center: true
      }).then(() => {
        this.loading = true
        this.delProjSocial({projSocialId: mId})
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
      if (this.params.projSocialId === undefined) delete this.params.projSocialId
      this[this.params.projSocialId !== undefined ? 'updProjSocial' : 'addProjSocial'](this.params)
        .then(data => {
          this.$message.success(this.params.projSocialId !== undefined ? '更新成功' : '添加成功')
          this.dLoading = false
          this.visible = false
          this.fetch()
        })
        .catch(() => {
          this.dLoading = false
        })
    },
    handleSubmit () {
      this.$refs.form.validate((valid) => {
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
        socialId: '',
        linkUrl: ''
      }
      this.validate = false
    }
  }
}
</script>

<style lang="scss">
  @import '~@/styles/variables.scss';
  .project-main{
    .fab{
      font-size: 30px;
    }
    .add-box{
      overflow: hidden;
      text-align: right;
      margin: 20px 0 25px;
    }
    .text-link, .text-error{
      cursor: pointer;
      &:hover{
        color: $main-color;
      }
    }
  }
</style>
