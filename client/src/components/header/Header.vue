<template>
  <nav class="navbar navbar-inverse navbar-static-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" :class="{collapsed: !showSide}" @click="navbarToggle">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>

        <router-link to="/" class="navbar-brand"><img src="/static/img/logo.png" alt="BitCV" height="30"></router-link>
      </div>

      <popside v-model="showSide" :has-token="hasToken" @signout="$emit('signout')"></popside>

      <div v-if="hasToken" class="dropdown navbar-right hidden-sm hidden-xs" :class="{open: showDropdown}" @mouseenter="showDropdown = true" @mouseleave="showDropdown = false">
        <!-- <button type="button" class="btn navbar-btn btn-default btn-outline" @click="$emit('signout')">注销登录</button> -->
        <a href="javascrip:;" class="dropdown-toggle"><img src="/static/img/avatar.png" class="img-circle"></a>
        <ul class="dropdown-menu">
          <li><router-link to="/wallet">我的钱包</router-link></li>
          <li><a href="javascript:;" @click="$emit('signout')">注销登录</a></li>
        </ul>
      </div>
      <div v-else class="navbar-right hidden-sm hidden-xs">
        <router-link class="btn navbar-btn btn-default btn-outline" to="/signup">注册</router-link>
        <span>&nbsp;&nbsp;</span>
        <router-link class="btn navbar-btn btn-default btn-outline" to="/signin">登录</router-link>
      </div>
    </div>
  </nav>
</template>

<script>
import Popside from '@/components/popside/popside'
export default {
  components: {
    Popside
  },
  props: {
    hasToken: Boolean
  },
  data () {
    return {
      showSide: false,
      showDropdown: false
    }
  },
  methods: {
    navbarToggle () {
      this.showSide = !this.showSide
    }
  },
  mounted () {
    this.showSide = false
    this.showDropdown = false
  }
}
</script>

<style lang="scss">
@import '~@/styles/variables';
.navbar-inverse {
  background-color: #656565;
  border-color: #666;
  .navbar-toggle {
    padding: 7px 10px;
    padding-left: 15px;
    margin-top: 5px;
    margin-bottom: 5px;
    border-color: transparent;
    .icon-bar {
      position: relative;
      width: 24px;
      height: 3px;
      &:before {
        position: absolute;
        content: '';
        width: 3px;
        height: 3px;
        left: -5px;
        background-color: #fff;
        border-radius: 1px;
      }
    }
  }
  .navbar-brand {
    padding-top: 10px;
    padding-bottom: 10px;
    > img {
      display: block;
    }
  }
  .dropdown-toggle {
    display: block;
    padding: 5px;
    > img {
      width: 40px;
      height: 40px;
      display: block;
    }
  }
  .btn-outline {
    color: $gray;
    &:hover, &:focus {
      color: #fff;
    }
  }
}
</style>
