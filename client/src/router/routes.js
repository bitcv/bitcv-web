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
  path: '/discover',
  component: getViews('discover/discover')
}, {
  path: '/discover/detail/:id',
  component: getViews('discover/detail')
}, {
  // 钱包
  path: '/wallet',
  component: getViews('user/wallet')
}, {
  // 体现
  path: '/wallet/withdraw/:id',
  component: getViews('user/withdraw')
}, {
  // 钱包记录
  path: '/wallet/records',
  component: getViews('user/records')
}, {
  path: '/projList',
  component: getComponent('projList/ProjList')
}, {
  path: '/candyRoom',
  redirect: '/candyRoom/candyList',
  component: getComponent('candyRoom/CandyRoom'),
  children: [{
    path: 'candyList',
    component: getComponent('candyRoom/CandyList')
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
  path: '/projDetail/:id',
  component: getComponent('projDetail/ProjDetail'),
  redirect: '/projDetail/info/:id',
  children: [{
    path: '/projDetail/info/:id',
    component: getComponent('projDetail/ProjDetailPanel')
  }, {
    path: '/projDetail/dynamic/:id',
    component: getComponent('projDetail/ProjDynamicPanel')
  }]
}, {
  path: '/signin',
  component: getComponent('sign/Signin')
}, {
  path: '/signup',
  component: getComponent('sign/Signup')
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
  path: '/newslist',
  component: getComponent('news/NewsList')
}, {
  path: '/newsdetail/:id',
  component: getComponent('news/NewsDetail')
}, {
  path: '/apply',
  component: getComponent('apply/Apply')
}]
