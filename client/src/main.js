// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import axios from 'axios'
import store from './store'
import router from './router'
import {sync} from 'vuex-router-sync'

import ElementUI from 'element-ui'
import 'swiper/dist/css/swiper.css'
import 'element-ui/lib/theme-chalk/index.css'
import common from './common'
Vue.use(common)

require('es6-promise').polyfill()
Vue.use(ElementUI)

Vue.axios = axios
Vue.prototype.$http = axios

Vue.config.productionTip = false

sync(store, router)

axios.interceptors.response.use(
  response => {
    if (response.data['errcode'] === 302) {
      store.commit('cleanUserInfo')

      router.push('/')

      return Promise.reject(response)
    }
    return response
  },
  error => {
    if (error.response.status === 302) {
      store.commit('cleanUserInfo')

      router.push('/')
    }
    return Promise.reject(error)
  }
)

store.commit('updateUserInfo')

/* eslint-disable no-new */
new Vue({
  el: '#app',
  store,
  router,
  components: { App },
  template: '<App/>',
  data: {
    eventHub: new Vue()
  }
})
