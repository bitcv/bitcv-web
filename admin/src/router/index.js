import Vue from 'vue'
import Router from 'vue-router'

import ProjList from '@/components/projList/ProjList'
import Social from '@/components/social/Social'
import Media from '@/components/media/Media'
import AddProject from '@/components/projList/addProject/AddProject'
import EditProject from '@/components/projList/editProject/EditProject'
import Signin from '@/components/signin/Signin'
import Home from '@/components/home/Home'

Vue.use(Router)

export default new Router({
  routes: [{
    path: '/',
    redirect: '/project',
    component: Home,
    children: [{
      path: '/project',
      component: ProjList
    }, {
      path: '/social',
      component: Social
    }, {
      path: '/media',
      component: Media
    }, {
      path: '/addProject',
      component: AddProject
    }, {
      path: '/editProject/:id',
      component: EditProject
    }]
  }, {
    path: '/signin',
    component: Signin
  }]
})
