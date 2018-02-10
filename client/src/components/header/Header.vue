<template>
  <div class="header">
    <div class="content">
      <router-link to="/" class="middle-hide">
        <div class="logo">
          <img src="/static/img/logo.png" alt="">
        </div>
      </router-link>
      <div class="nav">
        <ul class="mobile-nav">
          <li v-for="(menu, index) in menuList" :key="index" :class="{ active : menuIndex === index }">
            <router-link :to="menu.url">
              <span class="nav-text" @click="menuSwitch(index)">{{ menu.text }}</span>
            </router-link>
          </li>
        </ul>
      </div>
      <div class="info-box" v-if="isOnline">
        <img :src="avatarUrl" alt="" class="mobile-hide">
        <span class="nickname mobile-hide">{{ mobile }}</span>
        <div class="signout btn mobile-btn" @click="signout"><span class="btn-text">退出登录</span></div>
      </div>
      <div class="btn-box" v-else>
        <router-link to="/signin">
          <div class="signin btn mobile-btn"><span class="btn-text">登录</span></div>
        </router-link>
        <router-link to="/signup">
          <div class="signup btn mobile-btn"><span class="btn-text">注册</span></div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      isOnline: false,
      avatarUrl: '/static/img/avatar.png',
      mobile: 'yk_23723952234',
      menuIndex: 0,
      menuList: [
        {url: '/', text: '首页'},
        {url: '/projList', text: '发现'},
        {url: '/candyRoom', text: '余币宝'},
        {url: '/newlist', text: '资讯'}
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

    // getCookie
    var userId = localStorage.getItem('userId')
    if (userId) {
      this.isOnline = true
      this.mobile = localStorage.getItem('mobile')
      var avatarUrl = localStorage.getItem('avatarUrl')
      if (avatarUrl) {
        this.avatarUrl = avatarUrl
      }
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
    },
    signout () {
      var that = this
      this.$http.post('/api/signout', {}).then(function (res) {
        console.log('signout')
        var resData = res.data
        if (resData.errcode === 0) {
          localStorage.removeItem('userId')
          localStorage.removeItem('mobile')
          localStorage.removeItem('avatarUrl')
          that.$router.go(0)
        }
      })
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
        width: 65px;
        height: 30px;
        border-radius: 4px;
        .btn-text {
          font-size: 16px;
          line-height: 30px;
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
    .info-box {
      display: flex;
      align-items: center;
      .nickname {
        padding-left: 8px;
        font-size: 14px;
        color: #4A4A4A;
      }
      .signout {
        cursor: pointer;
        background-color: #282828;
        margin: 0 42px 0 16px;
        width: 100px;
        height: 35px;
        border-radius: 4px;
        .btn-text {
          font-size: 16px;
          line-height: 35px;
          display: block;
          width: 100%;
          height: 100%;
          text-align: center;
          color: #FFCF81;
        }
      }
    }
  }
  @media screen and (max-width: 750px) {
    .middle-hide {
      display: none;
    }
  }
  @media screen and (max-width: 600px) {
    .mobile-hide {
      display: none;
    }
    .mobile-nav {
      li {
        margin-left: 20px !important;
      }
    }
    .content {
      /*justify-content: space-around !important;*/
    }
    .mobile-btn {
      width: 50px !important;
      height: 30px !important;
      border-radius: 4px !important;
      &.signout {
        width: 80px !important;
      }
      .btn-text {
        font-size: 16px !important;
        line-height: 30px !important;
        display: block !important;
        width: 100% !important;
        height: 100% !important;
        text-align: center !important;
      }
    }
  }
}
</style>
