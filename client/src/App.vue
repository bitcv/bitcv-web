<template>
  <div id="app">
    <v-header v-if="visible"></v-header>
    <div class="main-container">
      <router-view/>
    </div>
    <v-footer v-if="visible"></v-footer>
  </div>
</template>

<script>
import {mapState} from 'vuex'
import Header from '@/components/header/header'
import Footer from '@/components/footer/Footer'

export default {
  name: 'App',
  components: {
    vHeader: Header,
    vFooter: Footer
  },
  computed: {
    ...mapState({
      path: state => state.route.path
    }),
    visible () {
      return this.path !== '/share'
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
