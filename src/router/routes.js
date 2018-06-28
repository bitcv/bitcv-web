function getComponent (path) {
  return () => import(`@/components/${path}.vue`).catch(e => location.reload())
}

function getViews (path) {
  return () => import(`@/views/${path}.vue`).catch(e => location.reload())
}

export default [{
  path: '/',
  component: getViews('home/home'),
  meta: {
    title: '币威--BitCV-区块链数字资产管理服务引擎'
  }
}, {
  // 发现
  path: '/discover',
  component: getViews('discover/discover'),
  meta: {
    title: '发现--BitCV-区块链数字资产管理服务引擎'
  }
}, {
  // 项目详情
  path: '/discover/detail/:id',
  component: getViews('discover/detail'),
  meta: {
    title: '项目详情--BitCV-区块链数字资产管理服务引擎'
  }
}, {
  // 币威指数
  path: '/discover/indexBcv',
  component: getViews('discover/indexBcv'),
  meta: {
    title: '币威指数--BitCV-区块链数字资产管理服务引擎'
  }
}, {
  // 钱包
  path: '/wallet',
  component: getViews('user/wallet'),
  meta: {
    requiresAuth: true,
    title: '钱包--BitCV-区块链数字资产管理服务引擎'
  }
}, {
  // 提现
  path: '/wallet/withdraw/:id/:protocol',
  component: getViews('user/withdraw'),
  meta: {
    requiresAuth: true,
    title: '提现--BitCV-区块链数字资产管理服务引擎'
  }
}, {
  // 钱包记录
  path: '/wallet/records',
  component: getViews('user/records'),
  meta: {
    requiresAuth: true,
    title: '钱包记录--BitCV-区块链数字资产管理服务引擎'
  }
}, {
  // 余币宝
  path: '/candyRoom',
  redirect: '/candyRoom/candyList',
  component: getComponent('candyRoom/CandyRoom'),
  children: [{
    path: 'candyList',
    // component: getComponent('candyRoom/CandyList')
    component: getViews('candy/list'),
    meta: {
      title: '余币宝--BitCV-区块链数字资产管理服务引擎'
    }
  }, {
    path: 'candyListAssets', // 资产明细
    component: getViews('candy/list-assets'),
    meta: {
      title: '资产明细--BitCV-区块链数字资产管理服务引擎'
    }
  }, {
    path: 'candyListProfit', // 收益明细
    component: getViews('candy/list-profit'),
    meta: {
      title: '收益明细--BitCV-区块链数字资产管理服务引擎'
    }
  }, {
    path: 'candyListPlan', // 已购计划列表
    component: getViews('candy/list-plan'),
    meta: {
      title: '已购计划列表--BitCV-区块链数字资产管理服务引擎'
    }
  }, {
    path: 'candyBuy',
    // component: getComponent('candyRoom/CandyBuy'),
    component: getViews('candy/buying'),
    meta: {
      requiresAuth: true,
      title: '购买--BitCV-区块链数字资产管理服务引擎'
    }
  }, {
    path: 'candyOrder',
    component: getViews('candy/order'),
    meta: {
      requiresAuth: true,
      title: '订单--BitCV-区块链数字资产管理服务引擎'
    }
  }, {
    path: 'candyDetails',
    component: getViews('candy/details'),
    meta: {
      requiresAuth: true,
      title: '详情--BitCV-区块链数字资产管理服务引擎'
    }
  }, {
    path: 'candyMyData',
    component: getViews('candy/my-data'),
    meta: {
      requiresAuth: true,
      title: '明细--BitCV-区块链数字资产管理服务引擎'
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
    meta: {
      requiresAuth: true,
      title: '代发宝--BitCV-区块链数字资产管理服务引擎'
    }
  }, { // 代发账户
    path: 'account',
    component: getViews('acting/account'),
    meta: {
      requiresAuth: true,
      title: '代发账户--BitCV-区块链数字资产管理服务引擎'
    }
  }, { // 发放记录
    path: 'record',
    component: getViews('acting/record'),
    meta: {
      requiresAuth: true,
      title: '发送记录--BitCV-区块链数字资产管理服务引擎'
    }
  }, { // 发放记录-详情
    path: 'record/detail/:taskId',
    component: getViews('acting/record-detail'),
    meta: {
      requiresAuth: true,
      title: '记录详情--BitCV-区块链数字资产管理服务引擎'
    }
  }, { // 帮助说明
    path: 'explain',
    component: getViews('acting/explain'),
    meta: {
      requiresAuth: true,
      title: '帮助说明--BitCV-区块链数字资产管理服务引擎'
    }
  }]
}, {
  // 创建项目
  path: '/apply',
  component: getComponent('apply/Apply'),
  meta: {
    requiresAuth: true,
    title: '新建项目--BitCV-区块链数字资产管理服务引擎'
  }
}, {
  // 注册
  path: '/signup',
  component: getComponent('sign/Signup'),
  meta: {
    title: '注册--BitCV-区块链数字资产管理服务引擎'
  }
}, {
  // 登录
  path: '/signin',
  component: getComponent('sign/Signin'),
  meta: {
    title: '登录--BitCV-区块链数字资产管理服务引擎'
  }
}, {
  path: '/share',
  component: getComponent('share/Share'),
  meta: {
    title: '分享--BitCV-区块链数字资产管理服务引擎'
  }
}, {
  path: '/protocol',
  component: getComponent('sign/Protocol'),
  meta: {
    title: '协议--BitCV-区块链数字资产管理服务引擎'
  }
}, {
  path: '/findpwd',
  component: getComponent('sign/FindPwd'),
  meta: {
    title: '找回密码--BitCV-区块链数字资产管理服务引擎'
  }
}, {
  // 404
  path: '*',
  redirect: '/'
}]
