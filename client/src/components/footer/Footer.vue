<template>
  <div class="footer">
    <div class="content">
      <div class="icon-box">
        <a :href="social.url" target="_blank" v-for="(social, index) in socialList" :key="index">
          <img :src="social.logoUrl" @mouseover="social.logoUrl = social.hoverUrl" @mouseout="social.logoUrl = social.unhoverUrl" alt="">
        </a>
      </div>
      <div class="name">
        <span class="name">LIANBI</span>
      </div>
      <div class="addr">
        <span></span>
      </div>
      <div class="right">
        <span>@storiqa.2018 保留所有权利</span>
        <span>收集到的信息有助于展示个性化广告，为客户提供服务，并接受市场的统计数据。继续浏览即表示同意使用cookie</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      socialList: [{
        url: 'https://weibo.com/bitcv',
        logoUrl: '/static/logo/blog.png',
        unhoverUrl: '/static/logo/blog.png',
        hoverUrl: '/static/logo/blog-hover.png'
      }, {
        url: 'https://www.facebook.com/groups/1301707606641533/',
        logoUrl: '/static/logo/facebook.png',
        unhoverUrl: '/static/logo/facebook.png',
        hoverUrl: '/static/logo/facebook-hover.png'
      }, {
        url: 'https://github.com/bitcv',
        logoUrl: '/static/logo/github.png',
        unhoverUrl: '/static/logo/github.png',
        hoverUrl: '/static/logo/github-hover.png'
      }, {
        url: 'https://t.me/bcvtoken',
        logoUrl: '/static/logo/telegram.png',
        unhoverUrl: '/static/logo/telegram.png',
        hoverUrl: '/static/logo/telegram-hover.png'
      }, {
        url: 'https://twitter.com/BCVofficial',
        logoUrl: '/static/logo/twitter.png',
        unhoverUrl: '/static/logo/twitter.png',
        hoverUrl: '/static/logo/twitter-hover.png'
      }]
    }
  }
}
</script>

<style lang="scss" scoped>
.footer {
  display: flex;
  justify-content: center;
  background-color: #FFF;
  .content {
    width: 1200px;
    height: 150px;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 26px 0 17px;
    box-sizing: border-box;
    .icon-box {
      position: absolute;
      right: 0;
      top: 24px;
      color: #D8D8D8;
      img {
        width: 28px;
        height: 28px;
        margin-left: 20px;
      }
    }
    .name {
      font-size: 30px;
      line-height: 42px;
      color: #F5A623;
    }
    .addr {
      font-size: 16px;
      line-height: 22px;
      color: #9B9B9B;
    }
    .right {
      font-size: 14px;
      line-height: 20px;
      color: #9B9B9B;
    }
  }
}
</style>
