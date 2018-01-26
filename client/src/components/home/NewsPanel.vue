<template>
  <div class="news-panel">
    <h3 class="panel-title">lianbi说</h3>
    <div class="list-container">
      <div class="item-container" v-for="item in 4" :key="item">
        <span class="text">如果世界漆黑，其实我很美，在爱情里面进退，最多被消费</span>
        <span class="time">2018-01-09</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {}
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
