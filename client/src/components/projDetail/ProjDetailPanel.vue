<template>
  <div class="proj-detail-panel">
    <div class="header">
      <span :class="{active: activeIndex === 1}" @click="activeIndex=1">
        <router-link to="#Info">项目信息</router-link>
      </span>
      <span :class="{active: activeIndex === 2}" @click="activeIndex=2">
        <router-link to="#Team">
          团队信息
        </router-link>
      </span>
      <span :class="{active: activeIndex === 3}" @click="activeIndex=3">
        <router-link to="#Event">
          项目发展
        </router-link>
      </span>
      <span :class="{active: activeIndex === 4}" @click="activeIndex=4">
        <router-link to="#Partner">
          合作伙伴
        </router-link>
      </span>
      <span :class="{active: activeIndex === 5}" @click="activeIndex=5">
        <router-link to="#Media">
          媒体报道
        </router-link>
      </span>
    </div>
    <div class="content">
      <div class="info-area" id="Info">
        <h3 class="panel-title">项目简介</h3>
        <div class="image-box" v-if="projDetail.bannerUrl">
          <img :src="projDetail.bannerUrl" alt="">
        </div>
        <div class="abstract">
          <span>{{ projDetail.abstract }}</span>
        </div>
      </div>
      <div v-if="projDetail.memberList.length" class="team-area" id="Team">
        <el-carousel :interval="5000" arrow="always" height="390px">
          <el-carousel-item class="member" v-for="(member, index) in projDetail.memberList" :key="index">
            <img class="avatar" :src="member.photoUrl" alt="">
            <span class="name">{{ member.name }}</span>
            <span class="intro">{{ member.intro }}</span>
          </el-carousel-item>
        </el-carousel>
      </div>
      <div v-if="projDetail.eventList.length" class="event-area" id="Event">
        <h3 class="panel-title">项目发展</h3>
        <proj-time-line :proj-event="projDetail.eventList"></proj-time-line>
      </div>
      <div v-if="projDetail.advisorList.length" class="advisor-area" id="Advisor">
        <h3 class="panel-title center-title">项目顾问</h3>
        <div class="advisor-box">
          <ul class="advisor-list">
            <li class="advisor-item mobile-list-item" v-for="(advisor, index) in projDetail.advisorList" :key="index">
              <img :src="advisor.photoUrl" alt="">
              <span class="name">{{ advisor.name }}</span>
              <div class="intro-hover">
                <span class="intro">{{ advisor.intro }}</span>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div v-if="projDetail.partnerList.length" class="partner-area" id="Partner">
        <h3 class="panel-title center-title">合作伙伴</h3>
        <div class="logo-box">
          <a :href="partner.homeUrl" target="_blank" v-for="(partner, index) in projDetail.partnerList" :key="index" class="img-container">
            <img :src="partner.logoUrl" alt="">
          </a>
        </div>
      </div>
      <div v-if="projDetail.reportList.length" class="media-area" id="Media">
        <h3 class="center-title panel-title">媒体报道</h3>
        <div class="item-box">
          <a class="item mobile-list-item" :href="report.linkUrl" target="_blank" v-for="(report, index) in projDetail.reportList" :key="index">
            <div class="image-area">
              <img class="log" :src="report.logoUrl" alt="">
            </div>
            <span class="content">{{ report.title }}</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ProjTimeLine from '@/components/projDetail/ProjTimeLine'
export default {
  props: {
    projDetail: Object
  },
  data () {
    return {
      activeIndex: 1
    }
  },
  components: {
    ProjTimeLine
  }
}
</script>

<style lang="scss" scoped>
.proj-detail-panel {
  width: 100%;
  .header {
    display: flex;
    justify-content: space-around;
    box-sizing: border-box;
    width: 100%;
    height: 40px;
    margin-bottom: 10px;
    font-size: 0;
    color: #000;
    background-color: #FFF;
    span {
      font-size: 14px;
      display: inline-block;
      height: 37px;
      line-height: 40px;
      border-bottom: 3px solid #FFF;
      cursor: pointer;
      &.active {
        border-bottom: 3px solid #F5A623;
      }
    }
  }
  .content {
    >div:nth-child(2n + 1) {
      background-color: #FFF;
    }
    >div:nth-child(2n) {
      background-color: #F6F6F6;
    }
    .info-area {
      padding: 20px;
      .image-box {
        margin: 20px 0;
        text-align: center;
        img {
          width: 100%;
        }
        border-bottom: 0.5px solid #979797;
      }
      .abstract {
        padding-top: 6px;
        font-size: 14px;
        line-height: 28px;
        text-align: left;
      }
    }
    .team-area {
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
          overflow: hidden;
          text-overflow: ellipsis;
          display: -webkit-box;
          -webkit-box-orient: vertical;
          -webkit-line-clamp: 5;
        }
      }
    }
    .event-area {
      width: 100%;
      box-sizing: border-box;
      padding: 40px 20px;
    }
    .advisor-area {
      padding: 20px;
      .advisor-box {
        margin: 48px 30px 0;
        .advisor-list {
          display: flex;
          justify-content: center;
          flex-wrap: wrap;
          width: 100%;
          .advisor-item {
            width: 168px;
            /*height: 222px;
            display: flex;*/
            display:inline-block;
            text-align:center;
            background-color: #FFF;
            position: relative;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 5px;
            img {
              width: 100%;
              /*height: 73px;*/
              border-radius: 50%;
            }
            .name {
              font-size: 14px;
              line-height: 30px;
              border-top: 2px solid #5AA6FF;
              color: #000;
              margin-top: 22px;
            }
            .intro-hover {
              position: absolute;
              top: 0;
              bottom: 0;
              left: 0;
              right: 0;
              display: flex;
              justify-content: center;
              align-items: center;
              font-size: 14px;
              line-height: 20px;
              color: rgba(256, 256, 256, 0);
              background-color: rgba(256, 256, 256, 0);
              &:hover {
                color: #4A4A4A;
                background-color: rgba(256, 256, 256, 0.9);
              }
            }
          }
        }
      }
    }
    .partner-area {
      padding: 40px 0;
      .logo-box {
        margin-top: 39px;
        box-sizing: border-box;
        padding: 0 30px;
        width: 100%;
        overflow: hidden;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        .img-container {
          float: left;
          display: flex;
          justify-content: center;
          align-items: center;
          margin: 30px 15px 0;
          border: 1px solid #DCDFE6;
          width: 170px;
          height: 65px;
          img {
            max-width: 100%;
            max-height: 100%;
          }
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
        flex-wrap: wrap;
        margin-top: 48px;
        .item {
          box-sizing: border-box;
          width: 168px;
          height: 222px;
          border-top: 9px solid #5AA6FF;
          box-shadow: 0 0 9px #C0C0C0;
          margin: 5px;
          &:nth-child(1) {
            border-color: #3A323D;
          }
          &:nth-child(2) {
            border-color: #5AA6FF;
          }
          &:nth-child(3) {
            border-color: #FFE651;
          }
          &:nth-child(4) {
            border-color: #FF6262;
          }
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
}
@media screen and (max-width: 500px) {
  .mobile-list-item {
    width: calc(50% - 10px) !important;
  }
}
</style>
