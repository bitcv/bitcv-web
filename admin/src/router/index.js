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
    }]
  }, {
    path: '/signin',
    component: Signin
  }]
})
