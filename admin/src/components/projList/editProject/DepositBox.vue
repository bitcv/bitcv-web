<template>
  <div class="deposit-box">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showAdd">添加</el-button>
    </div>
    <el-table :data="depositBoxList">
      <el-table-column label="总额度">
        <template slot-scope="scope">{{ scope.row.totalAmount }}</template>
      </el-table-column>
      <el-table-column label="起购额度">
        <template slot-scope="scope">{{ scope.row.minAmount }}</template>
      </el-table-column>
      <el-table-column label="回报率">
        <template slot-scope="scope">{{ scope.row.interestRate }}</template>
      </el-table-column>
      <el-table-column label="收币地址">
        <template slot-scope="scope">{{ scope.row.toAddr }}</template>
      </el-table-column>
      <el-table-column label="状态">
        <template slot-scope="scope">{{ scope.row.status }}</template>
      </el-table-column>
      <!--<el-table-column label="操作">-->
        <!--<template slot-scope="scope">-->
          <!--<el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button>-->
          <!--<el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>-->
        <!--</template>-->
      <!--</el-table-column>-->
    </el-table>
    <el-dialog title="糖果盒信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <el-form-item label="总额度">
          <el-input v-model="inputTotalAmount"></el-input>
        </el-form-item>
        <el-form-item label="起始额度">
          <el-input v-model="inputMinAmount"></el-input>
        </el-form-item>
        <el-form-item label="锁仓期">
          <el-input v-model="inputLockTime"></el-input>
        </el-form-item>
        <el-form-item label="回报利率">
          <el-input v-model="inputInterestRate"></el-input>
        </el-form-item>
        <el-form-item label="打币地址">
          <el-input v-model="inputFromAddr"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button @click="showDialog = false">取消</el-button>
        <el-button type="primary" @click="submit">确定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
export default {
  props: {
    projData: Object
  },
  data () {
    return {
      showDialog: false,
      inputTotalAmount: '',
      inputMinAmount: '',
      inputLockTime: '',
      inputInterestRate: '',
      inputFromAddr: '',
      depositBoxList: [],
      fromAddr: '',
      toAddr: '',
      totalInterest: '',
      depositBoxId: ''
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      this.$http.post('/api/getProjDepositBoxList', {
        projId: this.$route.params.id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.depositBoxList = res.data.data.dataList
        }
      })
    },
    getMediaList () {
      this.$http.get('/api/getMediaList').then((res) => {
        if (res.data.errcode === 0) {
          this.mediaList = res.data.data.dataList
        }
      })
    },
    showAdd () {
      this.depositBoxId = ''
      this.inputTotalAmount = ''
      this.inputMinAmount = ''
      this.inputLockTime = ''
      this.inputInterestRate = ''
      this.inputFromAddr = ''
      this.showDialog = true
    },
    showEdit (index) {
      var depositBox = this.depositBoxList[index]
      this.projReportId = depositBox.id
      this.inputTotalAmount = depositBox.totalAmount
      this.inputMinAmount = depositBox.minAmount
      this.inputLockTime = depositBox.lockTime
      this.inputInterestRate = depositBox.interestRate
      this.fromAddr = depositBox.fromAddr
      this.showDialog = true
    },
    showDel (depositBoxId) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delReport(depositBoxId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    submit () {
      if (this.depositBoxId) {
        this.updDepositBox()
      } else {
        this.addDepositBox()
      }
    },
    addDepositBox () {
      this.$http.post('/api/addDepositBox', {
        projId: this.$route.params.id,
        minAmount: this.inputMinAmount,
        lockTime: this.inputLockTime,
        totalAmount: this.inputTotalAmount,
        interestRate: this.inputInterestRate,
        fromAddr: this.inputFromAddr
      }).then((res) => {
        console.log(res)
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.showDialog = false
          this.updateData()
        }
      })
    },
    updDepositBox () {
      this.$http.post('/api/updDepositBox', {
        projReportId: this.projReportId,
        mediaId: this.inputMediaId,
        linkUrl: this.inputLinkUrl,
        title: this.inputTitle
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '更新成功!' })
          this.showDialog = false
          this.updateData()
        }
      })
    },
    delReport (depositBoxId) {
      this.$http.post('/api/delDepositBox', {
        depositBoxId: depositBoxId
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '删除成功!' })
          this.updateData()
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
