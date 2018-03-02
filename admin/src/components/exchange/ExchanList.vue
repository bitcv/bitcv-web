<template>
  <div class="media">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showAdd">添加</el-button>
    </div>
    <el-table :data="exchangeList">
      <el-table-column type="index"></el-table-column>
      <el-table-column label="Logo">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.logoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="交易所名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="官方网址">
        <template slot-scope="scope">{{ scope.row.homeUrl }}</template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button>
          <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="交易所信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <!-- <el-form-item label="Logo">
          <el-input v-model="inputLogoUrl" placeholder="请输入机构logo"></el-input>
        </el-form-item> -->
        <el-form-item label="Logo">
          <el-upload class="upload-box" name="logo" action="/api/uploadFile" :on-success="onLogoSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="inputLogoUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="名称">
          <el-input v-model="inputName" placeholder="请输入交易所名称"></el-input>
        </el-form-item>
        <el-form-item label="主页url">
          <el-input v-model="inputHomeUrl">
            <template slot="prepend" placeholder="请输入主页地址">http://</template>
          </el-input>
        </el-form-item>
        <el-form-item label="简介">
          <el-input type="textarea" v-model="inputIntro"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button @click="showDialog = false">取消</el-button>
        <el-button type="primary" @click="submit">确定</el-button>
      </div>
    </el-dialog>
    <!--Pagination-->
    <!-- <el-pagination class="footer-page-box" @size-change="onSizeChange" @current-change="onCurChange" :current-page="pageno" :page-sizes="[10, 20, 30, 40]" :page-size="perpage" layout="total, sizes, prev, pager, next, jumper" :total="dataCount">
    </el-pagination> -->
  </div>
</template>
<script>
export default {
  data () {
    return {
      exchangeList: [],
      showDialog: false,
      inputName: '',
      inputLogoUrl: '',
      inputHomeUrl: '',
      inputIntro: ''
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      this.$http.post('/api/getExchangeList').then((res) => {
        if (res.data.errcode === 0) {
          this.exchangeList = res.data.data.exchangeList
        }
      })
    },
    showAdd () {
      this.mediaId = ''
      this.inputName = ''
      this.inputLogoUrl = ''
      this.inputHomeUrl = ''
      this.inputIntro = ''
      this.showDialog = true
    },
    showEdit (index) {
      var exchangeData = this.exchangeList[index]
      this.mediaId = exchangeData.id
      this.inputName = exchangeData.name
      this.inputLogoUrl = exchangeData.logoUrl
      this.inputHomeUrl = exchangeData.homeUrl
      this.inputIntro = exchangeData.intro
      this.showDialog = true
    },
    onLogoSuccess (res) {
      if (res.errcode === 0) {
        this.inputLogoUrl = res.data.url
      }
    },
    submit () {
      if (this.mediaId) {
        this.updExchange()
      } else {
        this.addExchange()
      }
    },
    addExchange () {
      this.$http.post('/api/addExchange', {
        name: this.inputName,
        logoUrl: this.inputLogoUrl,
        homeUrl: this.inputHomeUrl,
        intro: this.inputIntro
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.showDialog = false
          this.updateData()
        }
      })
    },
    updExchange () {
      this.$http.post('/api/updExchange', {
        mediaId: this.mediaId,
        name: this.inputName,
        logoUrl: this.inputLogoUrl,
        homeUrl: this.inputHomeUrl,
        intro: this.inputIntro
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
        this.delExchange(id)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    delExchange (id) {
      this.$http.post('/api/delExchange', {
        mediaId: id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '删除成功!' })
          this.updateData()
        }
      })
    },
    onSizeChange (perpage) {
      this.perpage = perpage
      this.updateData()
    },
    onCurChange (pageno) {
      this.pageno = pageno
      this.updateData()
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
