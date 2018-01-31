<template>
  <div class="proj-list">
    <div class="content-container">
      <div class="header-btn-area">
        <router-link to="/addProject/basic">
          <el-button type="primary" icon="el-icon-plus">新建项目</el-button>
        </router-link>
      </div>
      <el-table :data="projList">
        <el-table-column label="项目ID" prop="id">
        </el-table-column>
        <el-table-column label="项目logo" prop="logoUrl">
        </el-table-column>
        <el-table-column label="项目名称" prop="name">
        </el-table-column>
        <el-table-column label="货币名称" prop="tokenName">
        </el-table-column>
        <el-table-column label="货币符号" prop="tokenSymbol">
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button disabled size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
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
      projList: [{
        id: 123,
        logoUrl: 'testLogo',
        name: 'demo',
        tokenName: '比特币',
        tokenSymbol: 'BTC'
      }]
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
