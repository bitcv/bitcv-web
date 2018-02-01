<template>
  <div class="proj-list">
    <div class="content-container">
      <div class="header-btn-area">
        <router-link to="/addProject">
          <el-button type="primary" icon="el-icon-plus">新建项目</el-button>
        </router-link>
      </div>
      <el-table :data="projList">
        <el-table-column label="项目ID" prop="id" width="80px"></el-table-column>
        <el-table-column label="项目logo" width="100px">
          <template slot-scope="scope">
            <img class="table-image" :src="scope.row.logoUrl" alt="">
          </template>
        </el-table-column>
        <el-table-column label="项目名称" prop="nameCn"></el-table-column>
        <el-table-column label="创建时间" prop="createdAt">
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <router-link :to="'/editProject/' + scope.row.id">
              <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
            </router-link>
            <el-button disabled size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      projList: []
    }
  },
  mounted () {
    var that = this
    this.$http.post('/api/getProjList', {
      pageno: 1,
      perpage: 10
    }).then(function (res) {
      var resData = res.data
      if (resData.errcode === 0) {
        that.projList = resData.data.projList
      }
    })
  }
}
</script>

<style lang="scss" scoped>
.content-container {
  padding: 20px;
}
</style>
