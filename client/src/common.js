exports.install = function (Vue, options) {
  Vue.prototype.convertFundStage = function (index) {
    switch (index) {
      case 0:
        return '保密'
      case 1:
        return '未融资'
      case 2:
        return '融资中'
      case 3:
        return '已融资'
    }
  }
  Vue.prototype.convertBuzType = function (index) {
    switch (index) {
      case 1:
        return '金融'
      case 2:
        return '数字货币'
      case 3:
        return '娱乐'
      case 4:
        return '供应链管理'
      case 5:
        return '法律服务'
      case 6:
        return '医疗'
      case 7:
        return '能源服务'
      case 8:
        return '公益'
      case 9:
        return '物联网'
      case 10:
        return '农业'
      case 11:
        return '社交'
      default:
        return '其它'
    }
  }
}
