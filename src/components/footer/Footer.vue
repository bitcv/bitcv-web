<template>
  <div class="footer">
    <div class="content">
      <div class="name">
        <img src="/static/img/logo.png" alt="BitCV">
      </div>
      <div class="icon-box">
        <a :href="social.url" target="_blank" v-for="(social, index) in socialList" :key="index">
          <i class="icon-bcv" :class="social.fontClass"></i>
        </a>
        <a href="https://medium.com/@bitcv" target="_blank">
          <img class="icon-bcv icon-medium" src="/static/img/logo_medium.svg" alt="">
        </a>
      </div>
      <div class="bottom">
        <span>Copyright © 2018-2019 BitCV. All Rights Reserved</span>
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
        fontClass: 'icon-weibo'
      }, {
        url: 'https://www.facebook.com/groups/1301707606641533/',
        fontClass: 'icon-facebook'
      }, {
        url: 'https://github.com/bitcv',
        fontClass: 'icon-github'
      }, {
        url: 'https://t.me/bcvtoken',
        fontClass: 'icon-telegram'
      }, {
        url: 'https://twitter.com/BCVofficial',
        fontClass: 'icon-twitter'
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
      width: 350px;
      display: flex;
      justify-content: space-around;
      flex-wrap: no-wrap;
      a {
        color: #9B9B9B;
        > .icon-bcv {
          font-size: 26px;
        }
        &:hover {
          color: #F5A623;
        }
        &:last-child {
          margin: 0;
        }
        .icon-medium {
          margin-top: 4px;
          width: 26px;
          height: 26px;
        }
      }
    }
    .name {
      img {
        width: 180px;
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
