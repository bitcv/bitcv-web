<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <el-tab-pane label="余币宝列表">
        <el-table :data="depositBoxList">
          <!--<el-table-column type="expand">-->
            <!--<template slot-scope="props">-->
              <!--<el-form label-position="left" inline>-->
                <!--<el-form-item label="项目名称">-->
                  <!--<span>{{ props.row.projId }}</span>-->
                <!--</el-form-item>-->
              <!--</el-form>-->
            <!--</template>-->
          <!--</el-table-column>-->
          <el-table-column type="index"></el-table-column>
          <el-table-column label="项目名称" prop="nameCn">
            <template slot-scope="scope">
              <a class="link" :href="'/projDetail/info/' + scope.row.projId" target="_blank">{{ scope.row.nameCn }}</a>
            </template>
          </el-table-column>
          <el-table-column label="总额度" prop="totalAmount"></el-table-column>
          <el-table-column label="起始额度" prop="minAmount"></el-table-column>
          <el-table-column label="剩余额度" prop="remainAmount"></el-table-column>
          <el-table-column label="年化回报">
            <template slot-scope="scope">{{ scope.row.interestRate * 100 }}%</template>
          </el-table-column>
          <el-table-column label="接收地址">
            <template slot-scope="scope">
              <a class="link" :href="'https://etherscan.io/token/' + scope.row.contractAddr + '?a=' + scope.row.toAddr" target="_blank">{{ getShortStr(scope.row.toAddr, 5) }}</a>
            </template>
          </el-table-column>
          <el-table-column label="状态">
            <template slot-scope="scope">
              {{ boxStatusDict[scope.row.status] }}
            </template>
          </el-table-column>
        </el-table>

        <!--Pagination-->
        <el-pagination class="footer-page-box" @size-change="onBoxSizeChange" @current-change="onBoxCurChange" :current-page="boxPageno" :page-sizes="[10, 20, 30, 40]" :page-size="boxPerpage" layout="total, sizes, prev, pager, next, jumper" :total="boxDataCount">
        </el-pagination>

      </el-tab-pane>
      <el-tab-pane label="订单列表">
        <el-table :data="depositOrderList">
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline style="font-size:0">
                <el-form-item label="项目名称:" style="width: 50%;margin-right:0">
                  <a class="link" :href="'/projDetail/info/' + scope.row.projId" target="_blank">{{ scope.row.nameCn }}</a>
                </el-form-item>
                <el-form-item label="锁定时间:" style="width: 50%;margin-right:0">{{ scope.row.lockTime }}</el-form-item>
                <el-form-item label="年化回报:" style="width: 50%;margin-right:0">{{ scope.row.interestRate }}</el-form-item>
                <el-form-item label="总额度:" style="width: 50%;margin-right:0">{{ scope.row.totalAmount }}</el-form-item>
                <el-form-item label="起始额度:" style="width: 50%;margin-right:0">{{ scope.row.minAmount }}</el-form-item>
                <el-form-item label="剩余额度:" style="width: 50%;margin-right:0">{{ scope.row.remainAmount }}</el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column type="index"></el-table-column>
          <el-table-column label="用户手机号" prop="mobile"></el-table-column>
          <el-table-column label="订单金额" prop="orderAmount"></el-table-column>
          <el-table-column label="支付地址">
            <template slot-scope="scope">
              <a class="link" :href="'https://etherscan.io/token/' + scope.row.contractAddr + '?a=' + scope.row.fromAddr" target="_blank">{{ getShortStr(scope.row.fromAddr, 5) }}</a>
            </template>
          </el-table-column>
          <el-table-column label="接收地址">
            <template slot-scope="scope">
              <a class="link" :href="'https://etherscan.io/token/' + scope.row.contractAddr + '?a=' + scope.row.toAddr" target="_blank">{{ getShortStr(scope.row.toAddr, 5) }}</a>
            </template>
          </el-table-column>
          <el-table-column label="合约地址">
            <template slot-scope="scope">
              <a class="link" :href="'https://etherscan.io/address/' + scope.row.contractAddr" target="_blank">{{ getShortStr(scope.row.contractAddr, 5) }}</a>
            </template>
          </el-table-column>
          <el-table-column label="状态" prop="status">
            <template slot-scope="scope">
              {{ orderStatusDict[scope.row.status] }}
            </template>
          </el-table-column>
        </el-table>

    <!--Pagination-->
    <el-pagination class="footer-page-box" @size-change="onOrderSizeChange" @current-change="onOrderCurChange" :current-page="orderPageno" :page-sizes="[10, 20, 30, 40]" :page-size="orderPerpage" layout="total, sizes, prev, pager, next, jumper" :total="orderDataCount">
    </el-pagination>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
export default {
  data () {
    return {
      depositBoxList: [],
      boxStatusDict: {},
      boxPageno: 1,
      boxPerpage: 10,
      boxDataCount: 0,
      depositOrderList: [],
      orderStatusDict: {},
      orderPageno: 1,
      orderPerpage: 10,
      orderDataCount: 0
    }
  },
  mounted () {
    this.updateBoxData()
    this.updateOrderData()
  },
  methods: {
    updateBoxData () {
      this.$http.post('/api/getAdminDepositBoxList', {
        pageno: this.boxPageno,
        perpage: this.boxPerpage
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.depositBoxList = res.data.data.dataList
          this.boxDataCount = res.data.data.dataCount
          this.boxStatusDict = res.data.data.statusDict
        }
      })
    },
    updateOrderData () {
      this.$http.post('/api/getAdminDepositOrderList', {
        pageno: this.orderPageno,
        perpage: this.orderPerpage
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.depositOrderList = res.data.data.dataList
          this.orderDataCount = res.data.data.dataCount
          this.orderStatusDict = res.data.data.statusDict
        }
      })
    },
    onBoxSizeChange (perpage) {
      this.boxPerpage = perpage
      this.updateBoxData()
    },
    onBoxCurChange (pageno) {
      this.boxPageno = pageno
      this.updateBoxData()
    },
    onOrderSizeChange (perpage) {
      this.orderPerpage = perpage
      this.updateOrderData()
    },
    onOrderCurChange (pageno) {
      this.orderPageno = pageno
      this.updateOrderData()
    }
  }
}
</script>

<style lang="scss" scoped>
.link {
  &:hover {
    color: #FFCF81;
  }
}
</style>
