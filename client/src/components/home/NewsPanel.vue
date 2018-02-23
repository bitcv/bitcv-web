<template>
  <div class="news-panel">
    <h3 class="panel-title">lianbi说</h3>
    <div class="list-container">
      <a class="item-container" v-for="(news, index) in newsList" :key="index" :href="news.url" target="_blank">
        <router-link :to="{ path: 'newdetail/' + news.id}">
        <span class="text">{{ news.title }}</span>
        <span class="time">{{ news.releaseTime }}</span>
        </router-link>
      </a>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      newsList: []
    }
  },
  mounted () {
    var that = this
    this.$http.post('api/getNewsList', {
      pageno: 1,
      perpage: 10
    }).then(function (res) {
      var resdata = res.data
      console.log(resdata)
      if (resdata.errcode === 0) {
        that.newsList = resdata.data
      }
    })
  }
}
</script>

<style lang="scss" scoped>
.news-panel {
  padding: 20px;
  background-color: #FFF;
  width: 100%;
  box-sizing: border-box;
  .list-container {
    width: 100%;
    .item-container {
      display: block;
      box-sizing: border-box;
      width: 350px;
      height: 100px;
      padding: 18px;
      margin: 0 -18px;
      position:relative;
      border-bottom: 1px solid #FFF;
      .text {
        font-size: 16px;
        line-height: 22px;
        color: #4A4A4A;
      }
      .time {
        font-size: 12px;
        line-height: 17px;
        color: #A5A2A6;
        position: absolute;
        right: 25px;
        bottom: 20px;
      }
    }
    .item-container:hover {
      border-bottom: 1px solid #FFCF81;
      .text {
        color: #F5A623;
      }
    }
  }
}
</style>
