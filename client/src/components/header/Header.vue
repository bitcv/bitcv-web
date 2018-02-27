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

      <popside v-model="showSide"></popside>

      <div class="navbar-right hidden-sm hidden-xs">
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
  data () {
    return {
      showSide: false
    }
  },
  methods: {
    navbarToggle () {
      this.showSide = !this.showSide
    }
  }
}
</script>

<style lang="scss">
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
}
</style>
