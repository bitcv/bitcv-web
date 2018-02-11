<template>
  <div class="proj-detail-panel">
    <div class="header">
      <span :class="{active: activeIndex === 1}" @click="activeIndex=1">
        <router-link to="#Media">媒体报道</router-link>
      </span>
      <span :class="{active: activeIndex === 2}" @click="activeIndex=2">
        <router-link to="#Public">
          官方公告
        </router-link>
      </span>
      <span :class="{active: activeIndex === 3}" @click="activeIndex=3">
        <router-link to="#Group">
          社群发布
        </router-link>
      </span>
    </div>
    <div class="content">
      <div class="media-area" id="Media">
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
      <div class="group-area" id="Public">
        <h3 class="center-title panel-title">官方公告</h3>
        <ul class="notice-list">
          <li v-for="(notice, index) in projDetail.publicList" :key="index">
            <a :href="notice.referUrl" target="_blank">
              <div class="title">
                <span class="dot"> </span>
                <span>{{ notice.title }}</span>
              </div>
              <span class="date">{{ notice.createdAt }}</span>
            </a>
          </li>
        </ul>
      </div>
      <div class = "public-area" id="Group">
        <h3 class="center-title">社群发布</h3>
        <ul class="social-list">
          <li v-for="(social, index) in projDetail.dynamicList" :key="index">
            <img :src="social.logoUrl" alt="">
            <div class="content-box">
              <div class="title-box">
                <span class="content-title">{{ social.officialName }}</span>
                <a :href="social.referUrl" class="content-source"><i class="fab" :class="social.fontClass"></i></a>
              </div>
              <span class="content-text">{{ social.title }}</span>
              <div class="content-footer">
                <span class="content-time">{{ social.createdAt }}</span>
                <a :href="social.referUrl">查看原文</a>
              </div>
            </div>
          </li>
        </ul>
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
      activeIndex: 1,
      noticeList: [{
        title: '有利论坛升级改造',
        url: 'http://www.baidu.com',
        date: '2018-10-13'
      }, {
        title: '有利论坛升级改造',
        url: 'http://www.baidu.com',
        date: '2018-10-13'
      }],
      socialList: [{
        title: '张默默',
        text: '的飞洒干啥跟帅哥撒旦个撒个是的个是多个砂锅大撒个是爱国撒个撒个是爱国按时高大上多个暗示过按时个啊三个大个啊三个大撒个撒个萨顶顶噶啥干撒个',
        logoUrl: '/static/img/logo.png',
        time: '2018-10-13',
        source: 'facebook'
      }, {
        title: '张默默',
        text: '的飞洒干啥跟帅哥撒旦个撒个是的个是多个砂锅大撒个是爱国撒个撒个是爱国按时高大上多个暗示过按时个啊三个大个啊三个大撒个撒个萨顶顶噶啥干撒个',
        logoUrl: '/static/img/logo.png',
        time: '2018-10-13',
        source: 'facebook'
      }]
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
      div {
        margin: 20px 0;
        text-align: center;
        img {
          width: 100%;
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
    .public-area {
      width: 100%;
      //max-height: 184px;
      height: 100%;
      background: rgba(255,255,255,1);
      .center-title {
        font-size:20px;
        padding-top: 30px;
        width: 100%;
        height: 56px;
        background: #4A4A4A;
        color: #FFF;
        font-family:PingFangSC-Semibold;
        //color:rgba(0,0,0,1);
        line-height:28px;
      }
        .social-list {
          padding: 30px 40px 20px;
          li {
            margin-bottom: 10px;
            img {
              width: 62px;
              height: 62px;
              border-radius: 50%;
            }
            .content-box {
              width: calc( 100% - 72px );
              min-height: 70px;
              vertical-align: top;
              display: inline-flex;
              flex-direction: column;
              justify-content: center;
              font-size: 16px;
              .title-box {
                display: flex;
                justify-content: space-between;
                align-items: center;
                .content-title {
                  color: #4A90E2;
                  line-height: 42px;
                }
                .content-source {
                  font-size: 20px;
                  color: #4A90E2;
                }
              }
              .content-text {
                color: #4A4A4A;
                line-height: 20px;
              }
              .content-footer {
                line-height: 30px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                .content-time {
                  font-size: 14px;
                  color: #9B9B9B;
                }
                a {
                  font-size: 14px;
                  color: #4A90E2;
                }
              }
            }
          }
        }
      .group-panel{
        height: 100%;
        img{
          width: 62px;
          height: 62px;
          margin: 40px 10px 30px 40px;
        }
        .content{
          //margin-left: 2px;
          vertical-align: top;
          display: inline-flex;
          justify-content: space-between;
          flex-direction: column;
          margin-top: 30px;
          .content-title{
            width: 248px;
            height: 22px;
            margin-top: 7px;
            text-align: left;
            font-size:14px;
            font-family:PingFangSC-Regular;
            color:rgba(74,144,226,1);
            line-height:22px;
          }
          .content-text{
            width: 650px;
            height: 40px;
            font-size: 14px;
            margin-top: 10px;
            font-family:PingFangSC-Regular;
            color:rgba(74,74,74,1);
            line-height: 20px;
            text-align: left;
            word-break: break-all;
            text-overflow: ellipsis;
            display: -webkit-box; /** 对象作为伸缩盒子模型显示 **/
            -webkit-box-orient: vertical; /** 设置或检索伸缩盒对象的子元素的排列方式 **/
            -webkit-line-clamp: 2; /** 显示的行数 **/
            overflow: hidden;  /** 隐藏超出的内容 **/
          }
          .content-time{
            width: 191px;
            height: 14px;
            font-size: 12px;
            font-family:PingFangSC-Regular;
            color:rgba(155,155,155,1);
            line-height: 17px;
            margin-top: 8px;
            text-align: left;
          }
        }
      }
    }
    .group-area {
      padding: 40px;
      //display: flex;
      //height: 200px;
      align-items: center;
      flex-wrap: wrap;
      h3{
        white-space: pre-wrap;
        text-align: center;
        // width:80px;
        // height:28px;
        font-size:20px;
        font-family:PingFangSC-Semibold;
        color:rgba(0,0,0,1);
        line-height:28px;
      }
      //justify-content: space-between;
      .notice-list {
        width: 100%;
        li {
          width: 100%;
          height: 40px;
          border-bottom: 1px solid #CACACA;
          font-size: 16px;
          color: #4A4A4A;
          a {
            height: 100%;
            display: block;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            .title {
              .dot {
                display: inline-block;
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background-color: #3A323D;
                position: relative;
                bottom: 3px;
                margin-right: 6px;
                margin-left: 6px;
              }
            }
            .date {
              color: #747474;
              margin-right: 25px;
            }
          }
        }
      }
      .public-content {
        margin-top: 20px;
        border-bottom: 0.5px solid #979797;
        padding-bottom: 4px;
        .content{
          margin-left: 5px;
          font-size:16px;
          font-family:PingFangSC-Regular;
          color:rgba(74,74,74,1);
          line-height:22px;
          width: 450px;
          //word-break: break-all;
          text-overflow: ellipsis;
          //display: -webkit-box; /** 对象作为伸缩盒子模型显示 **/
          -webkit-box-orient: vertical; /** 设置或检索伸缩盒对象的子元素的排列方式 **/
          -webkit-line-clamp: 1; /** 显示的行数 **/
          overflow: hidden;  /** 隐藏超出的内容 **/
        }
        .pub_time{
          //margin-left: 50px;
          float: right;
          font-size:16px;
          font-family:PingFangSC-Regular;
          color:rgba(116,116,116,1);
          line-height:22px;
        }
      }
    }
    .media-area {
      padding: 40px;
      font-family:PingFangSC-Semibold;
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
