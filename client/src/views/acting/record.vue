<template>
  <div class="record">
    <h5 class="title">
      发放记录
    </h5>
    <el-table
      :data="list"
      style="width: 100%">
      <el-table-column
        prop="id"
        label="序号">
      </el-table-column>
      <el-table-column
        prop="currency"
        label="币种">
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
      <el-table-column
        prop="time"
        label="时间">
        <template slot-scope="scope">
          <span v-if="scope.row.status">
            {{scope.row.time}}
          </span>
          <el-progress v-else :percentage="56"></el-progress>
        </template>
      </el-table-column>
      <el-table-column
        label="">
        <template slot-scope="scope">
          <router-link :to="{path: '/acting/actingRecord/detail', query: {id: scope.row.id}}">
            <i class="el-icon-arrow-right"></i>
          </router-link>
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
        <el-col :span="8">
          <el-progress :percentage="56"></el-progress>
        </el-col>
        <el-col :span="4" class="text-gray">预计还有<span>{{total.minute}}</span>分钟</el-col>
      </el-row>
    </footer>
  </div>
</template>

<script>
import {mapState} from 'vuex'
export default {
  data () {
    return {
      list: [
        {
          id: 9,
          currency: 'BTH',
          number: 300,
          status: 1,
          time: '2017-09-09'
        },
        {
          id: 9,
          currency: 'BTH',
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
  },
  methods: {
  }
}
</script>

<style lang="scss">
@import '~@/styles/variables';
.record{
  padding: 80px 0;
  h5.title{
    margin-top: 50px;
    i{
      margin-left: 10px;
      padding: 3px;
      background: #fcf7f1;
      border: 1px solid $primary-color;
      color: $primary-color;
    }
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
    .el-col-8{
      .el-progress{
        margin-top: 25px;
      }
    }
    .el-col-4{
      line-height: 65px;
      font-size: 12px;
      span{
        color: $primary-color;
      }
    }
  }
  .el-icon-arrow-right{
    cursor: pointer;
    &:hover{
      color: $primary-color;
    }
  }
  @media (max-width: 767px) {
    .footer{
      padding: 20px 0;
      .el-col-4{
        padding-top: 15px;
        line-height: 20px;
      }
    }
  }
}
</style>
