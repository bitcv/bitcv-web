<template>
  <div class="project-main" v-loading="loading">
    <div class="add-box">
      <el-button type="warning" plain @click="visible = true; validate = true">+添加</el-button>
    </div>

    <el-table :data="list" style="width: 100%;">
      <el-table-column prop="id" label="ID">
      </el-table-column>
      <el-table-column prop="wname" label="钱包名称">
      </el-table-column>
      <el-table-column prop="waddress" label="钱包地址">
      </el-table-column>
      <el-table-column label="操作" width="150">
        <template slot-scope="scope">
          <span class="text-link" @click="handleEdit(scope.row)">编辑</span>
          <span class="text-error" @click="handleDel(scope.row.id)" >删除</span>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog title="钱包地址配置" :visible.sync="visible" center v-loading="loading">
      <el-form label-width="120px" :model="form" status-icon :rules="rules" ref="form">
        <el-form-item label="钱包名称: ">
            <el-input type="text" v-model="form.walletname"></el-input>
        </el-form-item>
        <el-form-item label="钱包地址: ">
            <el-input type="text" v-model="form.walletaddr"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="visible = false">取 消</el-button>
        <el-button type="primary" @click="submitForm">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script type="text/javascript">
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
      validate: false,
      orderPageno: 1,
      orderPerpage: 10,
      list: [],
      form: {
        walletaddr: '',
        walletname: ''
      },
      rules: {
        walletaddr: [ {validator: valid} ]
      }
    }
  },
  computed: {
    params () {
      return {
        walletaddr: this.form.walletaddr,
        walletname: this.form.walletname,
        walletId: this.form.id
      }
    }
  },
  mounted () {
    this.fetch()
  },
  methods: {
    ...mapActions([
      'addWallets',
      'getWalletList',
      'delWalletAddr'
    ]),
    fetch () {
      this.loading = true
      this.getWalletList({
        pageno: this.orderPageno,
        perpage: this.orderPerpage
      })
        .then(({dataList = []} = {}) => {
          this.list = dataList
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
    handleAdd () {
      if (!this.params.walletname) {
        return alert('请输入钱包名称')
      }
      if (!this.params.walletaddr) {
        return alert('请输入钱包地址')
      }
      this.addWallets(this.params)
        .then(data => {
          this.$message.success(this.params.walletId !== undefined ? '更新成功' : '添加成功')
          this.loading = false
          this.visible = false
          this.fetch()
          window.location.reload()
        })
        .catch(() => {
          this.loading = false
        })
    },
    handleEdit (row) {
      this.visible = true
      this.validate = true
      this.form = Object.assign(this.form, row)
      this.form.walletaddr = this.form.waddress
      this.form.walletname = this.form.wname
      this.form.walletid = this.form.id
    },
    handleDel (mId) {
      this.$confirm('删除后无法恢复， 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        center: true
      }).then(() => {
        this.loading = true
        this.delWalletAddr({walletId: mId})
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
<style lang="scss">
.project-main {
  .add-box {
    overflow: hidden;
    text-align: right;
    margin: 20px 0 25px;
  }
  .text-error {
    cursor: pointer;
  }
  .text-link {
    cursor: pointer;
  }
}

</style>
