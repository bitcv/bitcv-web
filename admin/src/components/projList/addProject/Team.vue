<template>
  <div class="team">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showDialog = true">添加</el-button>
    </div>
    <el-table :data="projData.teamList">
      <el-table-column label="照片">
        <template slot-scope="scope">
          <img class="table-image" :src="scope.row.photoUrl" alt="">
        </template>
      </el-table-column>
      <el-table-column label="姓名">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="职位">
        <template slot-scope="scope">{{ scope.row.position }}</template>
      </el-table-column>
      <el-table-column label="简介">
        <template slot-scope="scope">{{ scope.row.intro }}</template>
      </el-table-column>
    </el-table>
    <el-dialog title="项目成员信息" :visible.sync="showDialog" center>
      <el-form label-width="80px">
        <el-form-item label="姓名">
          <el-input v-model="inputName"></el-input>
        </el-form-item>
        <el-form-item label="照片">
          <el-upload class="upload-box" name="picture" action="/api/uploadFile" :on-success="onPhotoSuccess" :show-file-list="false">
            <i class="el-icon-plus"></i>
            <img :src="inputPhotoUrl" alt="">
          </el-upload>
        </el-form-item>
        <el-form-item label="职位">
          <el-input v-model="inputPosition"></el-input>
        </el-form-item>
        <el-form-item label="简介">
          <el-input type="textarea" v-model="inputIntro"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button @click="showDialog = false">取消</el-button>
        <el-button type="primary" @click="addMember">确定</el-button>
      </div>
    </el-dialog>
    <div class="footer-btn-area">
      <el-button @click="lastStep">上一步</el-button>
      <el-button type="primary" @click="nextStep">下一步</el-button>
    </div>
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
      inputPhotoUrl: '',
      inputPosition: '',
      inputIntro: ''
    }
  },
  methods: {
    addMember () {
      this.projData.teamList.push({
        name: this.inputName,
        photoUrl: this.inputPhotoUrl,
        position: this.inputPosition,
        intro: this.inputIntro
      })
      this.inputName = ''
      this.inputPhoto = ''
      this.inputPhotoUrl = ''
      this.inputPosition = ''
      this.inputIntro = ''
      this.showDialog = false
    },
    onPhotoSuccess (res) {
      if (res.errcode === 0) {
        this.inputPhotoUrl = res.data.url
      }
    },
    lastStep () {
      this.$emit('updateIndex', 1)
    },
    nextStep () {
      this.$emit('updateData', {
        index: 3,
        projData: this.projData
      })
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
