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
      <basic v-show="curIndex === 0" :proj-data="projData" @updateData="updateData" @updateIndex="updateIndex"></basic>
      <event v-show="curIndex === 1" :proj-data="projData" @updateData="updateData" @updateIndex="updateIndex"></event>
      <team v-show="curIndex === 2" :proj-data="projData" @updateData="updateData" @updateIndex="updateIndex"></team>
      <partner v-show="curIndex === 3" :proj-data="projData" @updateData="updateData" @updateIndex="updateIndex"></partner>
      <media v-show="curIndex === 4" :proj-data="projData" @updateData="updateData" @updateIndex="updateIndex"></media>
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
      curIndex: 0,
      projData: {
        nameCn: '',
        nameEn: '',
        webUrl: '',
        logoUrl: '',
        bannerUrl: '',
        abstract: '',
        whitePaperUrl: '',
        startTime: '',
        endTime: '',
        tokenName: '',
        tokenSymbol: '',
        tagList: [],
        eventList: [],
        teamList: [],
        partnerList: [],
        mediaList: []
      }
    }
  },
  methods: {
    updateData (data) {
      this.projData = data.projData
      if (data.index === 5) {
        var paramObj = JSON.parse(JSON.stringify(this.projData))
        paramObj.tagList = JSON.stringify(paramObj.tagList)
        paramObj.eventList = JSON.stringify(paramObj.eventList)
        paramObj.teamList = JSON.stringify(paramObj.teamList)
        paramObj.partnerList = JSON.stringify(paramObj.partnerList)
        paramObj.mediaList = JSON.stringify(paramObj.mediaList)
        var that = this
        this.$http.post('/api/addProject', paramObj).then(function (res) {
          var resData = res.data
          console.log(resData)
          if (resData.errcode === 0) {
            alert('创建成功，即将跳转至项目列表页')
            that.$router.push('/')
          } else {
            alert(resData.errmsg)
          }
        }).catch(function (error) {
          console.log(error)
        })
      } else {
        this.curIndex = data.index
      }
    },
    updateIndex (index) {
      this.curIndex = index
    }
  },
  components: {
    Basic,
    Event,
    Team,
    Partner,
    Media
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
