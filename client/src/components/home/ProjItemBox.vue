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
  height: 120px;
  display: flex;
  justify-content: space-around;
  align-items: center;
  padding: 10px 0;
  box-sizing: border-box;
  font-size: 14px;
  line-height: 20px;
  color: #9B9B9B;
  .column.index1 {
    width: 30%;
    box-sizing: border-box;
    padding-left: 10px;
    font-size: 0;
    .image-box {
      display: inline-block;
      width: 60px;
      height: 60px;
      img {
        width: 60px;
        height: 60px;
      }
    }
    .info-box {
      font-size: 14px;
      display:inline-flex;
      flex-direction: column;
      justify-content: space-between;
      vertical-align: top;
      height: 60px;
      width: calc(100% - 65px);
      margin-left: 5px;
      .title {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: no-wrap;
        color: #000;
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
