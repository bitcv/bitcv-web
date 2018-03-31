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
import PerList from '@/components/person/PerList'
import InstituList from '@/components/institution/InstituList'
import ExchangeList from '@/components/exchange/ExchanList'
import Editor from '@/components/editor/EditorList'
import User from '@/components/user/UserList'
import Vcode from '@/components/vcode/vcode'
import Data from '@/components/data/dataList'
import Permission from '@/components/permission/permission'
import ProjData from '@/components/projdata/projdata'
import Finance from '@/components/finance/finance'
import Authuser from '@/components/authuser/authuser'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [{
    path: '/admin',
    component: Home,
    children: [{
      path: '/admin/project',
      meta: {
        requireAuth: true
      },
      component: ProjList
    }, {
      path: '/admin/depositBox',
      meta: {
        requireAuth: true
      },
      component: DepositBox
    }, {
      path: '/admin/projDepositBox/:id',
      meta: {
        requireAuth: true
      },
      component: ProjDepositBox
    }, {
      path: '/admin/token',
      meta: {
        requireAuth: true
      },
      component: Token
    }, {
      path: '/admin/social',
      meta: {
        requireAuth: true
      },
      component: Social
    }, {
      path: '/admin/media',
      meta: {
        requireAuth: true
      },
      component: Media
    }, {
      path: '/admin/setting',
      meta: {
        requireAuth: true
      },
      component: Setting
    }, {
      path: '/admin/addProject',
      meta: {
        requireAuth: true
      },
      component: AddProject
    }, {
      path: '/admin/editProject/:id',
      meta: {
        requireAuth: true
      },
      component: EditProject
    }, {
      path: '/admin/mediareport',
      meta: {
        requireAuth: true
      },
      component: MediaReport
    }, {
      path: '/admin/perlist',
      meta: {
        requireAuth: true
      },
      component: PerList
    }, {
      path: '/admin/institution',
      meta: {
        requireAuth: true
      },
      component: InstituList
    }, {
      path: '/admin/exchange',
      meta: {
        requireAuth: true
      },
      component: ExchangeList
    }, {
      path: '/admin/finance',
      meta: {
        requireAuth: true
      },
      component: Finance
    }, {
      path: '/admin/authuser',
      meta: {
        requireAuth: true
      },
      component: Authuser
    }, {
      path: '/admin/editor',
      meta: {
        requireAuth: true
      },
      component: Editor
    }, {
      path: '/admin/user',
      meta: {
        requireAuth: true
      },
      component: User
    }, {
      path: '/admin/vcode',
      meta: {
        requireAuth: true
      },
      component: Vcode
    }, {
      path: '/admin/data',
      meta: {
        requireAuth: true
      },
      component: Data
    }, {
      path: '/admin/permission',
      meta: {
        requireAuth: true
      },
      component: Permission
    }, {
      path: '/admin/projdata',
      meta: {
        requireAuth: true
      },
      component: ProjData
    }]
  }, {
    path: '/admin/signin',
    component: Signin
  }]
})
