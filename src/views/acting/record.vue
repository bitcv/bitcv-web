<template>
  <div class="record" v-loading="tableLoad">
    <h5 class="title">
      {{ $t('label.fa_record') }}
    </h5>
    <el-table :data="dataList" style="width: 100%" @row-click="openDetails">
      <el-table-column type="index" :label="$t('label.assest_num')"></el-table-column>
      <el-table-column prop="symbol" :label="$t('label.coin_type')"></el-table-column>
      <el-table-column prop="totalAmount" :label="$t('label.coin_num')"></el-table-column>
      <el-table-column prop="status" :label="$t('label.coin_status')">
        <template slot-scope="scope">
          <span v-if="language === 'cn'" :class="scope.row.status === 1 ? 'text-success' : ''">
            {{['', '发放中', '已完成'][scope.row.status]}}
          </span>
          <span v-else-if="language === 'en'" :class="scope.row.status === 1 ? 'text-success' : ''">
            {{['', 'Issuing', 'Completed'][scope.row.status]}}
          </span>
          <span v-else :class="scope.row.status === 1 ? 'text-success' : ''">
            {{['', '発行', '完了しました'][scope.row.status]}}
          </span>
        </template>
      </el-table-column>
      <el-table-column prop="process" :label="$t('label.process')">
        <template slot-scope="scope">
          <!--<span v-if="scope.row.process">-->
            <!--{{scope.row.time}}-->
          <!--</span>-->
          <el-progress :percentage="parseInt(scope.row.process * 100)"></el-progress>
        </template>
      </el-table-column>
      <el-table-column prop="createdAt" :label="$t('label.create_time')" width="170"></el-table-column>
      <el-table-column label="">
        <template slot-scope="scope">
          <router-link :to="{path: '/acting/record/detail/' + scope.row.taskId}">
            <i class="el-icon-arrow-right"></i>
          </router-link>
        </template>
      </el-table-column>
    </el-table>

    <!--<footer class="footer">-->
      <!--<el-row>-->
        <!--<el-col :span="6">-->
          <!--<h5>总数量</h5>-->
          <!--<div>{{total.number}}-->
            <!--<small>枚</small>-->
          <!--</div>-->
        <!--</el-col>-->
        <!--<el-col :span="6">-->
          <!--<h5>总地址</h5>-->
          <!--<div>-->
            <!--{{total.address}}-->
            <!--<small>条</small>-->
          <!--</div>-->
        <!--</el-col>-->
        <!--<el-col :span="8">-->
          <!--<el-progress :percentage="56"></el-progress>-->
        <!--</el-col>-->
        <!--<el-col :span="4" class="text-gray">预计还有<span>{{total.minute}}</span>分钟</el-col>-->
      <!--</el-row>-->
    <!--</footer>-->
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex'
export default {
  data () {
    return {
      dataList: [],
      tableLoad: false
    }
  },
  computed: {
    ...mapState({
      path: state => state.route.path
    }),
    language () {
      return this.$i18n.locale
    }
  },
  mounted () {
    this.fetch()
  },
  methods: {
    ...mapActions(['getUserTaskList']),
    fetch () {
      this.tableLoad = true
      this.getUserTaskList().then((data = {}) => {
        this.dataList = data.dataList
        this.tableLoad = false
      })
    },
    openDetails(row, column) {
      this.$router.push('/acting/record/detail/' + row.taskId )
    }
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
  tbody {
    cursor: pointer;
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
