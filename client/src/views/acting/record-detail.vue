<template>
  <div class="record-detail">
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/acting/actingRecord' }">发放记录</el-breadcrumb-item>
      <el-breadcrumb-item>发放详情</el-breadcrumb-item>
    </el-breadcrumb>
    <el-table
      :data="list"
      style="width: 100%">
      <el-table-column
        prop="id"
        label="序号">
      </el-table-column>
      <el-table-column
        prop="address"
        label="地址">
        <template slot-scope="scope">
          {{maskStr(scope.row.address, 3)}}
        </template>
      </el-table-column>
      <el-table-column
        prop="number"
        label="数量">
      </el-table-column>
      <el-table-column
        prop="status"
        label="状态">
        <template slot-scope="scope">
          <span :class="scope.row.status === 1 ? 'text-success' : ''">
            {{['验证中', '已完成'][scope.row.status]}}
          </span>
        </template>
      </el-table-column>
    </el-table>

    <footer class="footer">
      <el-row>
        <el-col :span="6">
          <h5>总数量</h5>
          <div>
            {{total.number}}
            <small>枚</small>
          </div>
        </el-col>
        <el-col :span="6">
          <h5>总地址</h5>
          <div>
            {{total.address}}
            <small>条</small>
          </div>
        </el-col>
      </el-row>
    </footer>
  </div>
</template>

<script>
import {mapState} from 'vuex'
import {maskStr} from '@/utils/utils'
export default {
  data () {
    return {
      list: [
        {
          id: 9,
          address: 'ajdssajsdasn8988899',
          number: 300,
          status: 1,
          time: '2017-09-09'
        },
        {
          id: 9,
          address: 'ajdssajsdasn8988899',
          number: 300,
          status: 0,
          time: '2017-09-09'
        }
      ],
      total: {
        number: 200,
        address: 100,
        minute: 10
      }
    }
  },
  computed: {
    ...mapState({
      path: state => state.route.path
    })
  },
  created () {
    console.log(this.$route.query)
  },
  methods: {
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
