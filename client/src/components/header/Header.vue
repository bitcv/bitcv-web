<template>
  <div class="header">
    <div class="content">
      <router-link to="/">
        <div class="logo">
          <img src="/static/img/logo.png" alt="">
        </div>
      </router-link>
      <div class="mobile-hide nav">
        <ul class="mobile-nav">
          <li v-for="(menu, index) in menuList" :key="index" :class="{ active : menuIndex === index }">
            <router-link :to="menu.url">
              <span class="nav-text" :class="mediaClass()" @click="menuSwitch(index)">{{ menu.text }}</span>
            </router-link>
          </li>
        </ul>
      </div>
      <div class="mobile-hide info-box" v-if="isOnline">
        <img :src="avatarUrl" alt="" v-on:click="dropdown">
        <ul class="nav-dropdown" v-bind:style="[displayStyles]">
          <li>
            <router-link class="nav-link" to="/candyRoom/myCandyOrder">订单</router-link>
          </li>
          <li>
            <a href="/admin/project" target="_blank">设置</a>
          </li>
          <li><a class="nav-link" @click="signout">注销</a></li>
        </ul>
        <span class="nickname mobile-hide">{{ mobile }}</span>
      </div>
      <div class="mobile-hide btn-box" :class="mediaClass()" v-else>
        <router-link to="/signin">
          <div class="signin btn mobile-btn"><span class="btn-text">登录</span></div>
        </router-link>
        <router-link to="/signup">
          <div class="signup btn mobile-btn"><span class="btn-text">注册</span></div>
        </router-link>
      </div>
    </div>
    <a href="javascript:;" class="btn-toggle middle-hide" @click="toggleSide"></a>
    <popside :isOnline="isOnline" @signout="signout" v-model="sideShow" class="middle-hide"></popside>
  </div>
</template>

<script>
import Popside from '@/components/popside/popside'

