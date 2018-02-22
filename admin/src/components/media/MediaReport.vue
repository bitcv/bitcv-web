<template>
  <div class="media">
    <!-- <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showAdd">添加</el-button>
    </div> -->
    <el-table :data="mediaList">
      <el-table-column label="媒体Logo">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.logoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="项目ID">
        <template slot-scope="scope">{{ scope.row.proj_id }}</template>
      </el-table-column>
      <el-table-column label="社交ID">
        <template slot-scope="scope">{{ scope.row.social_id }}</template>
      </el-table-column>
      <el-table-column label="主页">
        <template slot-scope="scope">{{ scope.row.home_url }}</template>
      </el-table-column>
      <el-table-column label="引用链接">
        <template slot-scope="scope">{{ scope.row.refer_url }}</template>
      </el-table-column>
      <el-table-column label="标题">
        <template slot-scope="scope">{{ scope.row.title }}</template>
      </el-table-column>
      <el-table-column label="名称">
        <template slot-scope="scope">{{ scope.row.official_name }}</template>
      </el-table-column>
      <el-table-column label="LogoURL">
        <template slot-scope="scope">{{ scope.row.logo_url }}</template>
      </el-table-column>
      <el-table-column label="内容">
        <template slot-scope="scope">{{ scope.row.content }}</template>
      </el-table-column>
      <el-table-column label="发布时间">
        <template slot-scope="scope">{{ scope.row.created_at }}</template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <!-- <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button> -->
          <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>
<script>
export default {
  data () {
    return {
      mediaReportList: []
    }
  },
  mounted () {
      this.updateData()
  },
  methods: {
    updateData (){
      this.$http.get('/api/getMediaReportList').then((res) => {
        if (res.data.errcode === 0) {
          this.mediaReportList = res.data.data.dataList
        }
      })
    },
    showDel (id) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delMedia(mediaId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    delMediaReport (id){
      this.$http.post('/api/delMediaReport', {
        mediaId: mediaId
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
