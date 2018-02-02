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
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="showEdit(scope.$index)">编辑</el-button>
          <el-button size="mini" type="danger" @click="showDel(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="社交账号信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <el-form-item label="平台名称">
          <el-select v-model="inputName" @change="selectSocial" filterable allow-create placeholder="请选择或输入社交平台名称" clearable>
            <el-option v-for="(social, index) in socialOptionList" :key="index" :value="social.name"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="平台Logo">
          <el-upload class="upload-box" name="logo" action="/api/uploadFile" :on-success="onLogoSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="inputLogoUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="主页url">
          <el-input v-model="inputLinkUrl">
            <template slot="prepend" placeholder="请输入社交账号的主页地址">http://</template>
          </el-input>
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
  props: {
    projData: Object
  },
  data () {
    return {
      showDialog: false,
      inputName: '',
      inputLogoUrl: '',
      inputLinkUrl: '',
      socialId: '',
      socialOptionList: [],
      socialList: []
    }
  },
  mounted () {
    this.updateData()
  },
  watch: {
    showDialog: function (isShow) {
      if (isShow) {
        this.getSocialList()
        console.log('in')
      }
    }
  },
  methods: {
    updateData () {
      this.$http.post('/api/getProjSocialList', {
        projId: this.$route.params.id
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.socialList = res.data.data.dataList
        }
      })
    },
    onLogoSuccess (res) {
      if (res.errcode === 0) {
        this.inputLogoUrl = res.data.url
      }
    },
    getSocialList () {
      this.$http.get('/api/getSocialList').then((res) => {
        if (res.data.errcode === 0) {
          this.socialOptionList = res.data.data.dataList
        }
      })
    },
    selectSocial () {
      this.socialOptionList.forEach((social) => {
        if (social.name === this.inputName) {
          this.inputLogoUrl = social.logoUrl
        }
      })
    },
    showEdit (index) {
      var socialInfo = this.socialList[index]
      this.socialId = socialInfo.id
      this.inputName = socialInfo.name
      this.inputLogoUrl = socialInfo.logoUrl
      this.inputLinkUrl = socialInfo.linkUrl
      this.showDialog = true
    },
    showDel (socialId) {
      this.$confirm('删除后无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.delSocial(socialId)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    submit () {
      if (this.socialId) {
        this.updSocial()
      } else {
        this.addSocial()
      }
    },
    addSocial () {
      this.$http.post('/api/addProjSocial', {
        projId: this.$route.params.id,
        name: this.inputName,
        logoUrl: this.inputLogoUrl,
        linkUrl: this.inputLinkUrl
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '添加成功!' })
          this.inputName = ''
          this.inputLogoUrl = ''
          this.inputLinkUrl = ''
          this.showDialog = false
          this.updateData()
        }
      })
    },
    updSocial () {
      this.$http.post('/api/updProjSocial', {
        socialId: this.socialId,
        name: this.inputName,
        logoUrl: this.inputLogoUrl,
        linkUrl: this.inputLinkUrl
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '更新成功!' })
          this.inputName = ''
          this.inputLogoUrl = ''
          this.inputLinkUrl = ''
          this.showDialog = false
          this.socialId = ''
          this.updateData()
        }
      })
    },
    delSocial (socialId) {
      this.$http.post('/api/delProjSocial', {
        socialId: socialId
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
