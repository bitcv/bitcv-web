<template>
  <div class="proj-detail-panel">
    <div class="header">
      <span class="active">项目信息</span>
      <span>团队信息</span>
      <span>项目发展</span>
      <span>合作伙伴</span>
      <span>媒体报道</span>
    </div>
    <div class="content">
      <div class="info-area">
        <h3 class="panel-title">项目简介</h3>
        <div>
          <img :src="projDetail.bannerUrl" alt="">
        </div>
        <div class="abstract">
          <span>{{ projDetail.abstract }}</span>
        </div>
      </div>
      <div class="team-area">
        <el-carousel :interval="5000" arrow="always" height="378px">
          <el-carousel-item class="member" v-for="(member, index) in projDetail.memberList" :key="index">
            <img class="avatar" :src="member.photoUrl" alt="">
            <span class="name">{{ member.name }}</span>
            <span class="intro">{{ member.intro }}</span>
          </el-carousel-item>
        </el-carousel>
      </div>
      <div class="event-area">
        hello
      </div>
      <div class="partner-area">
        <h3 class="panel-title center-title">合作伙伴</h3>
        <div class="logo-box">
          <a :href="partner.webUrl" target="_blank" v-for="(partner, index) in projDetail.partnerList" :key="index">
            <img :src="partner.logoUrl" alt="">
          </a>
        </div>
      </div>
      <div class="media-area">
        <h3 class="center-title panel-title">媒体报道</h3>
        <div class="item-box">
          <div v-for="(media, index) in projDetail.mediaList" :key="index" class="item">
            <div class="image-area">
              <img class="log" :src="media.imageUrl" alt="">
            </div>
            <span class="content">{{ media.title }}</span>
          </div>
        </div>
      </div>
    </div>
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
.proj-detail-panel {
  width: 100%;
  >div {
    background-color: #FFF;
  }
  .header {
    box-sizing: border-box;
    width: 100%;
    height: 40px;
    margin-bottom: 10px;
    font-size: 0;
    color: #000;
    padding-left: 23px;
    span {
      font-size: 14px;
      display: inline-block;
      height: 37px;
      line-height: 40px;
      margin-right: 98px;
        border-bottom: 3px solid #FFF;
      &.active {
        border-bottom: 3px solid #F5A623;
      }
    }
  }
  .info-area {
    padding: 20px;
    div {
      margin: 20px 0;
      text-align: center;
      img {
        width: 632px;
        height: 220px;
      }
    }
    .abstract {
      border-top: 0.5px solid #979797;
      padding-top: 6px;
      font-size: 14px;
      line-height: 28px;
      text-align: left;
    }
  }
  .team-area {
    background-color: #F6F6F6;
    .member {
      box-sizing: border-box;
      padding: 50px;
      text-align: center;
      >* {
        display: block;
        margin: 0 auto;
      }
      img {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        margin-bottom: 12px;
      }
      .name {
        margin-bottom: 40px;
      }
      .intro {
        font-size: 16px;
        line-height: 28px;
        color: #000;
        text-align: left;
      }
    }
  }
  .partner-area {
    background-color: #F6F6F6;
    padding: 40px 0;
    .logo-box {
      margin-top: 39px;
      box-sizing: border-box;
      padding: 0 20px;
      width: 100%;
      display: flex;
      justify-content: space-around;
      align-content: space-around;
      flex-wrap: wrap;
      img {
        width: 170px;
        height: 65px;
        margin: 10px 35px;
      }
    }
  }
  .media-area {
    padding: 40px;
    .item-box {
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 48px;
      .item {
        box-sizing: border-box;
        width: 168px;
        height: 222px;
        border-top: 9px solid #5AA6FF;
        box-shadow: 0 0 9px #C0C0C0;
        margin: 0 5px;
        .image-area {
          width: 100%;
          height: 115px;
          display: flex;
          justify-content: center;
          align-items: center;
          img {
            display: block;
            margin: 0 auto;
            width: 67px;
            height: 67px;
          }
        }
        .content {
          display: block;
          box-sizing: border-box;
          width: 100%;
          padding: 0 5px;
          font-size: 12px;
          line-height: 18px;
          color: #9B9B9B;
          text-align: center;
        }
      }
    }
  }
}
</style>
