import Vue from 'vue'
import Router from 'vue-router'

import ProjList from '@/components/projList/ProjList'
import Social from '@/components/social/Social'
import Media from '@/components/media/Media'
import AddProject from '@/components/projList/addProject/AddProject'
import EditProject from '@/components/projList/editProject/EditProject'
import Signin from '@/components/signin/Signin'
import Home from '@/components/home/Home'
import Setting from '@/components/setting/Setting'
import DepositBox from '@/components/depositBox/DepositBox'
import ProjDepositBox from '@/components/depositBox/projDepositBox'
import MediaReport from '@/components/media/MediaReport'
import Token from '@/components/token/Token'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [{
    path: '/admin',
    component: Home,
    children: [{
      path: '/admin/project',
      component: ProjList
    }, {
      path: '/admin/depositBox',
      component: DepositBox
    }, {
      path: '/admin/projDepositBox/:id',
      component: ProjDepositBox
    }, {
      path: '/admin/token',
      component: Token
    }, {
      path: '/admin/social',
      component: Social
    }, {
      path: '/admin/media',
      component: Media
    }, {
      path: '/admin/setting',
      component: Setting
    }, {
      path: '/admin/addProject',
      component: AddProject
    }, {
      path: '/admin/editProject/:id',
      component: EditProject
    }, {
      path: '/admin/mediareport',
      component: MediaReport
    }]
  }, {
    path: '/signin',
    component: Signin
  }]
})
