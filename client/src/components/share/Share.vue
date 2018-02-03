<template>
  <div class="share">
    <img src="/static/img/Group 3@2x.png" alt="" class="share-img">
    <div class="share-content">
      <div class="logo"><img :src="projDetail.logoUrl" alt=""></div>
      <div class="share-content-container">
        <div class="share-content-item">
          <span class="title">项目名称：</span>
          <span class="content">{{ projDetail.nameCn }}</span>
        </div>
        <div class="share-content-item">
          <span class="title">代币符号：</span>
          <span class="content">{{ projDetail.tokenSymbol }}</span>
        </div>
        <!--<div class="share-content-item">-->
          <!--<span class="title">所属行业：</span>-->
          <!--<span class="content">数字资管平台</span>-->
        <!--</div>-->
        <div class="share-content-item">
          <span class="title">项目介绍：</span>
          <span class="multi-content">{{ projDetail.abstract }}</span>
        </div>
      </div>
      <div class="more-info">（登陆<a class="link" href="lianbi.io">lianbi.io</a>获取更多项目详细信息）</div>
    </div>
    <img src="/static/img/Group 5@2x.png" alt="" class="share-img">
  </div>
</template>

<script>
export default {
  props: {
    projDetail: Object
  },
  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
.share {
  margin: -40px 0 -76px;
  .share-content {
    padding: 0 29px;
    .logo {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 17px auto;
      width: 50px;
      height: 50px;
      background-color: black;
      img {
        max-width: 100%;
        max-height: 100%;
      }
    }
    .share-content-container {
      margin-top: 15px;
    }
    .share-content-item {
      display: flex;
      margin-top: 3px;
      line-height: 16px;
      &:nth-child(1) {
        margin-top: 0;
      }
      .title {
        flex-shrink: 0;
        font-size: 8px;
        font-weight: 550;
        color: #F5A623;
      }
      .content {
        color: #4A4A4A;
        font-size: 9px;
        font-weight: 550;
      }
      .multi-content {
        color: #4A4A4A;
        font-size: 9px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 5;
      }
    }
    .more-info {
      font-size: 7px;
      margin: 21px 0 18px;
      text-align: center;
      .link {
        color: #F5A623;
      }
    }
  }
  .share-img {
    width: 100%;
  }
}
</style>
