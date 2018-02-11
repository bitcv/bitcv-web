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
      <el-table-column label="接收地址">
        <template slot-scope="scope">{{ getShortStr(scope.row.toAddr, 6) }}</template>
      </el-table-column>
      <el-table-column label="操作或状态">
        <template slot-scope="scope">
          <div v-if="scope.row.status===0">
            <el-button size="mini" @click="showOrderDialog(scope.$index)">付款</el-button>
            <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
          </div>
          <div v-else>{{scope.row.status === 1 ? '进行中' : scope.row.status}}</div>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog title="余币宝信息" :visible.sync="showDialog" center>
      <el-form label-width="100px">
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
        <el-form-item label="回报打出地址">
          <el-input v-model="inputFromAddr" placeholder="请输入您用于支付回报的钱包地址"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button @click="showDialog = false">取消</el-button>
        <el-button type="primary" @click="submit">确定</el-button>
      </div>
    </el-dialog>

    <el-dialog title="请支付余币宝利息" :visible.sync="showOrder" center>
      <el-form label-width="80px">
        <el-form-item label="打出地址">
          <span>{{ fromAddr }}</span>
        </el-form-item>
        <el-form-item label="接收地址">
          <span>{{ toAddr }}</span>
        </el-form-item>
        <el-form-item label="扫码支付">
          <div id="qrcode" v-if="clear"></div>
        </el-form-item>
        <el-form-item label="支付金额">
          <span>{{ totalInterest }}</span>
        </el-form-item>
      </el-form>
      <el-table :data="txRecordList" v-if="inScan">
        <el-table-column label="交易金额" prop="txAmount"></el-table-column>
        <el-table-column label="交易时间" prop="txTime"></el-table-column>
        <el-table-column label="交易哈希" prop="txHash"></el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-checkbox @change="selectRecord($event, scope.row.id)"></el-checkbox>
          </template>
        </el-table-column>
      </el-table>
      <div slot="footer">
        <template v-if="!inScan">
          <el-button type="primary" @click="getTxRecord">开始确认</el-button>
        </template>
        <template v-else>
          <el-button type="default" @click="getTxRecord">刷新</el-button>
          <el-button type="primary" @click="confirmTx">确认</el-button>
        </template>
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
      depositBoxId: '',
      showOrder: false,
      inScan: false,
      txRecordList: [],
      txRecordIdList: [],
      hasQrcode: false,
      clear: true
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
    showAdd () {
      this.depositBoxId = ''
      this.inputTotalAmount = ''
      this.inputMinAmount = ''
      this.inputLockTime = ''
      this.inputInterestRate = ''
      this.inputFromAddr = ''
      this.showDialog = true
    },
    showOrderDialog (index) {
      var depositBox = this.depositBoxList[index]
      this.fromAddr = depositBox.fromAddr
      this.toAddr = depositBox.toAddr
      this.depositBoxId = depositBox.id
      this.totalInterest = depositBox.interestRate * depositBox.totalAmount
      this.inScan = false
      this.showOrder = true
      // if (!this.hasQrcode) {
      require('@/components/common/jquery.min.js')
      $("#qrcode").html("")
      this.getQrcode()
        // this.hasQrcode = true
      // }
    },
    showDel (depositBoxId) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delDepositBox(depositBoxId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    submit () {
      this.addDepositBox()
    },
    getQrcode () {
      require('@/components/common/qrcode.min.js')
      // eslint-disable-next-line
      $('#qrcode').qrcode({
        text: this.toAddr,
        width: 150,
        height: 150
      })
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
    delDepositBox (depositBoxId) {
      this.$http.post('/api/delDepositBox', {
        depositBoxId: depositBoxId
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '删除成功!' })
          this.updateData()
        }
      })
    },
    getTxRecord () {
      this.$http.post('/api/getBoxTxRecordList', {
        depositBoxId: this.depositBoxId
      }).then((res) => {
        console.log(res)
        if (res.data.errcode === 0) {
          this.txRecordList = res.data.data.dataList
          this.inScan = true
        }
      })
    },
    selectRecord (e, txRecordId) {
      if (e) {
        this.txRecordIdList.push(txRecordId)
      } else {
        this.txRecordIdList.remove(txRecordId)
      }
    },
    confirmTx () {
      this.$http.post('/api/confirmBoxTx', {
        depositBoxId: this.depositBoxId,
        txRecordIdList: this.txRecordIdList
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.showOrder = false
          this.updateData()
        } else {
          alert(res.data.errmsg)
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
