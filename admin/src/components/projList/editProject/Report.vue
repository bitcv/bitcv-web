<template>
  <div class="report">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showDialog = true">添加</el-button>
    </div>
    <el-table :data="reportList">
      <el-table-column label="媒体Logo">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.logoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="媒体名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="新闻标题">
        <template slot-scope="scope">{{ scope.row.title }}</template>
      </el-table-column>
      <el-table-column label="新闻链接">
        <template slot-scope="scope">{{ scope.row.linkUrl }}</template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button>
          <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="媒体报道信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <el-form-item label="媒体名称">
          <el-select v-model="inputName" @change="selectMedia" filterable allow-create placeholder="请选择或输入媒体名称" clearable>
            <el-option v-for="(media, index) in mediaList" :key="index" :value="media.name"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="媒体Logo">
          <el-upload class="upload-box" name="logo" action="/api/uploadFile" :on-success="onLogoSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="inputLogoUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="新闻标题">
          <el-input v-model="inputTitle"></el-input>
        </el-form-item>
        <el-form-item label="新闻链接">
          <el-input v-model="inputLinkUrl">
            <template slot="prepend" placeholder="请输入新闻地址">http://</template>
          </el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button @click="showDialog = false">取消</el-button>
        <el-button type="primary" @click="submit">确定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
export default {
  props: {
    projData: Object
  },
  data () {
    return {
      showDialog: false,
      inputName: '',
      inputLogoUrl: '',
      inputTitle: '',
      inputLinkUrl: '',
      reportId: '',
      mediaList: [],
      reportList: []
    }
  },
  mounted () {
    this.updateData()
  },
  watch: {
    showDialog: function (isShow) {
      if (isShow) {
        this.getMediaList()
      }
    }
  },
  methods: {
    updateData () {
      this.$http.post('/api/getProjReportList', {
        projId: this.$route.params.id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.reportList = res.data.data.dataList
        }
      })
    },
    getMediaList () {
      this.$http.get('/api/getMediaList').then((res) => {
        if (res.data.errcode === 0) {
          this.mediaList = res.data.data.dataList
        }
      })
    },
    selectMedia () {
      this.mediaList.forEach((media) => {
        if (media.name === this.inputName) {
          this.inputLogoUrl = media.logoUrl
        }
      })
    },
    onLogoSuccess (res) {
      if (res.errcode === 0) {
        this.inputLogoUrl = res.data.url
      }
    },
    showEdit (index) {
      var reportInfo = this.reportList[index]
      this.reportId = reportInfo.id
      this.inputName = reportInfo.name
      this.inputLogoUrl = reportInfo.logoUrl
      this.inputLinkUrl = reportInfo.linkUrl
      this.inputTitle = reportInfo.title
      this.showDialog = true
    },
    showDel (reportId) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delReport(reportId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    submit () {
      if (this.reportId) {
        this.updReport()
      } else {
        this.addReport()
      }
    },
    addReport () {
      this.$http.post('/api/addProjReport', {
        projId: this.$route.params.id,
        name: this.inputName,
        logoUrl: this.inputLogoUrl,
        linkUrl: this.inputLinkUrl,
        title: this.inputTitle
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.inputName = ''
          this.inputLogoUrl = ''
          this.inputLinkUrl = ''
          this.inputTitle = ''
          this.showDialog = false
          this.updateData()
        }
      })
    },
    updReport () {
      this.$http.post('/api/updProjReport', {
        reportId: this.reportId,
        name: this.inputName,
        logoUrl: this.inputLogoUrl,
        linkUrl: this.inputLinkUrl,
        title: this.inputTitle
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '更新成功!' })
          this.inputName = ''
          this.inputLogoUrl = ''
          this.inputLinkUrl = ''
          this.inputTitle = ''
          this.showDialog = false
          this.reportId = ''
          this.updateData()
        }
      })
    },
    delReport (reportId) {
      this.$http.post('/api/delProjReport', {
        reportId: reportId
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '删除成功!' })
          this.updateData()
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
