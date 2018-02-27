import Vue from 'vue'

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

// 获取项目列表
export const getProList = (store, params) => {
  return Vue.axios.post('/api/getProjList', params)
    .then(res => resolveResponse(res.data))
}

// 获取项目详情
export const getProDetail = (store, params) => {
  return Vue.axios.post('/api/getProjDetail', params)
    .then(res => resolveResponse(res.data))
}

// 获取资讯列表
export const getNewsList = (store, params) => {
  return Vue.axios.post('/api/getNewsList', params)
    .then(res => resolveResponse(res.data))
}

// 获取筛选条件
export const getFilterParams = (store, params) => {
  return Vue.axios.post('/api/getProjTagList', params)
    .then(res => resolveResponse(res.data))
}

// 获取TOP10
export const getTop10 = (store, params) => {
  return Vue.axios.post('/api/getProjTopList', params)
    .then(res => resolveResponse(res.data))
}

// 关注|取消关注
export const updateFocus = (store, params) => {
  return Vue.axios.post('/api/toggleFocus', params)
    .then(res => resolveResponse(res.data))
}
