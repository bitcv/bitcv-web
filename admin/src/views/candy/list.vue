<template>
  <div class="project-main" v-loading="loading">
    <div v-if="projData.status && projData.contractAddr">
      <div class="add-box">
        <el-button type="warning" plain @click="visible = true; validate = true">+添加</el-button>
      </div>
      <el-table :data="list" style="width: 100%;">
        <el-table-column prop="totalAmount" label="总额度">
        </el-table-column>
        <el-table-column prop="minAmount" label="起购额度">
        </el-table-column>
        <el-table-column prop="interestRate" label="年化回报">
          <template slot-scope="scope">{{ scope.row.interestRate * 100 }}%</template>
        </el-table-column>
        <el-table-column label="接收地址">
          <template slot-scope="scope">
            <a class="link" :href="'https://etherscan.io/token/' + projData.contractAddr + '?a=' + scope.row.toAddr" target="_blank">{{ maskStr(scope.row.toAddr, 4) }}</a>
          </template>
        </el-table-column>
        <el-table-column label="状态" width="150">
          <template slot-scope="scope">
            {{['待支付', '进行中'][scope.row.status] || '-'}}
          </template>
        </el-table-column>
        <el-table-column label="操作" width="150">
          <template slot-scope="scope">
            <div v-if="scope.row.status === 0">
              <span class="text-link" @click="handlePay(scope.row)">支付</span>
              <span class="text-error" @click="handleDel(scope.row.id)" style="margin-left: 20px;">删除</span>
            </div>
            <div v-else> - </div>
          </template>
        </el-table-column>
      </el-table>

      <!-- 添加余币宝信息dialog -->
      <el-dialog title="余币宝信息" :visible.sync="visible" center @close="handleClose" v-loading="dLoading">
          <el-form label-width="120px" :model="form" status-icon :rules="rules" ref="form">
            <el-form-item label="总额度（枚）" prop="totalAmount">
              <el-input type="number" v-model="form.totalAmount"></el-input>
            </el-form-item>
            <el-form-item label="起始额度（枚）" prop="minAmount">
              <el-input type="number" v-model="form.minAmount"></el-input>
            </el-form-item>
            <el-form-item label="锁仓期" prop="lockTime">
              <el-select v-model="form.lockTime">
                <el-option v-for="(op, index) in options" :key="index" :label="op.label" :value="op.value"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item :label="'年化回报(' + form.interestRate +'%)'" prop="interestRate">
              <el-slider v-model="form.interestRate" :step="3" :min="6" :max="18" show-stops></el-slider>
            </el-form-item>
            <el-form-item label="需支付回报">
              <span>{{ form.totalAmount * form.interestRate * form.lockTime / 12}}枚</span>
            </el-form-item>
            <el-form-item label="回报打出地址" prop="fromAddr">
              <el-input v-model="form.fromAddr" placeholder="请输入您用于支付回报的钱包地址"></el-input>
            </el-form-item>
          </el-form>
        <div slot="footer">
          <el-button @click="visible = false">取消</el-button>
          <el-button type="primary" @click="handleSubmit">确定</el-button>
        </div>
      </el-dialog>

      <!-- 支付余币宝利息 -->
      <el-dialog title="请支付余币宝利息" :visible.sync="visible1" center @close="handleClose1" v-loading="dLoading1">
        <el-form label-width="80px">
          <el-form-item label="打出地址">
            <span>{{ interest.fromAddr }}</span>
          </el-form-item>
          <el-form-item label="接收地址">
            <span>{{ interest.toAddr }}</span>
          </el-form-item>
          <el-form-item label="扫码支付">
            <vue-qr :text="interest.toAddr || ''" height="160" width="160" :margin="10" class="qrcode"></vue-qr>
          </el-form-item>
          <el-form-item label="支付金额">
            <span>{{ getInterest(interest.interestRate, interest.totalAmount, interest.lockTime) }}</span>
          </el-form-item>
        </el-form>
        <el-table :data="interestList" v-if="isComfirm">
          <el-table-column label="交易金额" prop="txAmount"></el-table-column>
          <el-table-column label="交易时间" prop="txTime"></el-table-column>
          <el-table-column label="交易哈希" prop="txHash">
            <template slot-scope="scope">
              <a :href="'https://etherscan.io/tx/' + scope.row.txHash" target="_blank">{{ maskStr(scope.row.txHash, 3) }}</a>
            </template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-checkbox @change="handleChange(scope.row)"></el-checkbox>
            </template>
          </el-table-column>
        </el-table>
        <div slot="footer">
          <template v-if="!isComfirm">
            <el-button type="primary" @click="handleUpdate">开始确认</el-button>
          </template>
          <template v-else>
            <el-button type="default" @click="handleUpdate">刷新</el-button>
            <el-button type="primary" @click="handleComfirm" :disabled="isDisabled">确认</el-button>
          </template>
        </div>
      </el-dialog>
    </div>

    <div v-else style="padding: 30px 0;">
      <div class="empty" v-if="!projData.contractAddr">请在基本信息页配置通证的ERC20智能合约地址</div>
      <div class="empty" v-if="!projData.status">项目通过管理员审核后才能创建余币宝</div>
    </div>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex'
