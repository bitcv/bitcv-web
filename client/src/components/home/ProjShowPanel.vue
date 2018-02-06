<template>
  <div class="proj-show-panel">
    <h3 class="panel-title">发现新公司</h3>
    <div class="main-container">
      <router-link :to="{ path: 'projDetail/' + project.id}" v-for="project in projList" :key="project.id">
        <proj-intro :proj-data="project"></proj-intro>
      </router-link>
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
      pageno: 1,
      perpage: 6
    }).then(function (res) {
      var resData = res.data
      if (resData.errcode === 0) {
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
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-content: space-around;
  }
}
</style>
