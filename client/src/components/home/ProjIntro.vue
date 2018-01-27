<template>
  <div class="proj-intro">
    <div class="info-box">
      <div class="image-box">
        <img :src="projData.logoUrl" alt="">
      </div>
      <div class="content-box">
        <div class="title-box">
          <span class="name">{{ projData.tokenSymbol }}</span>
          <span class="text">天使轮</span>
        </div>
        <div class="icon-text">
          <img class="icon" src="/static/img/tag-2x.png" alt="">
          <span class="text">金融</span>
        </div>
        <div class="icon-text">
          <img class="icon" src="/static/img/location-2x.png" alt="">
          <span class="text">上海</span>
        </div>
      </div>
    </div>
    <div class="text-box">
      <span class="intro">{{ projData.title }}</span>
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
.proj-intro {
  box-sizing: border-box;
  width: 33%;
  height: 135px;
  padding: 23px 29px 8px;
  .info-box {
    width: 100%;
    height: 60px;
    font-size: 0;
    .image-box {
      display: inline-block;
      width: 60px;
      height: 60px;
      margin-left: 4px;
      img {
        width: 100%;
        height: 100%;
      }
    }
    .content-box {
      display: inline-block;
      height: 100%;
      margin-left: 12px;
      .title-box {
        font-size: 14px;
        line-height: 20px;
        .name {
          color: #4A4A4A;
        }
        .text {
          padding-left: 12px;
          color: #FFCF81;
        }
      }
      .icon-text {
        display: inline-block;
        margin-right: 14px;
        margin-top: 23px;
        .icon {
          width: 12px;
          height: 12px;
        }
        .text {
          padding-left: 5px;
          font-size:12px;
          color:rgba(155,155,155,1);
          line-height:17px;
        }
      }
    }
  }
  .text-box {
    width: 216px;
    height: 34px;
    font-size: 12px;
    line-height: 17px;
    margin-top: 10px;
    color: #4A4A4A;
    overflow: hidden;
  }
}
</style>
