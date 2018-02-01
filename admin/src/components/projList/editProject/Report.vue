<template>
  <div class="report">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showDialog = true">添加</el-button>
    </div>
    <el-table :data="reportList">
      <el-table-column label="媒体Logo">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.logoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="媒体名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="新闻封面">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.photoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="新闻标题">
        <template slot-scope="scope">{{ scope.row.title }}</template>
      </el-table-column>
      <el-table-column label="新闻链接">
        <template slot-scope="scope">{{ scope.row.linkUrl }}</template>
      </el-table-column>
    </el-table>
    <el-dialog title="媒体报道信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <el-form-item label="媒体名称">
          <el-input v-model="inputName"></el-input>
        </el-form-item>
        <el-form-item label="媒体Logo">
          <el-upload class="upload-box" name="logo" action="/api/uploadFile" :on-success="onLogoSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="inputLogoUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="新闻标题">
          <el-input v-model="inputTitle"></el-input>
        </el-form-item>
        <el-form-item label="新闻链接">
          <el-input v-model="inputLinkUrl">
            <template slot="prepend" placeholder="请输入新闻地址">http://</template>
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
      inputTitle: '',
      inputLinkUrl: '',
      reportList: []
    }
  },
  methods: {
    addMember () {
      this.showDialog = false
      this.inputLogoUrl = ''
      this.inputTitle = ''
      this.inputLinkUrl = ''
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
