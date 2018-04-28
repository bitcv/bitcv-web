// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import axios from 'axios'
import store from './store'
import router from './router'
import {sync} from 'vuex-router-sync'

import accounting from 'accounting'

import 'nprogress/nprogress.css'
import NProgress from 'nprogress'

import ElementUI from 'element-ui'
import 'swiper/dist/css/swiper.css'
import 'element-ui/lib/theme-chalk/index.css'
import common from './common'
// 多语言
import VueI18n from 'vue-i18n'
import VueClipboard from 'vue-clipboard2'

Vue.use(VueClipboard)
Vue.use(VueI18n)
const messages = {
  // 简体中文
  cn: {
    label: {
      lang: '中文',
      home: '主页',
      discover: '发现',
      ybb: '余币宝',
      dfb: '代发宝',
      login: '登录',
      registered: '注册',
      section_title: '更多的机构，遇见更多的财富',
      search_bar: '通证名称、符号、项目名称',
      search_btn: '搜索',
      create_project: '立即创建项目',
      re_project: '推荐项目',
      find_project: '发现新项目',
      pro_train: '项目直通车',
      pro_train_name: '公司名称',
      pro_train_type: '通证符号',
      pro_train_industry: '所属行业',
      pro_train_status: '融资状态',
      focus: '关注',
      view: '浏览',
      share: '分享',
      project_sum: '共',
      project_num: '项',
      detail_tag: '标签',
      project_shortname: '项目简称',
      project_s: '项目符号',
      project_home: '项目主页',
      white_paper: '白皮书',
      social: '社区',
      project_info: '项目信息',
      project_dy: '项目动态',
      project_intr: '项目简介',
      team_mem: '团队成员',
      project_advisor: '项目顾问',
      project_dev: '项目发展',
      brother: '合作伙伴',
      media: '媒体报道',
      anno: '官方公告',
      comm_anno: '社区发布',
      view_full: '查看原文',
      claim_co: '认领该公司',
      co_address: '公司联系信息',
      ybb_plan: '余币宝计划',
      lock: '锁仓期限',
      candy_project: '项目',
      return: '回报',
      million: '每万枚',
      start_amount: '起始额度',
      leave_amount: '剩余额度',
      buy: '立即抢购',
      coin_amount: '枚',
      month: '月',
      day: '天',
      login_mobile_ph: '请输入手机号',
      login_pwd_ph: '请输入密码',
      reset_pwd: '重置密码',
      hurry_login: '立即登录',
      festival: '春节期间参加活动的用户请先',
      welcome: '欢迎注册',
      sms: '短信验证码',
      get_sms: '获取短信验证码',
      pwd: '密码',
      account: '已有账号',
      new_pwd: '请输入新的密码',
      logout: '注销登录',
      assets: '我的资产',
      order: '余币宝订单',
      sum_money: '总资产',
      view_r: '查看交易记录',
      coin_type: '币种',
      price: '参考价',
      coin_num: '数量',
      coin_status: '状态',
      operation: '操作',
      later_t: '稍后提取',
      hurry_t: '立即提取',
      records: '交易记录',
      hash: '交易哈希',
      r_time: '交易时间',
      my_list: '我的余币宝订单',
      order_status: '订单状态',
      order_time: '下单时间',
      in_amount: '充值数量',
      sum_return: '总回报',
      all: '全部',
      complete: '已完成',
      to_charge: '待充值',
      no: '暂无交易',
      cancel: '已取消',
      order_finished: '订单完成',
      confirm_o: '确认订单',
      cancel_order: '取消订单',
      confirm: '确认',
      delete: '删除',
      buy_q: '购买数量',
      order_money: '订单金额',
      buy_c: '抢购额度',
      recharge_a: '您要充值的数量',
      p_in: '请输入',
      bigger: '下单数量必须大于起始额度',
      order_now: '立即下单',
      team_info: '团队信息',
      order_detail: '订单详情',
      re_address: '接收地址',
      your_address: '您的地址',
      sys_notice: '以下为系统自动检测到的交易记录，请勾选此笔订单相关的交易记录进行确认！',
      refresh_data: '刷新数据',
      co_co: '确认完成',
      start_co: '开始确认',
      my_goal: '我已向目标接收地址充值',
      set_address: '地址设置',
      buy_detail: '抢购详情',
      need: '需充值',
      pla_notice: '此地址为项目方与平台共同认可的资金存管地址，回报已入账，请放心充值',
      spending: '您的支出地址',
      submit_ord: '提交订单',
      getback: '后获得回报',
      input_address: '请输入支出地址',
      apply: '项目申请',
      apply_notice: '已经展示的项目请在项目详情页面直接认领！',
      tong_name: '通证名称',
      p_ename: '项目英文名',
      p_cname: '项目中文名',
      f_stage: '融资阶段',
      f_se: '保密',
      f_no: '未融资',
      f_ing: '融资中',
      f_f: '已融资',
      p_n: '项目名称',
      coin_sys: '代币符号',
      p_intr: '项目介绍',
      p_getmore: '获取更多项目详细信息',
      tips: '提示：本次矿工费由币威平台承担',
      shoukuan: '收款地址',
      wait: '请等待',
      sub: '提交',
      can_btn: '取消',
      no_data: '暂无数据',
      shouqi: '收起',
      jiaoyi: '交易数量',
      no_t: '暂无交易记录，请点击',
      retry: '重试',
      confirm_recharge: '请确认充值数量',
      coin_address: '请输入相应币种的钱包地址',
      ybbhuibao: '余币宝回报',
      nopeople: '暂时没人发起余币宝计划',
      bci: '币威指数',
      last_update_time: '上次更新时间',
      bci_more: '指数介绍',
      bci_intro: '指数介绍',
      bci30_ctn: '币威30指数（BCI30）成分币列表',
      bci150_ctn: '币威150指数（BCI150）成分币列表',
      bciReserve: '币威指数备选币列表'
    }
  },
  // 英文
  en: {
    label: {
      lang: 'English',
      home: 'Home',
      discover: 'Explore',
      ybb: 'EncryptoMore',
      dfb: 'AutoTransfer',
      login: 'Login',
      registered: 'Register',
      section_title: 'More Token, More Fortune',
      search_bar: 'Certificate Address、Symbol、Projects name',
      search_btn: 'Search',
      create_project: 'Create a project now',
      re_project: 'Recommended projects',
      find_project: 'New',
      pro_train: 'Projects',
      pro_train_name: 'Name',
      pro_train_type: 'Ticker',
      pro_train_industry: 'Sector',
      pro_train_status: 'Progress',
      focus: 'Follow',
      view: 'Browse',
      share: 'Share',
      project_sum: 'Altogether',
      project_num: 'Term',
      detail_tag: 'Tags',
      project_shortname: 'Project Ticker',
      project_s: 'Project symbol',
      project_home: 'Project WebSite',
      white_paper: 'White Paper',
      social: 'Community',
      project_info: 'Project Information',
      project_dy: 'Recent Update',
      project_intr: 'Project Introduction',
      team_mem: 'Team',
      project_advisor: 'Project Consultant',
      project_dev: 'Project Development',
      brother: 'Cooperative Partner',
      media: 'Media Coverage',
      anno: 'Official Announcement',
      comm_anno: 'Community Release',
      view_full: 'View Original',
      claim_co: 'Claim The Company',
      co_address: 'Company Information',
      ybb_plan: 'EncryptoMore Plan',
      lock: 'Locking Period',
      candy_project: 'Project',
      return: 'Return',
      million: 'Per Ten Thousand',
      start_amount: 'Starting Amount',
      leave_amount: 'Remaining Amount',
      buy: 'Buy',
      coin_amount: 'Token',
      month: 'Month',
      day: 'Day',
      login_mobile_ph: 'Cell Phone Number',
      login_pwd_ph: 'Password',
      reset_pwd: 'Forget Password',
      hurry_login: 'Login immediately',
      festival: 'The users who participated in the Spring Festival',
      welcome: 'Welcome to',
      sms: 'SMS Code',
      get_sms: 'Get SMS Code',
      pwd: 'Password',
      account: 'Already Registered',
      new_pwd: 'New Password',
      logout: 'Logout',
      assets: 'My Assets',
      order: 'CryptoMore Order',
      sum_money: 'Total Assets',
      view_r: 'View Transaction History',
      coin_type: 'Currency',
      price: 'Market Price',
      coin_num: 'Amount',
      coin_status: 'Status',
      operation: 'Operation',
      later_t: 'Extract Later',
      hurry_t: 'Withdraw',
      records: 'Transaction Record',
      hash: 'Trading Hash',
      r_time: 'Transaction Time',
      my_list: 'My CryptoMore Orders',
      order_status: 'Order Status',
      order_time: 'Order time',
      in_amount: 'Quantity',
      sum_return: 'Total Return',
      all: 'ALL',
      complete: 'Completed',
      to_charge: 'In Progress',
      no: 'No Transaction',
      cancel: 'Cancelled',
      order_finished: 'Order Completed',
      confirm_o: 'Confirm Order',
      cancel_order: 'Cancel Order',
      confirm: 'Confirm',
      delete: 'Delete',
      buy_q: 'Purchase Quantity',
      order_money: 'Order Amount',
      buy_c: 'Buying Credit',
      recharge_a: 'The amount you want to recharge',
      p_in: 'Please Enter',
      bigger: 'The order quantity must be greater than the start amount',
      order_now: 'Order Now',
      team_info: 'Team Information',
      order_detail: 'Order Detail',
      re_address: 'Receiving Address',
      your_address: 'Your Address',
      sys_notice: 'The following is the system automatically detects the transaction records, please check the transaction records related to this order to confirm!',
      refresh_data: 'Refresh the data',
      co_co: 'Confirm the completion',
      start_co: 'Start to confirm',
      my_goal: 'I have recharged the destination address',
      set_address: 'Address Setting',
      buy_detail: 'Locking Amount Detail',
      need: 'Need to recharge',
      pla_notice: 'This address is jointly approved by the project owner and the platform of the capital depository address, the return has been recorded, please rest assured recharge',
      spending: 'Your Sending Address',
      submit_ord: 'Submit Order',
      getback: 'Get Rewarded',
      input_address: 'Your Sending Address',
      apply: 'Apply for Project Listing',
      apply_notice: 'Please Fill the Details of your Project',
      tong_name: 'Certificate Address',
      p_ename: 'Project Name in English',
      p_cname: 'Project Name in Chinese',
      f_stage: 'Financing Stage',
      f_se: 'Undisclosed',
      f_no: 'Unfunded',
      f_ing: 'Financing',
      f_f: 'Financed',
      p_n: 'Project Name',
      coin_sys: 'Token Symbol',
      p_intr: 'Project Introduction',
      p_getmore: 'Get More Project Details',
      tips: 'Tip: the transaction fee is on us.',
      shoukuan: 'Receiving Address',
      wait: 'Please Wait',
      sub: 'Submit',
      can_btn: 'Cancel',
      no_data: 'No Data',
      shouqi: 'Collapse',
      jiaoyi: 'Number of transactions',
      no_t: 'No transaction history, please click',
      retry: 'Retry',
      confirm_recharge: 'Please confirm the amount of recharge',
      coin_address: 'Please enter the wallet address for the currency',
      ybbhuibao: 'EncryptoMore Return',
      nopeople: 'No one has initiated encryptoMore plan yet',
      bci: 'BCI Index',
      last_update_time: 'Last Update',
      bci_more: 'more',
      bci_intro: 'Introduction',
      bci30_ctn: 'BCI 30 Component',
      bci150_ctn: 'BCI 150 Component',
      bciReserve: 'Reserve List'
    }
  }
}
const i18n = new VueI18n({
  // 定义默认语言
  locale: 'cn',
  messages
})

