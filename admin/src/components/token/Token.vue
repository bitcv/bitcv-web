<template>
  <div class="token">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showAdd">添加</el-button>
    </div>
    <el-table :data="tokenList">
      <el-table-column label="通证ID" prop="id"></el-table-column>
      <el-table-column label="通证Logo">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.logoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="通证名称" prop="name"></el-table-column>
      <el-table-column label="通证符号" prop="symbol"></el-table-column>
      <el-table-column label="合约地址" prop="contractAddr">
        <template slot-scope="scope">
          <a class="link" :href="'https://etherscan.io/address/' + scope.row.contractAddr" target="_blank">{{ getShortStr(scope.row.contractAddr, 6) }}</a>
        </template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button>
          <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="通证信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <el-form-item label="通证名称">
          <el-input v-model="inputName" placeholder="请输入通证名称"></el-input>
        </el-form-item>
        <el-form-item label="通证符号">
          <el-input v-model="inputSymbol" placeholder="请输入通证符号"></el-input>
        </el-form-item>
        <el-form-item label="通证Logo">
          <el-upload class="upload-box" name="logo" action="/api/uploadFile" :on-success="onLogoSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="inputLogoUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="通证价格">
          <el-input v-model="inputPrice" placeholder="请输入通证价格"></el-input>
        </el-form-item>
        <el-form-item label="合约地址">
          <el-input v-model="inputContractAddr" placeholder="请输入通证合约地址"></el-input>
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
  data () {
    return {
      showDialog: false,
      inputName: '',
      inputSymbol: '',
      inputLogoUrl: '',
      inputPrice: '',
      inputContractAddr: '',
      tokenId: '',
      tokenList: []
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      this.$http.get('/api/getTokenList').then((res) => {
        if (res.data.errcode === 0) {
          this.tokenList = res.data.data.dataList
        }
      })
    },
    onLogoSuccess (res) {
      if (res.errcode === 0) {
        this.inputLogoUrl = res.data.url
      }
    },
    showAdd () {
      this.tokenId = ''
      this.inputName = ''
      this.inputSymbol = ''
      this.inputLogoUrl = ''
      this.inputPrice = ''
      this.inputContractAddr = ''
      this.showDialog = true
    },
    showEdit (index) {
      var tokenData = this.tokenList[index]
      this.tokenId = tokenData.id
      this.inputName = tokenData.name
      this.inputSymbol = tokenData.symbol
      this.inputLogoUrl = tokenData.logoUrl
      this.inputPrice = tokenData.price
      this.contractAddr = tokenData.contractAddr
      this.showDialog = true
    },
    showDel (tokenId) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delToken(tokenId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    submit () {
      if (this.tokenId) {
        this.updToken()
      } else {
        this.addToken()
      }
    },
    addToken () {
      this.$http.post('/api/addToken', {
        name: this.inputName,
        symbol: this.inputSymbol,
        logoUrl: this.inputLogoUrl,
        price: this.inputPrice,
        contractAddr: this.inputContractAddr
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.showDialog = false
          this.updateData()
        }
      })
    },
    updToken () {
      this.$http.post('/api/updToken', {
        tokenId: this.tokenId,
        name: this.inputName,
        symbol: this.inputSymbol,
        logoUrl: this.inputLogoUrl,
        price: this.inputPrice,
        contractAddr: this.inputContractAddr
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '更新成功!' })
          this.showDialog = false
          this.updateData()
        }
      })
    },
    delToken (tokenId) {
      this.$http.post('/api/delToken', {
        tokenId: tokenId
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
