import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/home/Home'
import ProjList from '@/components/projList/ProjList'
import ProjDetail from '@/components/projDetail/ProjDetail'
import Signin from '@/components/sign/Signin'
import Signup from '@/components/sign/Signup'

Vue.use(Router)

export default new Router({
  mode: 'history',
  scrollBehavior (to, from, savedPosition) {
    if (to.hash) {
      return {
        selector: to.hash
      }
    }
  },
  routes: [
    {
      path: '/',
      component: Home
    },
    {
      path: '/projList',
      component: ProjList
    },
    {
      path: '/projDetail/:id',
      component: ProjDetail
    },
    {
      path: '/signin',
      component: Signin
    },
    {
      path: '/signup',
      component: Signup
    }
  ]
})
