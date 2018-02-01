<template>
  <div class="social">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showDialog = true">添加</el-button>
    </div>
    <el-table :data="socialList">
      <el-table-column label="Logo">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.logoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="主页url">
        <template slot-scope="scope">{{ scope.row.linkUrl }}</template>
      </el-table-column>
    </el-table>
    <el-dialog title="社交账号信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <el-form-item label="平台名称">
          <el-input v-model="inputName"></el-input>
        </el-form-item>
        <el-form-item label="平台Logo">
          <el-upload class="upload-box" name="logo" action="/api/uploadFile" :on-success="onLogoSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="inputLogoUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="主页url">
          <el-input v-model="inputWebUrl">
            <template slot="prepend" placeholder="请输入社交账号的主页地址">http://</template>
          </el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button @click="showDialog = false">取消</el-button>
        <el-button type="primary" @click="addMember">确定</el-button>
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
      inputName: '',
      inputLogoUrl: '',
      inputWebUrl: '',
      socialList: []
    }
  },
  methods: {
    addMember () {
      this.showDialog = false
      this.$alert('成功添加社交信息', '提示')
    },
    onLogoSuccess (res) {
      if (res.errcode === 0) {
        this.inputLogoUrl = res.data.url
      }
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
