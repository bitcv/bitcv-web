function getComponent (path) {
  return () => import(`@/components/${path}.vue`).catch(e => location.reload())
}

function getViews (path) {
  return () => import(`@/views/${path}.vue`).catch(e => location.reload())
}

export default [{
  path: '/',
  component: getViews('home/home')
}, {
  // 发现
  path: '/discover',
  component: getViews('discover/discover')
}, {
  // 项目详情
  path: '/discover/detail/:id',
  component: getViews('discover/detail')
}, {
  // 钱包
  path: '/wallet',
  component: getViews('user/wallet'),
  meta: {
    requiresAuth: true
  }
}, {
  // 提现
  path: '/wallet/withdraw/:id/:protocol',
  component: getViews('user/withdraw'),
  meta: {
    requiresAuth: true
  }
}, {
  // 钱包记录
  path: '/wallet/records',
  component: getViews('user/records'),
  meta: {
    requiresAuth: true
  }
}, {
  // 余币宝
  path: '/candyRoom',
  redirect: '/candyRoom/candyList',
  component: getComponent('candyRoom/CandyRoom'),
  children: [{
    path: 'candyList',
    // component: getComponent('candyRoom/CandyList')
    component: getViews('candy/list')
  }, {
    path: 'candyListAssets', // 资产明细
    component: getViews('candy/list-assets')
  }, {
    path: 'candyListProfit', // 收益明细
    component: getViews('candy/list-profit')
  }, {
    path: 'candyListPlan', // 已购计划列表
    component: getViews('candy/list-plan')
  }, {
    path: 'candyBuy',
    // component: getComponent('candyRoom/CandyBuy'),
    component: getViews('candy/buying'),
    meta: {
      requiresAuth: true
    }
  }, {
    path: 'candyOrder',
    component: getViews('candy/order'),
    meta: {
      requiresAuth: true
    }
  }, {
    path: 'candyDetails',
    component: getViews('candy/details'),
    meta: {
      requiresAuth: true
    }
  }, {
    path: 'candyMyData',
    component: getViews('candy/my-data'),
    meta: {
      requiresAuth: true
    }
  }
  // {
  //   path: 'candyOrder',
  //   component: getComponent('candyRoom/CandyOrder'),
  //   meta: {
  //     requiresAuth: true
  //   }
  // }, {
  //   path: 'candyOrderDetail/:id',
  //   component: getComponent('candyRoom/CandyOrderDetail'),
  //   meta: {
  //     requiresAuth: true
  //   }
  // }, {
  //   path: 'candyOrderConfirm/:id',
  //   component: getComponent('candyRoom/CandyOrderConfirm'),
  //   meta: {
  //     requiresAuth: true
  //   }
  // }, {
    // path: 'myCandyOrder',
    // component: getComponent('candyRoom/MyCandyOrder'),
    // meta: {
    //   requiresAuth: true
    // }
  // }
  ]
}, { // 代发宝
  path: '/acting',
  component: getViews('acting/acting'),
  meta: {requiresAuth: true},
  redirect: '/acting/home',
  children: [{ // 首页
    path: 'home',
    component: getViews('acting/home'),
    meta: {requiresAuth: true}
  }, { // 代发账户
    path: 'account',
    component: getViews('acting/account'),
    meta: {requiresAuth: true}
  }, { // 发放记录
    path: 'record',
    component: getViews('acting/record'),
    meta: {requiresAuth: true}
  }, { // 发放记录-详情
    path: 'record/detail',
    component: getViews('acting/record-detail'),
    meta: {requiresAuth: true}
  }, { // 帮助说明
    path: 'explain',
    component: getViews('acting/explain'),
    meta: {requiresAuth: true}
  }]
}, {
  // 创建项目
  path: '/apply',
  component: getComponent('apply/Apply'),
  meta: {
    requiresAuth: true
  }
}, {
  // 注册
  path: '/signup',
  component: getComponent('sign/Signup')
}, {
  // 登录
  path: '/signin',
  component: getComponent('sign/Signin')
}, {
  path: '/share',
  component: getComponent('share/Share')
}, {
  path: '/protocol',
  component: getComponent('sign/Protocol')
}, {
  path: '/findpwd',
  component: getComponent('sign/FindPwd')
}, {
  path: '/resetpwd/:mobile',
  component: getComponent('sign/ResetPwd')
}, {
  // 404
  path: '*',
  redirect: '/'
}]
