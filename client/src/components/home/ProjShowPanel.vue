<template>
  <div class="proj-show-panel">
    <h3 class="panel-title">发现新公司</h3>
    <div class="main-container">
      <proj-intro v-for="project in projList" :key="project.id" :proj-data="project"></proj-intro>
    </div>
  </div>
</template>

<script>
import ProjIntro from '@/components/home/ProjIntro'

export default {
  data () {
    return {
      projList: []
    }
  },
  components: {
    ProjIntro
  },
  mounted: function () {
    var that = this
    this.$http.post('/api/getProjList', {
      pageno: 2,
      perpage: 6
    }).then(function (res) {
      var resData = res.data
      if (resData.errcode === 0) {
        console.log(resData)
        that.projList = resData.data.projList
      } else {
        alert(resData.errmsg)
      }
    })
  }
}
</script>

<style lang="scss" scoped>
.proj-show-panel {
  padding: 20px;
  background-color: #FFF;
  .main-container {
    margin: 0 -20px;
    width: 826px;
    height: 324px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-content: space-around;
  }
}
</style>
