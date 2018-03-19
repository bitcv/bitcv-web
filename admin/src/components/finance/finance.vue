<template>
  <div class="deposit-box">
    <el-tabs type="border-card">
      <el-tab-pane label="币财报列表">
        <el-form :inline="true" label-width="100px">
          <el-form-item label="交易哈希:">
            <el-input v-model="jyhash" placeholder="请输入交易哈希值"></el-input>
          </el-form-item>
          <el-form-item label="打款地址:" >
            <el-input v-model="faddr" placeholder="请输入打款地址"></el-input>
          </el-form-item>
          <el-form-item label="收款地址:">
            <el-input v-model="taddr" placeholder="请输入收款地址"></el-input>
          </el-form-item>
          <!-- <el-form-item label="代币符号">
            <el-input v-model="symbol" placeholder="请输入代币符号"></el-input>
          </el-form-item> -->
          <el-form-item label="类型:">
            <el-select v-model="recordstyp"  placeholder="请选择类型">
              <el-option v-model="all" label="全部">全部</el-option>
              <el-option v-for="(op, index) in recordstype" :key="index" :label="op" :value="index"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="费用性质:">
            <el-select v-model="feetype"  placeholder="请选择费用性质">
              <el-option v-model="all" label="全部">全部</el-option>
              <el-option v-for="(op, index) in options" :key="index" :label="op" :value="index"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="代币符号:">
            <el-select v-model="conintype" placeholder="请选择代币符号">
              <el-option v-model="all" label="全部">全部</el-option>
              <el-option v-for="(op, index) in cointypes" :key="index" :label="op" :value="index"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-button type="success" @click.prevent="search">搜索</el-button>
          </el-form-item>
        </el-form>

        <el-table :data="financeList">
          <el-table-column label="ID">
            <template slot-scope="scope">{{ scope.row.id }}</template>
          </el-table-column>
          <el-table-column label="交易哈希">
            <template slot-scope="scope">
              <a :href="'https://etherscan.io/tx/' + scope.row.transaction_hash" target="_blank">{{ getShortStr(scope.row.transaction_hash,5) }}</a>
            </template>
          </el-table-column>
          <el-table-column label="从">
            <template slot-scope="scope">{{ getShortStr(scope.row.from_addr,10) }}</template>
          </el-table-column>
          <el-table-column label="到">
            <template slot-scope="scope">{{ getShortStr(scope.row.to_addr,10) }}</template>
          </el-table-column>
          <el-table-column label="时间">
            <template slot-scope="scope">{{ scope.row.timeformat }}</template>
          </el-table-column>
          <el-table-column label="地址名称">
            <template slot-scope="scope">{{ scope.row.walletName }}</template>
          </el-table-column>
          <el-table-column label="类型">
            <template slot-scope="scope">{{ scope.row.typename }}</template>
          </el-table-column>
          <el-table-column label="用于">
            <template slot-scope="scope">{{ scope.row.usedname }}</template>
          </el-table-column>
          <el-table-column label="数量">
            <template slot-scope="scope">{{ scope.row.realamount }}</template>
          </el-table-column>
          <el-table-column label="符号">
            <template slot-scope="scope">{{ scope.row.cointype }}</template>
          </el-table-column>
          <el-table-column label="矿工费">
            <template slot-scope="scope">{{ scope.row.actualgasprice }}</template>
          </el-table-column>
          <el-table-column label="项目主体">
            <template slot-scope="scope">{{ scope.row.tokensubjectname }}</template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button type="success" size="mini" @click="editShow(scope.row,scope.$index)">标记</el-button>
            </template>
          </el-table-column>
        </el-table>
        <!--Pagination-->
        <el-pagination v-if="financeList && financeList.length > 0" class="footer-page-box" @size-change="onBoxSizeChange" @current-change="onBoxCurChange" :current-page="pageno" :page-sizes="[10, 20, 30, 40]" :page-size="perpage" layout="total, sizes, prev, pager, next, jumper" :total="dataCount">
        </el-pagination>

        <el-dialog title="交易记录" :visible.sync="showDialog" center>
          <el-form label-width="120px">
            <el-form-item label="交易哈希 : ">
              {{ editObj.transaction_hash}}
            </el-form-item>
            <el-form-item label="类型 : ">
              <el-select v-model="tokentypeId" placeholder="请选择">
                <el-option v-for="(op, index) in tokentype" :key="index" :label="op" :value="index">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="费用性质 : ">
              <el-select v-model="usedId" placeholder="请选择">
                <el-option v-for="(op, index) in options" :key="index" :label="op" :value="index">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="项目主体 : ">
              <el-input v-model="tokensub" placeholder="请输入内容"></el-input>
            </el-form-item>
            <el-form-item label="备注 : ">
              <el-input v-model="memo" placeholder="请输入内容"></el-input>
            </el-form-item>
          </el-form>
          <span slot="footer" class="dialog-footer">
            <el-button @click="showDialog = false">取 消</el-button>
            <el-button type="primary" @click="fork">确 定</el-button>
          </span>
        </el-dialog>

      </el-tab-pane>
      <el-tab-pane label="账户地址">
        <div class="header-btn-area">
          <el-button type="primary" icon="el-icon-plus" @click="showAdd">添加</el-button>
        </div>
        <el-table :data="walletList">
          <el-table-column label="ID" >
            <template slot-scope="scope">{{ scope.row.id }}</template>
          </el-table-column>
          <el-table-column label="账户名称" >
            <template slot-scope="scope">{{ scope.row.wname }}</template>
          </el-table-column>
          <el-table-column label="钱包名称" >
            <template slot-scope="scope">
              <a :href="'https://etherscan.io/address/' + scope.row.waddress" target="_blank">{{ scope.row.waddress }}</a>
            </template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button>
              <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-dialog title="账户地址配置" :visible.sync="showDialog" center>
          <el-form label-width="120px">
            <el-form-item label="账户名称: ">
              <el-input type="text" placeholder="请输入账户名称" v-model="walletname"></el-input>
            </el-form-item>
            <el-form-item label="账户地址: ">
              <el-input type="text" placeholder="请输入账户地址" v-model="walletaddr"></el-input>
            </el-form-item>
          </el-form>
          <div slot="footer">
            <el-button @click="showDialog = false">取消</el-button>
            <el-button type="primary" @click="submit">确定</el-button>
          </div>
        </el-dialog>

        <!--Pagination-->
        <!-- <el-pagination class="footer-page-box" @size-change="onOrderSizeChange" @current-change="onOrderCurChange" :current-page="orderPageno" :page-sizes="[10, 20, 30, 40]" :page-size="orderPerpage" layout="total, sizes, prev, pager, next, jumper" :total="orderDataCount">
        </el-pagination> -->
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
    export default {
        data () {
            return {
                walletList: [],
                financeList: [],
                showDialog: false,
                inputName: '',
                inputLogoUrl: '',
                inputHomeUrl: '',
                pageno: 1,
                perpage: 10,
                dataCount: 0,
                walletname: '',
                walletaddr: '',
                options: [],
                tokentype: [],
                tokensubject: [],
                editObj: [],
                tokentypeId: '',
                tokensub: '',
                memo: '',
                usedId: '',
                hash: '',
                jyhash: '',
                faddr: '',
                taddr: '',
                // symbol: '',
                cointypes: [],
                recordstype: [],
                conintype: '',
                recordstyp: '',
                feetype: '',
                all: '99'
            }
        },
        mounted () {
            this.updateData()
            this.financeData()
            // this.getCount()
        },
        methods: {
            updateData () {
                this.$http.post('/api/getWalletList', {
                    pageno: this.pageno,
                    perpage: this.perpage
                }).then((res) => {
                    if (res.data.errcode === 0) {
                    this.walletList = res.data.data.dataList
                }
            })
            },
            financeData () {
                this.$http.post('/api/getFinanceList', {
                    pageno: this.pageno,
                    perpage: this.perpage
                }).then((res) => {
                    if (res.data.errcode === 0) {
                    this.financeList = res.data.data.dataList
                    this.options = res.data.data.options
                    this.tokensubject = res.data.data.tokensubject
                    this.tokentype = res.data.data.tokentype
                    this.dataCount = res.data.data.totalCount
                    this.cointypes = res.data.data.cointypes
                    this.recordstype = res.data.data.recordstype
                }
            })
            },
            // getCount () {
            //   this.$http.post('/api/getFinanceCount').then((res) => {
            //     if (res.data.errcode === 0) {
            //       this.dataCount = res.data.data.totalCount
            //     }
            //   })
            // },
            showAdd () {
                this.walletId = ''
                this.walletname = ''
                this.walletaddr = ''
                this.showDialog = true
            },
            editShow (row, _index) {
                this.editObj = row
                this.hash = this.financeList[_index].transaction_hash
                this.tokentypeId = this.editObj.typename
                this.usedId = this.editObj.usedname
                this.tokensub = this.editObj.tokensubject
                this.memo = this.editObj.memo
                this.showDialog = true
            },
            showEdit (index) {
                var instituData = this.walletList[index]
                this.walletId = instituData.id
                this.walletname = instituData.wname
                this.walletaddr = instituData.waddress
                this.showDialog = true
            },
            submit () {
                if (!this.walletname) {
                    return alert('请输入账户名称')
                }
                if (!this.walletaddr) {
                    return alert('请输入账户地址')
                }
                if (this.walletId) {
                    this.updWallet()
                } else {
                    this.addWallet()
                }
            },
            fork () {
                this.$http.post('/api/updateRecords', {
                    transaction_hash: this.hash,
                    memo: this.memo,
                    tokensubject: this.tokensub,
                    used: this.usedId,
                    type: this.tokentypeId
                }).then((res) => {
                    if (res.data.errcode === 0) {
                    this.$message({ type: 'success', message: '标记成功!' })
                    this.showDialog = false
                    this.financeData()
                }
            })
            },
            addWallet () {
                this.$http.post('/api/addWallets', {
                    walletaddr: this.walletaddr,
                    walletname: this.walletname
                }).then((res) => {
                    if (res.data.errcode === 0) {
                    this.$message({ type: 'success', message: '添加成功!' })
                    this.showDialog = false
                    this.updateData()
                }
            })
            },
            updWallet () {
                this.$http.post('/api/updWallets', {
                    walletId: this.walletId,
                    walletname: this.walletname,
                    walletaddr: this.walletaddr
                }).then((res) => {
                    if (res.data.errcode === 0) {
                    this.$message({ type: 'success', message: '更新成功!' })
                    this.showDialog = false
                    this.updateData()
                }
            })
            },
            showDel (id) {
                this.$confirm('删除后无法恢复, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.delWalletAddr(id)
            }).catch(() => {
                    this.$message({
                    type: 'info',
                    message: '已取消删除'
                })
            })
            },
            delWalletAddr (id) {
                this.$http.post('/api/delWalletAddr', {
                    walletId: id
                }).then((res) => {
                    if (res.data.errcode === 0) {
                    this.$message({ type: 'success', message: '删除成功!' })
                    this.updateData()
                }
            })
            },
            onBoxSizeChange (perpage) {
                this.perpage = perpage
                this.financeData()
            },
            onBoxCurChange (pageno) {
                this.pageno = pageno
                this.financeData()
            },
            search () {
                this.$http.post('/api/getFinanceList', {
                    pageno: this.pageno,
                    perpage: this.perpage,
                    jyhash: this.jyhash,
                    faddr: this.faddr,
                    taddr: this.taddr,
                    // symbol: this.symbol,
                    conintype: this.conintype,
                    recordstyp: this.recordstyp,
                    feetype: this.feetype
                }).then((res) => {
                    if (res.data.errcode === 0) {
                    this.financeList = res.data.data.dataList
                    this.options = res.data.data.options
                    this.tokensubject = res.data.data.tokensubject
                    this.tokentype = res.data.data.tokentype
                    this.dataCount = res.data.data.totalCount
                }
            })
            }
        }
    }
</script>
<style lang="scss" scoped>
</style>