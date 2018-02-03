<template>
  <div class="proj-item-box">
    <div class="column index1">
      <div class="image-box">
        <img :src="projData.logoUrl" alt="">
      </div>
      <div class="info-box">
        <span class="title">{{ projData.nameCn }}</span>
        <span class="content">{{ projData.nameEn }}</span>
      </div>
    </div>
    <div class="column flex index2 mobile-hide">
      <span class="title">行业</span>
      <span class="content">{{ buzType }}</span>
    </div>
    <div class="column flex index2 mobile-hide">
      <span class="title">代币符号</span>
      <span class="content">{{ projData.tokenSymbol }}</span>
    </div>
    <div class="column flex index4 mobile-hide">
      <span class="title">融资状态</span>
      <span class="content">{{ fundStage }}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    projData: Object
  },
  data () {
    return {}
  },
  computed: {
    fundStage () {
      switch (this.projData.fundStage) {
        case 0:
          return '保密'
        case 1:
          return '未融资'
        case 2:
          return '融资中'
        case 3:
          return '已融资'
      }
    },
    buzType () {
      switch (this.projData.buzType) {
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
}
</script>

<style lang="scss" scoped>
.proj-item-box {
  width: 100%;
  height: 100px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 0;
  box-sizing: border-box;
  font-size: 14px;
  line-height: 20px;
  color: #9B9B9B;
  .column.index1 {
    display: flex;
    justify-content: start;
    box-sizing: border-box;
    overflow: hidden;
    .image-box {
      flex: 0 0 auto;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 60px;
      height: 60px;
      img {
        max-width: 100%;
        max-height: 100%;
      }
    }
    .info-box {
      flex: 1 1 auto;
      font-size: 14px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 60px;
      margin-left: 5px;
      .title {
        color: #000;
        font-weight: bold;
      }
    }
  }
  .column.flex {
    width: 20%;
    height: 60px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    &.index4>.content {
      font-size: 16px;
      line-height: 22px;
      color: #F5A623;
    }
    &.index3>.content {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 24px;
      padding: 0 5px;
      background-color: #4A4A4A;
      color: #FFCF81;
      border-radius: 2px;
    }
  }
}
</style>