import {maskStr, getInterest} from '@/utils/utils'
import VueQr from 'vue-qr'

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
    let validAddr = (rule, val, callback) => {
      if (this.validate) {
        if (!val) {
          callback(new Error('必填'))
        } else {
          if (val.indexOf('0x') !== 0 || val.length !== 42) {
            callback(new Error('请输入正确的地址'))
          }
        }
      }
      callback()
    }
    return {
      loading: false,
      visible: false,
      dLoading: false, // dialog loading
      validate: true, // 用于识别是否需要验证（dialog关闭时，form赋空值，所以不需要验证）
      list: [],
      form: {
        minAmount: '',
        totalAmount: '',
        lockTime: 3,
        interestRate: 3,
        fromAddr: ''
      },
      rules: {
        minAmount: [ {validator: valid} ],
        totalAmount: [ {validator: valid} ],
        lockTime: [ {validator: valid} ],
        interestRate: [ {validator: valid} ],
        fromAddr: [ {validator: validAddr} ]
      },
      visible1: false, // 余币宝支付信息弹框visible
      dLoading1: false, // 余币宝支付信息弹框visible loading
      isComfirm: false, // 确认表格显示
      interest: {}, // 余币宝支付信息
      interestList: [], // 余币宝支付信息列表
      projData: {}, // 项目数据
      options: [
        {
          label: '1个月',
          value: 1
        }, {
          label: '3个月',
          value: 3
        }, {
          label: '6个月',
          value: 6
        }, {
          label: '9个月',
          value: 9
        }, {
          label: '一年',
          value: 12
        }
      ],
      selectList: [], // 被选中的list
      isDisabled: true
    }
  },
  computed: {
    ...mapState({
      userInfo: state => state.userInfo
    }),
    params () {
      return {
        projId: this.userInfo.userId,
        depositBoxId: this.interest.id,
        minAmount: this.form.minAmount,
        totalAmount: this.form.totalAmount,
        lockTime: this.form.lockTime,
        interestRate: Number(this.form.interestRate) * 0.01,
        fromAddr: this.form.fromAddr
      }
    }
  },
  created () {
    this.fetch()
    this.fetchBasic()
  },
  methods: {
    ...mapActions(['getProjBasicInfo', 'getProjDepositBoxList', 'addDepositBox', 'delDepositBox', 'getBoxTxRecordList', 'confirmBoxTx']),
    maskStr (str, number) {
      return maskStr(str, number)
    },
    getInterest (amount, interestRate, lockTime) {
      return getInterest(amount, interestRate, lockTime)
    },
    fetchBasic () {
      this.getProjBasicInfo({projId: this.userInfo.userId})
        .then((data = {}) => {
          this.projData = data
        })
        .catch(() => {})
    },
    fetch () {
      this.loading = true
      this.getProjDepositBoxList({projId: this.userInfo.userId})
        .then(({dataList = []} = {}) => {
          this.list = dataList
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
    handlePay (row) {
      this.visible1 = true
      this.isComfirm = false
      this.interest = Object.assign(this.interest, row)
    },
    handleDel (mId) {
      this.$confirm('删除后无法恢复， 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        center: true
      }).then(() => {
        this.loading = true
        this.delDepositBox({depositBoxId: mId})
          .then((data = {}) => {
            this.loading = false
            this.$message({ type: 'success', message: '删除成功' })
            this.fetch()
          })
          .catch(() => {
            this.loading = false
          })
      }).catch(() => {})
    },
    handleAdd () {
      this.dLoading = true
      if (this.params.depositBoxId === undefined) delete this.params.depositBoxId
      this.addDepositBox(this.params)
        .then(data => {
          this.$message.success('添加成功')
          this.dLoading = false
          this.visible = false
          this.fetch()
        })
        .catch(() => {
          this.dLoading = false
        })
    },
    handleSubmit () {
      this.$refs.form.validate((valid) => {
        if (valid) {
          this.handleAdd()
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    handleClose () {
      this.form = {
        minAmount: '',
        totalAmount: '',
        lockTime: 3,
        interestRate: 3,
        fromAddr: ''
      }
      this.validate = false
    },
    handleChange (row) {
      row.selected = !row.selected
      this.isDisabled = true
      this.selectList = []
      this.interestList.filter(item => {
        if (item.selected) this.selectList.push(item.id)
      })
      if (this.selectList.length) this.isDisabled = false
    },
    handleUpdate () {
      this.dLoading1 = true
      this.getBoxTxRecordList({depositBoxId: this.params.depositBoxId})
        .then(({dataList = []} = {}) => {
          this.dLoading1 = false
          this.isComfirm = true
          this.interestList = dataList
          this.interestList.length && this.interestList.map(item => this.$set(item, 'selected', false))
        })
        .catch(() => {
          this.dLoading1 = false
        })
    },
    handleComfirm () {
      this.dLoading1 = true
      this.confirmBoxTx({
        depositBoxId: this.params.depositBoxId,
        txRecordIdList: this.selectList
      })
        .then((data = {}) => {
          this.dLoading1 = false
          this.visible1 = false
          this.fetch()
        })
        .catch(() => {
          this.dLoading1 = false
        })
    },
    handleClose1 () {
      this.interest = {}
      this.isDisabled = true
    }
  }
}
</script>

<style lang="scss">
  @import '~@/styles/variables.scss';
  .project-main{
    .add-box{
      overflow: hidden;
      text-align: right;
      margin: 20px 0 25px;
    }
    .text-link, .text-error{
      cursor: pointer;
      &:hover{
        color: $main-color;
      }
    }
    .empty {
      width: 100%;
      color: $main-color;
      text-align: center;
      line-height: 50px;
    }
    .el-slider__button-wrapper{
      top: -17px;
    }
    .link{
      color: #606266;
      &:hover, &:focus{
        color: $main-color;
      }
    }
    .qrcode, .qrcode img{
      width: 150px;
      height: 150px;
    }
  }
</style>
