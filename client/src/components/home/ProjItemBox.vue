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
    <div class="column flex index2">
      <span class="title">代币名称</span>
      <span class="content">{{ projData.tokenSymbol }}</span>
    </div>
    <div class="column flex index3">
      <span class="title">当前代币价格</span>
      <span class="content">{{ projData.tokenPrice }}</span>
    </div>
    <div class="column flex index4">
      <span class="title">融资状态</span>
      <span class="content">未透露</span>
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
    width: 40%;
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
    &.index2>.content {
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
