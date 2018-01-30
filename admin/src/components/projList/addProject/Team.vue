<template>
  <div class="team">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showDialog = true">添加</el-button>
    </div>
    <el-table :data="teamList">
      <el-table-column label="照片">
        <template slot-scope="scope">{{ scope.row.photoUrl }}</template>
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
          <el-input type="file" @change="inputPhoto"></el-input>
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
      <router-link to="event">
        <el-button>上一步</el-button>
      </router-link>
      <router-link to="partner" type="primary">
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
      inputPhotoUrl: '',
      inputPosition: '',
      inputIntro: '',
      teamList: []
    }
  },
  methods: {
    addMember () {
      this.teamList.push({
        name: this.inputName,
        photoUrl: this.inputPhotoUrl,
        position: this.inputPosition,
        intro: this.inputIntro
      })
      this.showDialog = false
    },
    inputPhoto (event) {
      this.inputPhotoUrl = event
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
