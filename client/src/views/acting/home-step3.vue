<template>
  <div class="step3">
    <h5>{{isFinished ? '发放完成' : '发放中'}}</h5>
    <el-table :data="dataList" style="width: 100%">
      <el-table-column type="index" label="序号" width="100"></el-table-column>
      <el-table-column prop="toAddr" label="地址">
        <template slot-scope="scope">
          {{maskStr(scope.row.toAddr, 3)}}
        </template>
      </el-table-column>
      <el-table-column prop="amount" label="数量" width="180"></el-table-column>
      <el-table-column prop="status" label="状态" width="180">
        <template slot-scope="scope">
          <span :class="scope.row.status === 4 ? 'text-success' : ''">
            {{['', '待发送', '待发送', '转账中', '已完成'][scope.row.status]}}
          </span>
        </template>
      </el-table-column>
    </el-table>

    <footer>
      <el-row>
        <el-col :span="16">
          <el-progress :percentage="process * 100"></el-progress>
        </el-col>
        <el-col :span="8" class="text-warning">下载完成报告</el-col>
      </el-row>
    </footer>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex'
import {maskStr} from '@/utils/utils'

export default {
  props: {
    taskId: Number
  },
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
      ],
      dataList: [],
      process: 0,
      timerId: ''
    }
  },
  mounted () {
    this.fetch()
    if (!this.timerId) {
      this.timerId = setInterval(() => {
        this.fetch()
      }, 30000)
    }
  },
  beforeDestroy () {
    if (this.timerId) {
      clearInterval(this.timerId)
    }
  },
  computed: {
    ...mapState({
      path: state => state.route.path
    })
  },
  methods: {
    ...mapActions(['getDispenseList']),
    fetch () {
      console.log('fetch')
      this.getDispenseList({
        taskId: this.taskId
      }).then((data = {}) => {
        this.dataList = data.dataList
        this.process = data.process
      })
    },
    maskStr (str, number) {
      return maskStr(str, number)
    }
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
