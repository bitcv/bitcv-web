<template>
  <div class="project-main" v-loading="loading">
    <div class="add-box">
      <el-button type="warning" plain @click="visible = true; validate = true">+添加报道</el-button>
    </div>
    <el-table :data="list" style="width: 100%;">
      <el-table-column prop="logoUrl" label="媒体logo" class-name="header">
        <template slot-scope="scope">
          <img :src="scope.row.logoUrl" :alt="scope.row.name + 'logo'" width="60" />
        </template>
      </el-table-column>
      <el-table-column prop="name" label="媒体名称">
      </el-table-column>
      <el-table-column prop="title" label="新闻标题">
      </el-table-column>
      <el-table-column prop="linkUrl" label="新闻链接">
      </el-table-column>
      <el-table-column label="操作" width="150">
        <template slot-scope="scope">
          <span class="text-link" @click="handleEdit(scope.row)">编辑</span>
          <span class="text-error" @click="handleDel(scope.row.id)" style="margin-left: 20px;">删除</span>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog title="媒体报道信息" :visible.sync="visible" center @close="handleClose" v-loading="dLoading">
        <el-form label-width="80px" :model="form" status-icon :rules="rules" ref="form">
          <el-form-item label="媒体名称" prop="mediaId">
            <el-select v-model="form.mediaId" placeholder="请选择社交平台">
              <el-option v-for="(op, index) in options" :key="index" :label="op.name" :value="op.id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="新闻标题" prop="title">
            <el-input v-model="form.title"></el-input>
          </el-form-item>
          <el-form-item label="新闻链接" prop="linkUrl">
            <el-input v-model="form.linkUrl" placeholder="请输入新闻地址">
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
        mediaId: '',
        title: '',
        linkUrl: ''
      },
      rules: {
        mediaId: [ {validator: valid} ],
        title: [ {validator: valid} ],
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
        mediaId: this.form.mediaId,
        title: this.form.title,
        linkUrl: this.form.linkUrl,
        projReportId: this.form.id
      }
    }
  },
  created () {
    this.fetch()
  },
  methods: {
    ...mapActions(['getProjReportList', 'getMediaList', 'addProjReport', 'updProjReport', 'delProjReport']),
    fetch () {
      this.loading = true
      this.getProjReportList({projId: this.userInfo.userId})
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
      this.getMediaList()
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
      console.log(this.form)
    },
    handleDel (mId) {
      this.$confirm('删除后无法恢复， 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        center: true
      }).then(() => {
        this.loading = true
        this.delProjReport({projReportId: mId})
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
      if (this.params.projReportId === undefined) delete this.params.projReportId
      this[this.params.projReportId !== undefined ? 'updProjReport' : 'addProjReport'](this.params)
        .then(data => {
          this.$message.success(this.params.projReportId !== undefined ? '更新成功' : '添加成功')
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
          if (this.form.id) this.form.projReportId = this.form.id
          this.handleAdd()
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    handleClose () {
      this.form = {
        mediaId: '',
        title: '',
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

    .text-link, .text-error{
      cursor: pointer;
      &:hover{
        color: $main-color;
      }
    }
  }
</style>
