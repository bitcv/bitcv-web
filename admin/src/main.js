// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import store from './store'
import router from './router'
import {sync} from 'vuex-router-sync'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import axios from 'axios'

require('es6-promise').polyfill()
Vue.prototype.$http = axios
Vue.config.productionTip = false

Vue.use(ElementUI)

sync(store, router)

axios.interceptors.response.use(
  response => {
    if (response.data['errcode'] === 302) {
      store.commit('cleanUserInfo')

      router.push('/signin')

      return Promise.reject(response)
    }
    return response
  },
  error => {
    if (error.response.status === 302) {
      store.commit('cleanUserInfo')

      router.push('/signin')
    }
    return Promise.reject(error)
  }
)

// 同步缓存中的用户信息
const syncUserInfo = () => store.commit('updateUserInfo')

const getToken = () => {
  syncUserInfo()

  const userInfo = store.state.userInfo

  return userInfo && Object.keys(userInfo).length > 0
}

// 登录拦截
router.beforeEach((to, from, next) => {
  if (to.matched.some(route => route.meta && route.meta.requiresAuth)) {
    const token = getToken()

    if (!token) {
      next('/signin')
    }
  }

  next()
})

syncUserInfo()

/* eslint-disable no-new */
new Vue({
  el: '#app',
  store,
  router,
  components: { App },
  template: '<App/>'
})
