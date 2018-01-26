import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/home/Home'
import ProjList from '@/components/projList/ProjList'
import ProjDetail from '@/components/projDetail/ProjDetail'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/projList',
      name: 'projList',
      component: ProjList
    },
    {
      path: '/projDetail',
      name: 'projDetail',
      component: ProjDetail
    },
  ]
})
