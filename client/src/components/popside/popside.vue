<template>
  <div class="popside" :class="{'popside-show': show}">
    <div class="popside-mask" @click="toggle"></div>
    <div class="popside-container">
      <router-link v-if="!token" class="popside-avatar hidden-sm" to="/signin" @click.native="toggle">登录</router-link>
      <img v-else class="popside-avatar" src="/static/img/avatar.png">
      <ul class="nav navbar-nav popside-nav">
        <router-link tag="li" active-class="active" exact to="/" @click.native="toggle"><a href="javascript:;">主页</a></router-link>
        <router-link tag="li" active-class="active" to="/discover" @click.native="toggle"><a href="javascript:;">发现</a></router-link>
        <router-link tag="li" active-class="active" to="/candyRoom" @click.native="toggle"><a href="javascript:;">余币宝</a></router-link>
        <router-link tag="li" active-class="active" to="/newlist" @click.native="toggle"><a href="javascript:;">资讯</a></router-link>
        <router-link v-if="token" tag="li" to="/" @click.native="signout"><a href="javascript:;">注销登录</a></router-link>
      </ul>
    </div>
  </div>
</template>

<script>
import {mapState} from 'vuex'

export default {
  props: {
    value: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      show: false
    }
  },
  computed: {
    ...mapState({
      token: state => state.token
    })
  },
  watch: {
    value: {
      handler (val) {
        this.show = val
      },
      immediate: true
    }
  },
  methods: {
    toggle () {
      this.$emit('input', !this.show)
    },
    signout () {
      this.$emit('signout')
      this.toggle()
    }
  }
}
</script>

<style lang="scss">
@import '~@/styles/variables';

.popside-avatar {
  display: none;
}

@media (max-width: 991px) {
  .popside-mask, .popside-container {
    position: fixed;
    right: 0;
    top: 0;
    bottom: 0;
  }
  .popside-mask {
    left: 0;
    z-index: 1000;
    display: none;
  }
  .popside-show .popside-mask {
    display: block;
  }
  .popside-container {
    width: 55%;
    z-index: 1001;
    display: flex;
    flex-direction: column;
    align-items: center;
    // justify-content: center;
    background-color: rgba(255, 255, 255, .8);
    box-shadow: -1px 0px 11px 0 rgba(255, 207, 129, .34);
    transition: all .35s;
    transform: translate3d(110%, 0, 0);
  }
  .popside-show .popside-container {
    transform: translate3d(0, 0, 0);
  }
  .popside-avatar {
    display: inline-block;
    width: 70px;
    height: 70px;
    margin-top: 80px;
    margin-bottom: 80px;
    font-size: 20px;
    color: #f5a623;
    line-height: 70px;
    text-align: center;
    border: 2px solid #4a4a4a;
    border-radius: 50%;
    -webkit-tap-highlight-color: transparent;
  }
  .popside-nav {
    list-style-type: none;
    padding-left: 0;
    width: 100%;

    > li {
      margin-bottom: 10px;

      > a {
        display: block;
        padding: 0;

        font-size: 16px;
        line-height: 46px;
        color: #4a4a4a;
        text-align: center;
        text-decoration: none;
      }

      &.active > a,
      & > a.router-link-active {
        color: #f5a623;
        background-color: #000000;
      }
    }
  }
}

@media (min-width: 992px) {
  .popside-nav.navbar-nav {
    margin-left: 20%;

    > li {
      > a {
        padding-left: 5px;
        padding-right: 5px;
        padding-bottom: 13px;
        font-size: 16px;
        border-bottom: 2px solid transparent;
      }

      & + li {
        margin-left: 30px;
      }

      &.active > a,
      &.active > a:hover,
      &.active > a:focus {
        color: $primary-color;
        background-color: transparent;
        border-bottom-color: $primary-color;
      }
    }
  }
}
</style>
