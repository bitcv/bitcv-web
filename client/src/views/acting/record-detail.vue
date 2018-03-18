<template>
  <div class="record-detail">
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/acting/record' }">发放记录</el-breadcrumb-item>
      <el-breadcrumb-item>发放详情</el-breadcrumb-item>
    </el-breadcrumb>
    <el-table :data="dataList" style="width: 100%">
      <el-table-column type="index" label="序号">
      </el-table-column>
      <el-table-column prop="toAddr" label="地址">
        <!--<template slot-scope="scope">-->
          <!--{{maskStr(scope.row.toAddr, 3)}}-->
        <!--</template>-->
      </el-table-column>
      <el-table-column prop="amount" label="数量"></el-table-column>
      <el-table-column prop="status" label="状态">
        <template slot-scope="scope">
          <span :class="scope.row.status === 4 ? 'text-success' : ''">
            {{['', '待发送', '待发送', '发送中', '已完成'][scope.row.status]}}
          </span>
        </template>
      </el-table-column>
    </el-table>

    <footer class="footer">
      <el-row>
        <el-col :span="6">
          <h5>总数量</h5>
          <div>
            {{totalAmount}}
            <small>枚</small>
          </div>
        </el-col>
        <el-col :span="6">
          <h5>总地址</h5>
          <div>
            {{dataCount}}
            <small>条</small>
          </div>
        </el-col>
      </el-row>
    </footer>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex'
import {maskStr} from '@/utils/utils'
export default {
  data () {
    return {
      dataList: [],
      dataCount: 0,
      process: 0,
      totalAmount: 0,
      total: {
        number: 200,
        address: 100,
        minute: 10
      }
    }
  },
  computed: {
    ...mapState({
      taskId: state => state.route.params.taskId
    })
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
  methods: {
    ...mapActions(['getDispenseList']),
    fetch () {
      console.log('fetch')
      this.getDispenseList({
        taskId: this.taskId
      }).then((data = {}) => {
        this.dataList = data.dataList
        this.dataCount = data.dataCount
        this.process = data.process
        this.totalAmount = data.totalAmount
      })
    },
    maskStr (str, number) {
      return maskStr(str, number)
    }
  }
}
</script>

<style lang="scss">
.record-detail{
  padding: 80px 0;
  .el-breadcrumb::after, .el-breadcrumb::before{
    display: none;
  }
  .el-table{
    margin-top: 30px;
  }
  .footer{
    background: #fdf6ec;
    margin-top: 30px;
    padding: 20px;
    .el-col-6{
      text-align: center;
      h5{
        color: #999;
      }
      div{
        font-size: 18px;
        color: orange;
        small{
          font-size: 12px;
          color: #ccc;
        }
      }
    }
  }
}
</style>
