<template>
  <div class="footer">
    <div class="content">
      <div class="icon-box">
        <a :href="social.url" target="_blank" v-for="(social, index) in socialList" :key="index">
          <i class=" fab" :class="social.fontClass"></i>
        </a>
      </div>
      <div class="name">
        <span class="name">LIANBI</span>
      </div>
      <div class="addr">
        <span></span>
      </div>
      <div class="right">
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
        fontClass: 'fa-weibo',
      }, {
        url: 'https://www.facebook.com/groups/1301707606641533/',
        fontClass: 'fa-facebook',
      }, {
        url: 'https://github.com/bitcv',
        fontClass: 'fa-github',
      }, {
        url: 'https://t.me/bcvtoken',
        fontClass: 'fa-telegram-plane',
      }, {
        url: 'https://twitter.com/BCVofficial',
        fontClass: 'fa-twitter',
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
      a {
        margin-right: 13px;
        font-size: 28px;
        color: #9B9B9B;
        &:hover {
          color: #F5A623;
        }
      }
    }
    .name {
      font-size: 30px;
      line-height: 42px;
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
