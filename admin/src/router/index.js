import Vue from 'vue'
import Router from 'vue-router'

import ProjList from '@/components/projList/ProjList'
import AddProject from '@/components/projList/addProject/AddProject'
import EditProject from '@/components/projList/editProject/EditProject'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      component: ProjList
    },
    {
      path: '/addProject',
      component: AddProject
    },
    {
      path: '/editProject/:id',
      component: EditProject
    }
  ]
})
