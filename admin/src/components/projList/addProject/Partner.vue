<template>
  <div class="media">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showDialog = true">添加</el-button>
    </div>
    <el-table :data="partnerList">
      <el-table-column label="Logo">
        <template slot-scope="scope">{{ scope.row.logoUrl }}</template>
      </el-table-column>
      <el-table-column label="名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="主页url">
        <template slot-scope="scope">{{ scope.row.webUrl }}</template>
      </el-table-column>
    </el-table>
    <el-dialog title="合作伙伴信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <el-form-item label="名称">
          <el-input v-model="inputName"></el-input>
        </el-form-item>
        <el-form-item label="Logo">
          <el-input type="file" @change="inputLogo"></el-input>
        </el-form-item>
        <el-form-item label="主页url">
          <el-input v-model="inputWebUrl"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button @click="showDialog = false">取消</el-button>
        <el-button type="primary" @click="addMember">确定</el-button>
      </div>
    </el-dialog>
    <div class="footer-btn-area">
      <router-link to="team">
        <el-button>上一步</el-button>
      </router-link>
      <router-link to="media">
        <el-button type="primary">下一步</el-button>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      showDialog: false,
      inputName: '',
      inputLogoUrl: '',
      inputWebUrl: '',
      partnerList: []
    }
  },
  methods: {
    addMember () {
      this.partnerList.push({
        name: this.inputName,
        logoUrl: this.inputLogoUrl,
        webUrl: this.inputWebUrl
      })
      this.showDialog = false
    },
    inputLogo (event) {
      this.inputLogoUrl = event
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
