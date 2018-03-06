// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import axios from 'axios'
import common from './common'
Vue.use(common)

require('es6-promise').polyfill()
Vue.prototype.$http = axios
Vue.config.productionTip = false

Vue.use(ElementUI)

//axios.interceptors.response.use(
  //response => {
    //if (response.data['errcode'] === 302) {
      //location.href = '/signin'
    //}
    //return response
  //},
  //error => {
    //if (error.response.status === 302) {
      //location.href = '/signin'
    //}
    //return Promise.reject(error)
  //}
//)

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
