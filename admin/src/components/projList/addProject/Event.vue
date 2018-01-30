<template>
  <div class="event">
    <el-form label-width="120px">
      <div class="event-box">
        <el-item label="标题">
          <el-input></el-input>
        </el-item>
        <el-item label="事件描述">
          <el-input></el-input>
        </el-item>
      </div>
      <el-item>
        <el-button type="primary"></el-button>
        <el-button>取消</el-button>
      </el-item>
    </el-form>
  </div>
</template>

<script>
export default {
  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
</style>
