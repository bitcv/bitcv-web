import axios from 'axios'

const handleErr = (msg) => {
  alert(msg)
}

export const reLogin = ({commit}) => {
  commit('cleanUserInfo')
}

const resolveResponse = ({errcode, data, errmsg = ''}) => {
  switch (errcode) {
    case 0: return data
    case 302:
      reLogin()
      return Promise.reject(errmsg)
    default:
      handleErr(errmsg)
      return Promise.reject(errmsg)
  }
}

const post = (url, params) => {
  return axios.post(url, params).then(res => resolveResponse(res.data))
}

// 获取项目列表
export const getProList = (store, params) => {
  return post('/api/getProjList', params)
}

// 获取项目详情
export const getProDetail = (store, params) => {
  return post('/api/getProjDetail', params)
}

// 获取资讯列表
export const getNewsList = (store, params) => {
  return post('/api/getNewsList', params)
}

// 获取筛选条件
export const getFilterParams = (store, params) => {
  return post('/api/getProjTagList', params)
}

// 获取英文筛选条件
export const getEnFilterParams = (store, params) => {
  return post('/api/getEnProjTagList', params)
}

// 获取TOP10
export const getTop10 = (store, params) => {
  return post('/api/getProjTopList', params)
}

// 关注|取消关注
export const updateFocus = (store, params) => {
  return post('/api/toggleFocus', params)
}

// 获取余币宝计划列表
export const getCandyList = (store, params) => {
  return post('/api/getDepositBoxList', params)
}

// 提交充值订单
export const postCandyOrder = (store, params) => {
  return post('/api/addDepositOrder', params)
}

// 获取订单详情
export const getOrderDetail = (store, params) => {
  return post('/api/getOrderDetail', params)
}

// 获取确认订单列表
export const getOrderTxRecordList = (store, params) => {
  return post('/api/getOrderTxRecordList', params)
}

// 确认记录完成
export const confirmDepositTx = (store, params) => {
  return post('/api/confirmDepositTx', params)
}

// 获取用户订单列表
export const getUserOrderList = (store, params) => {
  return post('/api/getUserOrderList', params)
}

// 取消订单
export const cancelDepositOrder = (store, params) => {
  return post('/api/cancelDepositOrder', params)
}
