<template>
  <div class="event">
    <div class="header-btn-area">
      <el-button type="primary" icon="el-icon-plus" @click="showDialog = true">添加</el-button>
    </div>
    <el-table :data="projData.eventList">
      <el-table-column label="时间">
        <template slot-scope="scope">{{ scope.row.time }}</template>
      </el-table-column>
      <el-table-column label="标题">
        <template slot-scope="scope">{{ scope.row.title }}</template>
      </el-table-column>
      <el-table-column label="详情">
        <template slot-scope="scope">{{ scope.row.detail }}</template>
      </el-table-column>
    </el-table>
    <el-dialog title="项目重大事件" :visible.sync="showDialog" center>
      <el-form label-width="50px">
        <el-form-item label="时间">
          <el-date-picker v-model="inputTime" type="datetime" default-value="2017-01-01T00:00:00" placeholder="请选择时间"></el-date-picker>
        </el-form-item>
        <el-form-item label="标题">
          <el-input v-model="inputTitle"></el-input>
        </el-form-item>
        <el-form-item label="描述">
          <el-input type="textarea" v-model="inputDetail"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button @click="showDialog = false">取消</el-button>
        <el-button type="primary" @click="addEvent">确定</el-button>
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
      inputTime: '',
      inputTitle: '',
      inputDetail: '',
      eventList: []
    }
  },
  methods: {
    addEvent () {
      this.projData.eventList.push({
        time: this.inputTime,
        title: this.inputTitle,
        detail: this.inputDetail
      })
      this.inputTime = ''
      this.inputTitle = ''
      this.inputDetail = ''
      this.showDialog = false
    },
    lastStep () {
      this.$emit('updateIndex', 0)
    },
    nextStep () {
      this.$emit('updateData', {
        index: 2,
        projData: this.projData
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.event {
  .event-container {
    padding: 20px;
    border: 1px solid yellow;
    margin-bottom: 30px;
    .title {
      width: 100%;
      text-align: center;
      line-height: 28px;
      margin-bottom: 10px;
    }
  }
}
</style>
