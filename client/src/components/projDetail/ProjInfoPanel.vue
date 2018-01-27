<template>
  <div class="proj-info">
    <div class="info-box">
      <img :src="projDetail.logoUrl" alt="">
      <div class="text-box">
        <div class="top-row">
          <span class="name-cn">{{ projDetail.nameCn }}</span>
          <span class="name-en">({{ projDetail.nameEn }})</span>
        </div>
        <div class="bottom-row">
          <span class="text-title">代币名称：</span>
          <span class="text">{{ projDetail.tokenName }}</span>
          <span class="text-title">代币符号：</span>
          <span class="text">{{ projDetail.tokenSymbol }}</span>
        </div>
      </div>
    </div>
    <div class="tag-box">
      <span class="title">标签</span>
      <span class="tag" v-for="(tag, index) in projDetail.tagList" :key="index">{{ tag }}</span>
    </div>
    <div class="social-box">
      <span class="title">友情链接：</span>
      <a :href="partner.webUrl" v-for="(partner, index) in projDetail.partnerList" :key="index" target="_blank">
        <img :src="partner.logoUrl" alt="">
      </a>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    projDetail: Object
  },
  data () {
    return {}
  }
}
</script>

<style lang="scss" scoped>
.proj-info {
  box-sizing: border-box;
  width: 100%;
  padding: 54px 41px 0 22px;
  background-color: #FFF;
  .info-box {
    img {
      width: 80px;
      height: 80px;
    }
    .text-box {
      margin-left: 24px;
      height: 80px;
      vertical-align: top;
      display: inline-flex;
      justify-content: space-between;
      flex-direction: column;
      .top-row {
        font-size: 16px;
        line-height: 22px;
        color: #4A4A4A;
        .name-en {
          font-size: 14px;
          line-height: 20px;
          margin-left: 12px;
          color: #9B9B9B;
        }
      }
      .bottom-row {
        font-size: 14px;
        line-height: 20px;
        color: #000;
        .text {
          color: #F5A623;
          margin-right: 41px;
        }
      }
    }
  }
  .tag-box {
    padding: 29px 0 20px;
    font-size: 14px;
    line-height: 20px;
    border-bottom: 0.5px solid #979797;
    .title {
      color: #000;
      margin-right: 4px;
    }
    .tag {
      color: #9B9B9B;
      margin-right: 10px;
    }
  }
  .social-box {
    height: 46px;
    display: flex;
    align-items: center;
    font-size: 14px;
    line-height: 20px;
    color: #000;
    img {
      width: 26px;
      height: 26px;
      margin: 0 5px;
    }
  }
}
</style>
