<template>
  <div class="header">
    <div class="content">
      <router-link to="/">
        <div class="logo">
          <img src="/static/img/logo.png" alt="">
        </div>
      </router-link>
      <div class="nav">
        <ul>
          <li v-for="(menu, index) in menuList" :key="index" :class="{ active : menuIndex === index }">
            <router-link :to="menu.url">
              <span class="nav-text" @click="menuSwitch(index)">{{ menu.text }}</span>
            </router-link>
          </li>
        </ul>
      </div>
      <div class="btn-box">
        <router-link to="signin">
          <div class="signin btn"><span class="btn-text">登录</span></div>
        </router-link>
        <router-link to="signup">
          <div class="signup btn"><span class="btn-text">注册</span></div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      menuIndex: 0,
      menuList: [
        {url: '/', text: '首页'},
        {url: 'projList', text: '发现'},
        {url: '/', text: '资讯'}
      ]
    }
  },
  mounted () {
    if (this.$route.path === '/') {
      this.menuIndex = 0
    }
    if (this.$route.path === '/projList') {
      this.menuIndex = 1
    }
  },
  watch: {
    '$route' (to, from) {
      if (this.$route.path === '/') {
        this.menuIndex = 0
      }
      if (this.$route.path === '/projList') {
        this.menuIndex = 1
      }
    }
  },
  methods: {
    menuSwitch (index) {
      this.menuIndex = index
    }
  }
}
</script>

<style lang="scss" scoped>
.header {
  width: 100%;
  height: 60px;
  background-color: #FFF;
  display: flex;
  justify-content: center;
  .content {
    width: 1200px;
    height: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    .logo {
      width: 161px;
      height: 30px;
      img {
        width: 100%;
        height: 100%;
      }
    }
    .nav {
      height: 100%;
      color: #8D8D8D;
      ul {
        height: 100%;
        display: flex;
        align-items: center;
        li {
          box-sizing: border-box;
          height: 100%;
          padding-top: 18px;
          margin-left: 50px;
          &.active {
            border-bottom: 5px solid #FFCF81;
            color: #4A4A4A;
          }
          .nav-text {
            font-size: 18px;
            line-height: 25px;
          }
        }
      }
    }
    .btn-box {
      .btn {
        display: inline-block;
        width: 75px;
        height: 40px;
        border-radius: 4px;
        .btn-text {
          font-size: 20px;
          line-height: 40px;
          display: block;
          width: 100%;
          height: 100%;
          text-align: center;
        }
      }
      .signup {
        background-color: #282828;
        margin: 0 42px 0 16px;
        .btn-text {
          color: #FFCF81;
        }
      }
      .signin {
        border: 0.5px solid #CECECE;
        color: #8D8D8D;
      }
    }
  }
}
</style>
