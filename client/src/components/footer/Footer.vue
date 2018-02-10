<template>
  <div class="footer">
    <div class="content">
      <div class="name">
        <img src="/static/img/logo.png" alt="">
      </div>
      <div class="icon-box">
        <a :href="social.url" target="_blank" v-for="(social, index) in socialList" :key="index">
          <i class=" fab" :class="social.fontClass"></i>
        </a>
      </div>
      <div class="bottom">
        <span>copyright 2018 lian.io</span>
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
        fontClass: 'fa-weibo'
      }, {
        url: 'https://www.facebook.com/groups/1301707606641533/',
        fontClass: 'fa-facebook'
      }, {
        url: 'https://github.com/bitcv',
        fontClass: 'fa-github'
      }, {
        url: 'https://t.me/bcvtoken',
        fontClass: 'fa-telegram-plane'
      }, {
        url: 'https://twitter.com/BCVofficial',
        fontClass: 'fa-twitter'
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
  padding: 10px;
  .content {
    width: 1200px;
    height: 222px;
    display: flex;
    box-sizing: border-box;
    padding: 33px 0 12px;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    box-sizing: border-box;
    .icon-box {
      a {
        margin-right: 68px;
        font-size: 28px;
        color: #9B9B9B;
        &:hover {
          color: #F5A623;
        }
        &:last-child {
          margin: 0;
        }
      }
    }
    .name {
      img {
        width: 133px;
        height: 25px;
      }
    }
    .bottom {
      font-size: 14px;
      line-height: 20px;
      color: #9B9B9B;
    }
  }
}
</style>
