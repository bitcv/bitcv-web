<template>
  <div class="proj-item-box">
    <div class="column index1">
      <div class="image-box">
        <img src="/static/img/logo.png" alt="">
      </div>
    </div>
    <div class="column index2">
      <span class="title">光链传媒</span>
      <span class="content">企业服务>其它服务</span>
    </div>
    <div class="column index3">
      <span class="title">代币名称</span>
      <span class="content">STO</span>
    </div>
    <div class="column index4">
      <span class="title">当前代币价格</span>
      <span class="content">$0.09</span>
    </div>
    <div class="column index5">
      <span class="title">融资状态</span>
      <span class="content">未透露</span>
    </div>
  </div>
</template>

<script>
export default {
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
  .column {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    font-size: 14px;
    line-height: 20px;
    color: #9B9B9B;
    .image-box {
      width: 60px;
      height: 60px;
      img {
        width: 60px;
        height: 60px;
      }
    }
    &.index2>.title {
      color: #000;
    }
    &.index3>.content {
      font-size: 16px;
      line-height: 22px;
      color: #F5A623;
    }
    &.index4>.content {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 79px;
      height: 24px;
      background-color: #4A4A4A;
      color: #FFCF81;
      border-radius: 2px;
    }
  }
}
</style>
