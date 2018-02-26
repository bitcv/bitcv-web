<template>
  <div class="navbar navbar-static-top navbar-reverse">
    <div class="container">
      <ul class="nav navbar-nav">
        <router-link tag="li" active-class="active" to="/" exact><a href="javascript:;">首页</a></router-link>
        <router-link tag="li" active-class="active" to="/projList"><a href="javascript:;">发现</a></router-link>
        <router-link tag="li" active-class="active" to="/candyRoom"><a href="javascript:;">余币宝</a></router-link>
        <router-link tag="li" active-class="active" to="/newslist"><a href="javascript:;">资讯</a></router-link>
      </ul>
      <div class="navbar-right">
        <router-link class="btn btn-outline navbar-btn" to="/signin">登录</router-link>
        <router-link class="btn btn-outline navbar-btn" to="/signup">注册</router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {}
}
</script>

<style lang="scss">
@import '~@/styles/variables';
</style>
