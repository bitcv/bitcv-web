import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/home/Home'
import ProjList from '@/components/projList/ProjList'
import ProjDetail from '@/components/projDetail/ProjDetail'
import Signin from '@/components/sign/Signin'
import Signup from '@/components/sign/Signup'
import Share from '@/components/share/Share'

Vue.use(Router)

export default new Router({
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
    },
    {
      path: '/share',
      component: Share
    }
  ]
})
