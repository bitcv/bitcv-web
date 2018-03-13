<template>
  <div id="app">
    <v-header v-if="visible" :has-token="hasToken" @signout="signout"></v-header>
    <div class="main-container">
      <router-view/>
    </div>
    <v-footer v-if="visible"></v-footer>
  </div>
</template>

<script>
import {mapState, mapMutations} from 'vuex'
import Header from '@/components/header'
import Footer from '@/components/footer'

export default {
  name: 'App',
  components: {
    vHeader: Header,
    vFooter: Footer
  },
  computed: {
    ...mapState({
      path: state => state.route.path,
      userInfo: state => state.userInfo
    }),
    visible () {
      return this.path !== '/share'
    },
    hasToken () {
      if (this.userInfo) {
        return Object.keys(this.userInfo).length > 0
      }

      return false
    }
  },
  watch: {
    hasToken (val) {
      if (!val) {
        this.$router.push('/signin')
      }
    }
  },
  methods: {
    ...mapMutations(['cleanUserInfo']),
    signout () {
      this.cleanUserInfo()
      // this.$router.push('/')
    }
  }
}
</script>

<style lang="scss">
  @import 'styles/index';
  // @import "./App.scss";
</style>

<style lang="scss" scoped>
.main-container {
  margin: 40px 0 76px;
  // display: flex;
  // justify-content: center;
  // overflow: hidden;
  // min-height: calc(100vh - 418px);
  // background-color: #F2F2F2;
}
</style>
