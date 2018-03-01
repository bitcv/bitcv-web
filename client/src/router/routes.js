function getComponent (path) {
  return () => import(`@/components/${path}.vue`)
}

function getViews (path) {
  return () => import(`@/views/${path}.vue`)
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
  component: getViews('user/wallet')
}, {
  // 提现
  path: '/wallet/withdraw/:id',
  component: getViews('user/withdraw')
}, {
  // 钱包记录
  path: '/wallet/records',
  component: getViews('user/records')
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
    path: 'candyBuy',
    component: getComponent('candyRoom/CandyBuy'),
    props: true
  }, {
    path: 'candyOrder',
    component: getComponent('candyRoom/CandyOrder')
  }, {
    path: 'candyOrderDetail/:id',
    component: getComponent('candyRoom/CandyOrderDetail')
  }, {
    path: 'candyOrderConfirm/:id',
    component: getComponent('candyRoom/CandyOrderConfirm')
  }, {
    path: 'myCandyOrder',
    component: getComponent('candyRoom/MyCandyOrder')
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
}]
