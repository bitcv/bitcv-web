<template>
  <div class="add-project">
    <div class="step-container">
      <el-steps :active="curIndex" align-center finish-status="success">
        <el-step title="基本信息"></el-step>
        <el-step title="发展历程"></el-step>
        <el-step title="团队成员"></el-step>
        <el-step title="合作伙伴"></el-step>
        <el-step title="媒体报道"></el-step>
      </el-steps>
    </div>
    <div class="form-container">
      <basic v-show="curIndex === 1"></basic>
      <event v-show="curIndex === 1"></event>
      <team v-show="curIndex === 1"></team>
      <partner v-show="curIndex === 1"></partner>
      <media v-show="curIndex === 1"></media>
    </div>
  </div>
</template>

<script>
import Basic from '@/components/projList/addProject/Basic'
import Event from '@/components/projList/addProject/Event'
import Team from '@/components/projList/addProject/Team'
import Partner from '@/components/projList/addProject/Partner'
import Media from '@/components/projList/addProject/Media'

export default {
  data () {
    return {
      curIndex: 1,
      projData: {
        nameCn: '',
        nameEn: '',
        webUrl: '',
        logo: '',
        abstract: '',
        tagList: [],
        startTime: '',
        endTime: '',
        eventList: [],
        teamList: [],
        partnerList: [],
        mediaList: []
      }
    }
  },
  watch: {
    $route (cur) {
      switch (cur.path) {
        case '/addProject/basic':
          this.curIndex = 0
          break
        case '/addProject/event':
          this.curIndex = 1
          break
        case '/addProject/team':
          this.curIndex = 2
          break
        case '/addProject/partner':
          this.curIndex = 3
          break
        case '/addProject/media':
          this.curIndex = 4
          break
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.add-project {
  .step-container {
    box-sizing: border-box;
    width: 100%;
    padding: 20px;
  }
  .form-container {
    box-sizing: border-box;
    width: 100%;
    padding: 10px 80px;
  }
}
</style>
