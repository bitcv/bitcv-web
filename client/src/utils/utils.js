// 字符串掩码处理
export const maskStr = (str, num, separator = '...') => {
  return str.substr(0, num) + separator + str.substr(-num)
}

// 获取融资状态
export const getFundStage = (v, lang) => {
  var dist = {
    cn: ['保密', '未融资', '融资中', '已融资'],
    en: ['Confidentiality', 'Unfunded', 'Financing', 'Financed']
  }
  return dist[lang][v]
}

// 获取行业类型
export const getBuzType = (v, lang) => {
  var dist = {
    cn: ['金融', '数字货币', '娱乐', '供应链管理', '法律服务', '医疗', '能源服务', '公益', '物联网', '农业', '社交'] || '其他',
    en: ['Financial', 'Digital currency', 'Entertainment', 'Supply Chain Management', 'Legal service', 'Medical', 'Energy service', 'Public welfare', 'Internet of Things', 'Agriculture', 'Social'] || 'Other'
  }
  return dist[lang][v]
}

// 获取订单状态
export const getOrderStatus = v => {
  return ['待支付', '已完成', '已取消', '已过期'][v] || '未知状态'
}

// 时间转换
export const getDateDiff = dateTimeStamp => {
  const minute = 1000 * 60
  const hour = minute * 60
  const day = hour * 24
  // const halfMonth = day * 15
  const month = day * 30

  const diffValue = Date.now() - dateTimeStamp

  let result = ''

  if (diffValue < 0) return result

  const monthC = diffValue / month
  const weekC = diffValue / (7 * day)
  const dayC = diffValue / day
  const hourC = diffValue / hour
  const minC = diffValue / minute

  if (monthC >= 1) {
    result = `${parseInt(monthC)}月前`
  } else if (weekC >= 1) {
    result = `${parseInt(weekC)}周前`
  } else if (dayC >= 1) {
    result = `${parseInt(dayC)}天前`
  } else if (hourC >= 1) {
    result = `${parseInt(hourC)}小时前`
  } else if (minC >= 1) {
    result = `${parseInt(minC)}分钟前`
  } else {
    result = '刚刚'
  }

  return result
}
export function formatDate (date, fmt) {
  if (/(y+)/.test(fmt)) {
    fmt = fmt.replace(RegExp.$1, (date.getFullYear() + '').substr(4 - RegExp.$1.length))
  }
  let o = {
    'M+': date.getMonth() + 1,
    'd+': date.getDate(),
    'h+': date.getHours(),
    'm+': date.getMinutes(),
    's+': date.getSeconds()
  }
  for (let k in o) {
    if (new RegExp(`(${k})`).test(fmt)) {
      let str = o[k] + ''
      fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? str : padLeftZero(str))
    }
  }
  return fmt
}
function padLeftZero (str) {
  return ('00' + str).substr(str.length)
}
