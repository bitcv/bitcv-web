<template>
  <div class="proj-intro">
      <div class="info-box">
        <div class="image-box">
          <img :src="projData.logoUrl" alt="">
        </div>
        <div class="content-box">
          <div class="title-box">
            <span class="name">{{ projData.tokenSymbol }}</span>
            <!--<span class="text">天使轮</span>-->
          </div>
          <div class="tag-box">
<!--            <div class="icon-text">-->
              <!--<img class="icon" src="/static/img/tag-2x.png" alt="">-->
              <!--<span class="text">金融</span>-->
            <!--</div>-->
            <!--<div class="icon-text">-->
              <!--<img class="icon" src="/static/img/location-2x.png" alt="">-->
              <!--<span class="text">上海</span>-->
            <!--</div>-->
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
  flex: 1 1 auto;
  box-sizing: border-box;
  margin: 20px 20px 0;
  width: 216px;
  height: 135px;
  .info-box {
    display: flex;
    width: 100%;
    height: 60px;
    font-size: 0;
    .image-box {
      flex-shrink: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 60px;
      height: 60px;
      margin-left: 4px;
      background-color: black;
      img {
        max-width: 100%;
        max-height: 100%;
      }
    }
    .content-box {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      width: auto;
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
      .tag-box {
        overflow: hidden;
        .icon-text {
          height: 16px;
          line-height: 16px;
          float: left;
          margin-right: 14px;
          .icon {
            float: left;
            margin-top: 2px;
            width: 12px;
            height: 12px;
          }
          .text {
            margin-left: 5px;
            font-size:12px;
            color:rgb(155,155,155);
          }
        }
      }
    }
  }
  .text-box {
    margin-top: 10px;
    height: 34px;
    font-size: 12px;
    line-height: 17px;
    color: #4A4A4A;
    overflow: hidden;
  }
}
</style>
