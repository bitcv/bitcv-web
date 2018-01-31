import Vue from 'vue'
import Router from 'vue-router'

import ProjList from '@/components/projList/ProjList.vue'
import AddProject from '@/components/projList/addProject/AddProject.vue'

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
    }
  ]
})
