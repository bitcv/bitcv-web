<template>
  <div class="navbar">
    <router-link to="/" class="brand"><img src="../../assets/images/brand.png"></router-link>

    <el-menu class="navbar-right" mode="horizontal">
      <el-submenu index="1">
        <template slot="title">
          <img class="avatar" :src="avatarUrl">
          <span class="user-phone">{{ userInfo ? userInfo.mobile : ' ' }}</span>
        </template>
        <el-menu-item index="1-1" @click="signout">退出登录</el-menu-item>
      </el-submenu>
    </el-menu>
  </div>
</template>

<script>
import {mapState, mapMutations} from 'vuex'

export default {
  computed: {
    ...mapState({
      userInfo: state => state.userInfo
    }),
    hasToken () {
      return this.userInfo && Object.keys(this.userInfo).length > 0
    },
    avatarUrl () {
      if (this.userInfo && this.userInfo.avatarUrl) {
        return this.userInfo.avatarUrl
      }

      return require('@/assets/images/avatar.png')
    }
  },
  methods: {
    ...mapMutations(['cleanUserInfo']),
    signout () {
      this.cleanUserInfo()

      this.$router.replace('/signin')
    }
  }
}
</script>

<style lang="scss">
.navbar {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  height: 60px;
  background-color: #656565;

  .brand {
    padding: 10px;
    height: 100%;
    box-sizing: border-box;
    img {
      display: block;
      height: 100%;
      box-sizing: border-box;
    }
  }

  .navbar-right {
    float: right;
  }

  .el-menu {
    background-color: transparent;

    .el-submenu {
      &.is-opened {
        background-color: #f99638;
      }

      .el-submenu__title {
        color: #fff;
        &:hover {
          background-color: transparent;

          > .el-submenu__icon-arrow {
            color: #fff;
          }
        }

        .avatar {
          border-radius: 50%;
          width: 40px;
          height: 40px;
          vertical-align: middle;
          margin-right: 10px;
        }
      }
    }
  }
}
</style>
