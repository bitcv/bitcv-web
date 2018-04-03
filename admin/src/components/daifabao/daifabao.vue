<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <el-tab-pane label="代发宝列表">
        <el-form :inline="true" label-width="100px">
          <el-form-item label="交易哈希:">
            <el-input placeholder="请输入交易哈希值"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="success">搜索</el-button>
          </el-form-item>
        </el-form>
        <el-table>
          <el-table-column label="ID">
            <template slot-scope="scope"></template>
          </el-table-column>
          <el-table-column label="交易哈希">
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button type="success" size="mini">标记</el-button>
              <el-button size="mini" type="danger">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
        <!--Pagination-->
        <!-- <el-pagination v-if="financeList && financeList.length > 0" class="footer-page-box" @size-change="onBoxSizeChange" @current-change="onBoxCurChange" :current-page="pageno" :page-sizes="[10, 20, 30, 40]" :page-size="perpage" layout="total, sizes, prev, pager, next, jumper" :total="dataCount">
        </el-pagination> -->
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<script>
export default {
  data () {
    return {
    }
  },
  mounted () {
  },
  methods: {
  }
}

</script>
<style lang="scss">
</style>
