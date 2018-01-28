<template>
  <div class="proj-list-panel">
    <h3 class="panel-title">项目直通车</h3>
    <div class="proj-item-wrapper">
      <router-link :to="{ path: 'projDetail/' + project.id}" v-for="project in projList" :key="project.id">
        <proj-item-box  :projData="project"></proj-item-box>
      </router-link>
    </div>
    <div class="bottom-btn">更多优秀项目</div>
  </div>
</template>

<script>
import ProjItemBox from '@/components/home/ProjItemBox'

export default {
  data () {
    return {
      dataCount: 0,
      projList: []
    }
  },
  mounted () {
    var that = this
    this.$http.post('/api/getProjList', {
      pageno: 1,
      perpage: 10
    }).then(function (res) {
      var resData = res.data
      if (resData.errcode === 0) {
        that.dataCount = resData.data.dataCount
        that.projList = resData.data.projList
      }
    })
  },
  components: {
    ProjItemBox
  }
}
</script>

<style lang="scss" scoped>
.proj-list-panel {
  background-color: #FFF;
  width: 100%;
  box-sizing: border-box;
  position: relative;
  .panel-title {
    padding: 20px;
  }
  .proj-item-wrapper {
    padding: 0 50px;
  }
  .bottom-btn {
    width: 100%;
    height: 54px;
    line-height: 54px;
    text-align: center;
    background-color: #D8D8D8;
    color: #FFF;
    cursor: pointer;
  }
}
</style>
