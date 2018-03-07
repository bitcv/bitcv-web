import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'

Vue.use(VueRouter)

export default new VueRouter({
  routes,
  base: '/admin/',
  mode: 'history',
  linkActiveClass: 'active'
})
