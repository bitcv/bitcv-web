<template>
  <div class="project-main" v-loading="loading">
    <div class="add-box">
      <el-button type="warning" plain @click="visible = true; validate = true">+添加历程</el-button>
    </div>
    <el-table :data="list" style="width: 100%;">
      <el-table-column prop="occurTime" label="时间">
      </el-table-column>
      <el-table-column prop="title" label="标题">
      </el-table-column>
      <el-table-column prop="detail" label="简介">
      </el-table-column>
      <el-table-column label="操作" width="150">
        <template slot-scope="scope">
          <span class="text-link" @click="handleEdit(scope.row)">编辑</span>
          <span class="text-error" @click="handleDel(scope.row.id)" style="margin-left: 20px;">删除</span>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog title="项目重大事件" :visible.sync="visible" center @close="handleClose" v-loading="dLoading">
        <el-form label-width="80px" :model="form" status-icon :rules="rules" ref="form">
          <el-form-item label="时间" prop="occurTime">
            <el-date-picker v-model="form.occurTime" type="datetime" :default-value="new Date()" placeholder="请选择时间"></el-date-picker>
          </el-form-item>
          <el-form-item label="标题" prop="title">
            <el-input v-model="form.title"></el-input>
          </el-form-item>
          <el-form-item label="简介" prop="detail">
            <el-input type="textarea" v-model="form.detail" :autosize="{minRows: 3, maxRows: 5}"></el-input>
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
      form: {
        occurTime: '',
        title: '',
        detail: ''
      },
      rules: {
        occurTime: [ {validator: valid} ],
        title: [ {validator: valid} ],
        detail: [ {validator: valid} ]
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
        eventId: this.form.id,
        occurTime: this.form.occurTime,
        title: this.form.title,
        detail: this.form.detail
      }
    }
  },
  created () {
    this.fetch()
  },
  methods: {
    ...mapActions(['getProjEventList', 'addProjEvent', 'updProjEvent', 'delProjEvent']),
    fetch () {
      this.loading = true
      this.getProjEventList({projId: this.userInfo.userId})
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
      this.form.occurTime = new Date(this.form.occurTime)
      console.log(new Date(this.form.occurTime))
    },
    handleDel (mId) {
      this.$confirm('删除后无法恢复， 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        center: true
      }).then(() => {
        this.loading = true
        this.delProjEvent({eventId: mId})
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
      if (this.params.eventId === undefined) delete this.params.eventId
      this[this.params.eventId !== undefined ? 'updProjEvent' : 'addProjEvent'](this.params)
        .then(data => {
          this.$message.success(this.params.eventId !== undefined ? '更新成功' : '添加成功')
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
          if (this.form.id) this.form.eventId = this.form.id
          this.handleAdd()
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    handleClose () {
      this.form = {
        occurTime: '',
        title: '',
        detail: ''
      }
      this.validate = false
    }
  }
}
</script>

<style lang="scss">
  @import '~@/styles/variables.scss';
  .project-main{
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
