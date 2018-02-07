<template>
  <div class="news-panel">
    <h3 class="panel-title">lianbi说</h3>
    <div class="list-container">
      <a class="item-container" v-for="(news, index) in newsList" :key="index" :href="news.url" target="_blank">
        <span class="text">{{ news.title }}</span>
        <span class="time">{{ news.time }}</span>
      </a>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      newsList: [{
        title: 'BitCV - 区块链数字资产管理服务引擎',
        url: 'https://www.bitcv.one/',
        time: '2018-01-31 17:48:32'
      }, {
        title: '数字资产管理平台BitCVBitCV获千万级天使融资',
        url: 'http://www.sohu.com/a/219786398_439726',
        time: '2018-01-30 10:41'
      }, {
        title: '腾讯云加入Hyperledger超级账本项目，深度参与国际区块链生态建设',
        url: 'http://www.xinhuanet.com/itown/2018-01/30/c_136935789.htm',
        time: '2018-01-30 10:59:49'
      }, {
        title: '区块链新锐BitCV获千万级天使融资 或成数字资产管理平台开创者',
        url: 'http://industry.caijing.com.cn/20180130/4401295.shtml',
        time: '2018-01-30 10:52:27'
      }]
    }
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
