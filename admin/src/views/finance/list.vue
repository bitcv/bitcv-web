<template>
  <div class="project-main" v-loading="loading">
    <div class="add-box">
        <!-- <el-button type="warning" @click="exportExcel">导出</el-button> -->
        <!-- <a href="/api/exportRecords"><el-button type="warning">导出</el-button></a> -->
    </div>
    <el-table :data="list" style="width: 100%;">
      <el-table-column prop="id" label="ID">
      </el-table-column>
      <el-table-column prop="transaction_hash" label="交易哈希">
      </el-table-column>
      <el-table-column prop="from_addr" label="从">
      </el-table-column>
      <el-table-column prop="to_addr" label="到">
      </el-table-column>
      <el-table-column prop="timeformat" label="时间">
      </el-table-column>
      <el-table-column prop="walletName" label="地址名称">
      </el-table-column>
      <el-table-column prop="typename" label="类型">
      </el-table-column>
      <el-table-column prop="usedname" label="用于">
      </el-table-column>
      <el-table-column prop="realamount" label="数量">
      </el-table-column>
      <el-table-column prop="actualgasprice" label="矿工费">
      </el-table-column>
      <el-table-column prop="tokensubjectname" label="项目主体"></el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
            <el-button type="success" @click="editShow(scope.row, scope.$index)" >标记</el-button>
        </template>
      </el-table-column>
   </el-table>

    <!--page-->
    <el-pagination v-if="list && list.length > 0" class="footer-page-box" @size-change="onOrderSizeChange" @current-change="onOrderCurChange" :current-page="orderPageno" :page-sizes="[10, 20, 30, 40]" :page-size="orderPerpage" layout="total, sizes, prev, pager, next, jumper" :total="orderDataCount">
    </el-pagination>
    <!--page-->

   <el-dialog title="交易记录" :visible.sync="visible" center v-loading="loading">

    <el-form label-width="120px" :model="form" ref="form">
      <el-form-item label="交易哈希 : ">
        {{ editObj.transaction_hash}}
      </el-form-item>
      <el-form-item label="类型 : ">
        <el-select v-model="form.tokentype" placeholder="请选择">
          <el-option v-for="(op, index) in tokentype" :key="index" :label="op" :value="index">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="费用性质 : ">
        <el-select v-model="form.used" placeholder="请选择">
          <el-option v-for="(op, index) in options" :key="index" :label="op" :value="index">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="项目主体 : ">
          <el-input v-model="form.tokensubject" placeholder="请输入内容"></el-input>
      </el-form-item>
      <el-form-item label="备注 : ">
        <el-input v-model="form.memo" placeholder="请输入内容"></el-input>
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button @click="visible = false">取 消</el-button>
      <el-button type="primary" @click="submitForm">确 定</el-button>
    </span>
   </el-dialog>
  </div>
</template>

<script>
import VueQr from 'vue-qr'
import {mapActions} from 'vuex'

export default {
  components: {
    VueQr
  },
  data () {
    let valid = (rule, val, callback) => {
      if (this.validate) {
        if (!val) {
          callback(new Error('必填'))
        }
      }
      callback()
    }
    return {
      loading: false,
      visible: false,
      list: [],
      orderPageno: 1,
      orderPerpage: 10,
      orderDataCount: 0,
      listIndex: 0,
      editObj: [],
      options: [],
      tokentype: [],
      tokensubject: '',
      memo: '',
      input: '',
      form: {
        used: '',
        tokentype: '',
        tokensubject: ''
      },
      rules: {
        used: [ {validator: valid} ]
      }
    }
  },
  mounted () {
    this.fetch()
    this.getCount()
  },
  computed: {
    params () {
      return {
        transaction_hash: this.editObj.transaction_hash,
        used: this.form.used,
        type: this.form.tokentype,
        tokensubject: this.form.tokensubject,
        memo: this.form.memo
      }
    }
  },
  methods: {
    ...mapActions([
      'getFinanceList',
      'getDetailByHash',
      'getFinanceCount',
      'updateRecords',
      'exportRecords'
    ]),
    editShow (row, _index) {
      this.listIndex = _index
      this.editObj = row
      this.visible = true
      this.form = Object.assign(this.form, row)
      this.form.used = this.form.usedname
      this.form.tokentype = this.form.typename
    },
    exportExcel () {
      this.exportRecords()
        .then(() => {
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
    fetch () {
      this.loading = true
      this.getFinanceList({
        pageno: this.orderPageno,
        perpage: this.orderPerpage
      })
        .then(({dataList = [], options = [], tokentype = [], tokensubject = ''} = {}) => {
          this.list = dataList
          this.options = options
          this.tokentype = tokentype
          this.tokensubject = tokensubject
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
    getCount () {
      this.getFinanceCount()
        .then(({totalCount = 0}) => {
          this.orderDataCount = totalCount
        })
    },
    onOrderSizeChange (perpage) {
      this.orderPerpage = perpage
      this.fetch()
    },
    onOrderCurChange (pageno) {
      this.orderPageno = pageno
      this.fetch()
    },
    handleAdd () {
      this.updateRecords(this.params)
        .then(data => {
          this.$message.success('标记成功')
          this.loading = false
          this.visible = false
          this.fetch()
        })
        .catch(() => {
          this.loading = false
        })
    },
    submitForm () {
      this.$refs.form.validate((valid) => {
        if (valid) {
          this.handleAdd()
        } else {
          return false
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
  .footer-page-box {
    text-align: center;
    margin-top: 40px;
  }
  .add-box{
      overflow: hidden;
      text-align: right;
      margin: 20px 0 25px;
    }
</style>
