import Vue from 'vue'
import Router from 'vue-router'

import ProjList from '@/components/projList/ProjList.vue'
import AddProject from '@/components/projList/addProject/AddProject.vue'
import Basic from '@/components/projList/addProject/Basic.vue'
import Team from '@/components/projList/addProject/Team.vue'
import Event from '@/components/projList/addProject/Event.vue'
import Partner from '@/components/projList/addProject/Partner.vue'
import Media from '@/components/projList/addProject/Media.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      component: ProjList
    },
    {
      path: '/addProject',
      component: AddProject,
      children: [
        {
          path: 'basic',
          component: Basic
        },
        {
          path: 'team',
          component: Team
        },
        {
          path: 'event',
          component: Event
        },
        {
          path: 'partner',
          component: Partner
        },
        {
          path: 'media',
          component: Media
        },
      ]
    }
  ]
})
