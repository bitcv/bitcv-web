<template>
  <div id="app">
    <v-header v-if="visible" :has-token="hasToken" @signout="signout"></v-header>
    <div class="main-container">
      <router-view/>
    </div>
    <v-footer v-if="visible"></v-footer>

    <div v-if="device != 'pc' && downloadBarStatus" class="download" @click='downloadApp'>
      <img src="/static/img/download_bar3.png" alt="">
      <span class="download-close" @click.stop='downloadBarStatus = false'></span>
    </div>
  </div>
</template>

<script>
import {mapState, mapMutations} from 'vuex'
import Header from '@/components/header'
import Footer from '@/components/footer'

export default {
  name: 'App',
  data () {
    return {
      downloadBarStatus: true
    }
  },
  components: {
    vHeader: Header,
    vFooter: Footer
  },
  computed: {
    ...mapState({
      path: state => state.route.path,
      userInfo: state => state.userInfo,
      device: state => state.device
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
      this.$http.post('/api/signout', []).then((res) => {
        if (res.data.errcode === 0) {
          this.cleanUserInfo()
        }
      })
      // this.$router.push('/')
    },
    downloadApp () {
      window.location.href = 'https://www.bitcv.app/?from=bitcvCom'
    }
  }
}
</script>

<style lang="scss">
  @import 'styles/index';
  // @import "./App.scss";

  #launcher {
    bottom: 50px!important;
  }
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

.download {
  z-index: 10000;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  img {
    width: 100%;
  }
  .download-close {
    position: absolute;
    top: 50%;
    left: 12px;
    width: 14px;
    height: 14px;
    margin-top: -7px;
    background-image: url('/static/img/btn_close3.png');
    background-size: 100% 100%;
  }
}
</style>
