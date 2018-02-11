<template>
  <div class="top-list">
    <h3 class="panel-title">推荐项目</h3>
    <div class="info-container">
      <router-link :to="{ path: 'projDetail/' + project.id}" v-for="project in projList" :key="project.id" class="info-area">
          <span>{{ project.tokenName }}</span>
          <span>{{ project.tokenPrice }}</span>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      projList: []
    }
  },
  mounted () {
    var that = this
    this.$http.post('/api/getProjList', {
      pageno: 1,
      perpage: 3
    }).then(function (res) {
      var resData = res.data
      if (resData.errcode === 0) {
        that.projList = resData.data.projList
      }
    })
  }
}
</script>

<style lang="scss" scoped>
.top-list {
  box-sizing: border-box;
  width: 350px;
  padding: 20px;
  background-color: #FFF;
  .info-container {
    box-sizing: border-box;
    width: 100%;
    padding: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    .info-area {
      box-sizing: border-box;
      width: 294px;
      height: 81px;
      background-color: #000;
      margin-top: 4px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 30px;
      color: #FFCF81;
      :last-child {
        color: #FFF;
      }
      &:first-child {
        background: linear-gradient(#000, #525252);
      }
      &:nth-child(2) {
        background: linear-gradient(#616161, #9BC5C3);
      }
      &:last-child {
        background: linear-gradient(#243949, #517FA4);
      }
    }
  }
}
</style>
