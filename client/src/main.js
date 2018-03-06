// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import axios from 'axios'
import store from './store'
import router from './router'
import {sync} from 'vuex-router-sync'

import 'nprogress/nprogress.css'
import NProgress from 'nprogress'

import ElementUI from 'element-ui'
import 'swiper/dist/css/swiper.css'
import 'element-ui/lib/theme-chalk/index.css'
import common from './common'
// 多语言
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)
const messages = {
  // 简体中文
  cn: {
    label: {
      lang: '中文',
      home: '主页'
    }
  },
  // 英文
  en: {
    label: {
      lang: 'English',
      home: 'home'
    }
  }
}
const i18n = new VueI18n({
  // 定义默认语言
  locale: 'cn',
  messages
})

Vue.use(common)

require('es6-promise').polyfill()
Vue.use(ElementUI)

Vue.prototype.$http = axios
Vue.prototype.nprogress = NProgress
window.NProgress = NProgress

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

const getToken = () => {
  const userInfo = store.state.userInfo

  return userInfo && Object.keys(userInfo).length > 0
}

// 同步缓存中的用户信息
store.commit('updateUserInfo')

// 登录拦截
router.beforeEach((to, from, next) => {
  NProgress.start()
  if (to.matched.some(route => route.meta && route.meta.requiresAuth)) { // 需要校验登录信息
    const hasToken = getToken()

    if (hasToken) { // 有token
      next()
    } else { // 没有token
      // 跳转到登录页
      next('/signin')
    }
  }

  next()
})

router.afterEach((to, from) => {
  NProgress.done()
})

/* eslint-disable no-new */
new Vue({
  el: '#app',
  store,
  router,
  i18n,
  components: { App },
  template: '<App/>',
  data: {
    eventHub: new Vue()
  }
})
