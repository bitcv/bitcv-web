<template>
  <div class="media">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showDialog = true">添加</el-button>
    </div>
    <el-table :data="partnerList">
      <el-table-column label="媒体Logo">
        <template slot-scope="scope">{{ scope.row.logoUrl }}</template>
      </el-table-column>
      <el-table-column label="媒体名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="新闻封面">
        <template slot-scope="scope">{{ scope.row.photoUrl }}</template>
      </el-table-column>
      <el-table-column label="新闻标题">
        <template slot-scope="scope">{{ scope.row.title }}</template>
      </el-table-column>
      <el-table-column label="新闻链接">
        <template slot-scope="scope">{{ scope.row.webUrl }}</template>
      </el-table-column>
    </el-table>
    <el-dialog title="媒体报道信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <el-form-item label="媒体名称">
          <el-input v-model="inputName"></el-input>
        </el-form-item>
        <el-form-item label="媒体Logo">
          <el-input type="file" @change="inputLogo"></el-input>
        </el-form-item>
        <el-form-item label="新闻封面">
          <el-input type="file" @change="inputPhoto"></el-input>
        </el-form-item>
        <el-form-item label="新闻标题">
          <el-input v-model="inputTitle"></el-input>
        </el-form-item>
        <el-form-item label="新闻链接">
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
      <el-button type="primary">完成</el-button>
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
      inputTitle: '',
      inputPhotoUrl: '',
      inputWebUrl: '',
      partnerList: []
    }
  },
  methods: {
    addMember () {
      this.partnerList.push({
        name: this.inputName,
        logoUrl: this.inputLogoUrl,
        title: this.inputTitle,
        photoUrl: this.inputPhotoUrl,
        webUrl: this.inputWebUrl
      })
      this.showDialog = false
    },
    inputLogo (event) {
      this.inputLogoUrl = event
    },
    inputPhoto (event) {
      this.inputPhotoUrl = event
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