const isNumber = (n) => {
  return !isNaN(n) && typeof n === 'number'
}

Vue.use(common)

require('es6-promise').polyfill()
Vue.use(ElementUI)

Vue.prototype.$http = axios
Vue.prototype.nprogress = NProgress
window.NProgress = NProgress

Vue.config.productionTip = false

sync(store, router)

axios.interceptors.response.use(
  response => {
    if (response.data['errcode'] === 302) {
      store.commit('cleanUserInfo')

      router.push('/')

      return Promise.reject(response)
    }
    return response
  },
  error => {
    if (error.response.status === 302) {
      store.commit('cleanUserInfo')

      router.push('/')
    }
    return Promise.reject(error)
  }
)

// accounting
Vue.prototype.accounting = accounting

// 千位分隔符
Vue.filter('currency', (val, symbol = '', precision = 2, thousand = ',', decimal = '.', format = '%s%v') => {
  let n = val - 0
  if (!isNaN(n) && isNumber(n)) {
    return accounting.formatMoney(n, symbol, precision, thousand, decimal, format)
  }
  return val
})

// 同步缓存中的用户信息
const syncUserInfo = () => store.commit('updateUserInfo')

const getToken = () => {
  syncUserInfo()

  const userInfo = store.state.userInfo

  return userInfo && Object.keys(userInfo).length > 0
}

// 登录拦截
router.beforeEach((to, from, next) => {
  NProgress.start()
  if (to.matched.some(route => route.meta && route.meta.requiresAuth)) { // 需要校验登录信息
    const hasToken = getToken()

    if (!hasToken) { // 没有token
      // 跳转到登录页
      next('/signin')
    }
  }

  next()
})

router.afterEach((to, from) => {
  NProgress.done()
})

router.beforeEach((to, from, next) => {
  /* 路由发生变化修改页面title */
  if (to.meta.title) {
    document.title = to.meta.title
  }
  next()
})

syncUserInfo()

/* eslint-disable no-new */
new Vue({
  el: '#app',
  store,
  router,
  i18n,
  components: { App },
  template: '<App/>',
  data: {
    eventHub: new Vue()
  }
})
