<template>
  <div class="step3">
    <h5>{{isFinished ? '发放完成' : '发放中'}}</h5>
    <el-table
      :data="list"
      style="width: 100%">
      <el-table-column
        prop="id"
        label="序号"
        width="100">
      </el-table-column>
      <el-table-column
        prop="address"
        label="地址">
      </el-table-column>
      <el-table-column
        prop="number"
        label="数量"
        width="180">
      </el-table-column>
      <el-table-column
        prop="status"
        label="状态"
        width="180">
        <template slot-scope="scope">
          <span :class="scope.row.status === 1 ? 'text-success' : ''">
            {{['发放中', '已完成'][scope.row.status]}}
          </span>
        </template>
      </el-table-column>
    </el-table>

    <footer>
      <el-row>
        <el-col :span="16">
          <el-progress :percentage="56"></el-progress>
        </el-col>
        <el-col :span="8" class="text-warning">下载完成报告</el-col>
      </el-row>
    </footer>
  </div>
</template>

<script>
import {mapState} from 'vuex'

export default {
  data () {
    return {
      isFinished: false,
      list: [
        {
          id: 9,
          address: 'sjjdhdhd',
          number: 300,
          status: 1
        }
      ]
    }
  },
  computed: {
    ...mapState({
      path: state => state.route.path
    })
  }
}
</script>

<style lang="scss">
.step3{
  padding: 80px 0;
  footer{
    background: #fcf7f1;
    margin:20px 0;
    padding: 30px;
    line-height: 30px;
    .el-progress{
      margin-top: 10px;
      width: 60%;
    }
    .text-warning{
      color: orange;
      text-align: center;
    }
  }
}
</style>