export default {
  components: {
    Popside
  },
  data () {
    return {
      sideShow: false,
      isOnline: false,
      avatarUrl: '/static/img/avatar.png',
      mobile: 'yk_23723952234',
      menuIndex: 0,
      menuList: [
        {url: '/', text: '首页'},
        {url: '/projList', text: '发现'},
        {url: '/candyRoom', text: '余币宝'},
        {url: '/newslist', text: '资讯'}
      ],
      displayStyles: {
        display: 'none'
      }
    }
  },
  created () {
    this.$root.eventHub.$on('checkLoginStatus', this.checkLoginStatus)
  },
  beforeDestroy () {
    this.$root.eventHub.$off('checkLoginStatus', this.checkLoginStatus)
  },
  mounted () {
    // this.updMenuIndex()
    this.checkLoginStatus()
  },
  watch: {
    '$route' () {
      this.updMenuIndex()
    }
  },
  methods: {
    checkLoginStatus () {
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
    toggleSide () {
      this.sideShow = !this.sideShow
    },
    menuSwitch (index) {
      this.menuIndex = index
    },
    updMenuIndex () {
      if (this.$route.path === '/') {
        this.menuIndex = 0
      } else if (this.$route.path.indexOf('/projList') === 0 || this.$route.path.indexOf('/projDetail') === 0) {
        this.menuIndex = 1
      } else if (this.$route.path.indexOf('/candyRoom') === 0) {
        this.menuIndex = 2
      } else if (this.$route.path.indexOf('/newlist') === 0 || this.$route.path.indexOf('/newdetail') === 0) {
        this.menuIndex = 3
      }
    },
    signout () {
      console.log('signout')
      this.$http.post('/api/signout', {}).then((res) => {
        if (res.data.errcode === 0) {
          localStorage.removeItem('userId')
          localStorage.removeItem('mobile')
          localStorage.removeItem('avatarUrl')
          this.$router.go(0)
        }
      })
    },
    dropdown () {
      this.displayStyles.display = this.displayStyles.display === 'none' ? 'block' : 'none'
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
    box-sizing: border-box;
    padding: 0 10px;
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
            &.media-mobile {
              font-size: 16px;
            }
            font-size: 18px;
            line-height: 25px;
          }
        }
      }
    }
    .btn-box {
      &.media-mobile {
        width: 120px;
        .btn {
          width: 45px;
        }
        .signup {
          margin: 0 0 0 6px;
        }
      }
      .btn {
        display: inline-block;
        padding: 0;
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
      position: relative;
      .nickname {
        padding-left: 8px;
        font-size: 14px;
        color: #4A4A4A;
      }
      .nav-dropdown {
        display: none;
        box-sizing: border-box;
        max-height: calc(100vh - 61px);
        overflow-y: auto;
        position: absolute;
        top: 100%;
        background-color: #fff;
        padding: 10px 0;
        border: 1px solid #ddd;
        border-bottom-color: #ccc;
        text-align: left;
        border-radius: 4px;
        white-space: nowrap;
      }
      .nav-dropdown li {
        line-height: 1.8em;
        margin: 0;
        display: block;
      }
      .nav-dropdown a {
        padding: 0 24px 0 20px;
        cursor: pointer;
        &:hover {
          background-color: #FFCF81;
        }
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
  .btn-toggle {
    display: inline-block;
    float: right;
    width: 80px;
    height: 40px;
    margin: 10px 20px;
    background-color: transparent;
    background-repeat: no-repeat;
    background-position: center;
    background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB0PSIxNTE5Mzc5ODI5MjkyIiBjbGFzcz0iaWNvbiIgc3R5bGU9IiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHAtaWQ9IjE4MjcxIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgd2lkdGg9IjY0IiBoZWlnaHQ9IjY0Ij48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwvc3R5bGU+PC9kZWZzPjxwYXRoIGQ9Ik0xNDIuMjIyMjIyIDE3MC42NjY2NjdtLTU2Ljg4ODg4OSAwYTU2Ljg4ODg4OSA1Ni44ODg4ODkgMCAxIDAgMTEzLjc3Nzc3OCAwIDU2Ljg4ODg4OSA1Ni44ODg4ODkgMCAxIDAtMTEzLjc3Nzc3OCAwWiIgZmlsbD0iI2Y1YTYyMyIgcC1pZD0iMTgyNzIiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guNzc4MTA2OS4wLmkyMyIgY2xhc3M9IiI+PC9wYXRoPjxwYXRoIGQ9Ik0zMTIuODg4ODg5IDExMy43Nzc3NzhoNjI1Ljc3Nzc3OHYxMTMuNzc3Nzc4SDMxMi44ODg4ODl6IiBmaWxsPSIjNGE0YTRhIiBwLWlkPSIxODI3MyIgZGF0YS1zcG0tYW5jaG9yLWlkPSJhMzEzeC43NzgxMDY5LjAuaTE2IiBjbGFzcz0ic2VsZWN0ZWQiPjwvcGF0aD48cGF0aCBkPSJNMTQyLjIyMjIyMiAzOTguMjIyMjIybS01Ni44ODg4ODkgMGE1Ni44ODg4ODkgNTYuODg4ODg5IDAgMSAwIDExMy43Nzc3NzggMCA1Ni44ODg4ODkgNTYuODg4ODg5IDAgMSAwLTExMy43Nzc3NzggMFoiIGZpbGw9IiNmNWE2MjMiIHAtaWQ9IjE4Mjc0IiBkYXRhLXNwbS1hbmNob3ItaWQ9ImEzMTN4Ljc3ODEwNjkuMC5pMjQiIGNsYXNzPSIiPjwvcGF0aD48cGF0aCBkPSJNMzEyLjg4ODg4OSAzNDEuMzMzMzMzaDYyNS43Nzc3Nzh2MTEzLjc3Nzc3OEgzMTIuODg4ODg5eiIgZmlsbD0iIzRhNGE0YSIgcC1pZD0iMTgyNzUiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guNzc4MTA2OS4wLmkxOSIgY2xhc3M9InNlbGVjdGVkIj48L3BhdGg+PHBhdGggZD0iTTE0Mi4yMjIyMjIgNjI1Ljc3Nzc3OG0tNTYuODg4ODg5IDBhNTYuODg4ODg5IDU2Ljg4ODg4OSAwIDEgMCAxMTMuNzc3Nzc4IDAgNTYuODg4ODg5IDU2Ljg4ODg4OSAwIDEgMC0xMTMuNzc3Nzc4IDBaIiBmaWxsPSIjZjVhNjIzIiBwLWlkPSIxODI3NiIgZGF0YS1zcG0tYW5jaG9yLWlkPSJhMzEzeC43NzgxMDY5LjAuaTI1IiBjbGFzcz0iIj48L3BhdGg+PHBhdGggZD0iTTMxMi44ODg4ODkgNTY4Ljg4ODg4OWg2MjUuNzc3Nzc4djExMy43Nzc3NzhIMzEyLjg4ODg4OXoiIGZpbGw9IiM0YTRhNGEiIHAtaWQ9IjE4Mjc3IiBkYXRhLXNwbS1hbmNob3ItaWQ9ImEzMTN4Ljc3ODEwNjkuMC5pMjEiIGNsYXNzPSJzZWxlY3RlZCI+PC9wYXRoPjxwYXRoIGQ9Ik0xNDIuMjIyMjIyIDg1My4zMzMzMzNtLTU2Ljg4ODg4OSAwYTU2Ljg4ODg4OSA1Ni44ODg4ODkgMCAxIDAgMTEzLjc3Nzc3OCAwIDU2Ljg4ODg4OSA1Ni44ODg4ODkgMCAxIDAtMTEzLjc3Nzc3OCAwWiIgZmlsbD0iI2Y1YTYyMyIgcC1pZD0iMTgyNzgiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guNzc4MTA2OS4wLmkyNiIgY2xhc3M9IiI+PC9wYXRoPjxwYXRoIGQ9Ik0zMTIuODg4ODg5IDc5Ni40NDQ0NDRoNjI1Ljc3Nzc3OHYxMTMuNzc3Nzc4SDMxMi44ODg4ODl6IiBmaWxsPSIjNGE0YTRhIiBwLWlkPSIxODI3OSIgZGF0YS1zcG0tYW5jaG9yLWlkPSJhMzEzeC43NzgxMDY5LjAuaTIyIiBjbGFzcz0ic2VsZWN0ZWQiPjwvcGF0aD48L3N2Zz4=);
    background-size: 100%;
    -webkit-tap-highlight-color: transparent;
  }
  .mobile-nav {
    a, a:focus, a:hover {
      color: #8D8D8D;
      text-decoration: none;
    }

    .active a,
    .active a:focus,
    .active a:hover {
      color: #4A4A4A;
    }
  }
  @media screen and (min-width: 768px) {
    .middle-hide {
      display: none;
    }
  }
  @media screen and (max-width: 767px) {
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
