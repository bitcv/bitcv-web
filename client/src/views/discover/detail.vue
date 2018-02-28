<template>
  <div class="container">
    <div class="panel panel-custom">
      <div class="panel-body" style="padding:15px 30px 0;">
        <div class="row">
          <div class="col-md-6">
            <div class="media">
              <div class="media-left">
                <img :src="info.logoUrl" class="media-object img-rounded" width="100">
              </div>
              <div class="media-body">
                <h4>{{ info.nameCn}}</h4>
                <p>项目简称：<span class="text-primary">{{ info.nameEn }}</span></p>
                <p>项目符号：<span class="text-primary">{{ info.tokenSymbol }}</span></p>
              </div>
            </div>
            <p class="span-group">
              <span class="text-dark">标签</span>
              <span>数字管理</span>
              <span>SaaS</span>
            </p>
          </div>
          <div class="col-md-6 md-mg-t">
            <div class="col-xs-8">
              <p><a :href="info.homeUrl" class="btn btn-default btn-outline btn-sm" target="_blank" style="width:80px;">项目主页</a></p>
              <div style="height: 20px;"></div>
              <p><a :href="info.whitePaperUrl" class="btn btn-default btn-outline btn-sm" target="_blank" style="width:80px;">白皮书</a></p>
            </div>
            <div class="col-xs-4">
              <ul class="list-unstyled text-dark">
                <li>
                  <p>{{ ['关注', '取消关注'][info.focusStatus] }}&nbsp;&nbsp;&nbsp;
                    <a href="javascript:;" class="text-dark" @click="handleFocus">
                      <span class="icon-bcv" :class="{'icon-heart': info.focusStatus == 0, 'icon-heart-fill': info.focusStatus == 1}"></span>
                    </a>
                  </p>
                </li>
                <li><p>分享&nbsp;&nbsp;&nbsp;<a href="javascript:;"><span class="icon-bcv icon-share"></span></a></p></li>
                <li><p>关注：<span class="text-black">{{ info.focusNum }}</span></p></li>
                <li><p>浏览：<span class="text-black">{{ info.viewTimes }}</span></p></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="tab-bar">
      <div class="row">
        <div class="col-md-6">
          <a href="javascript:;" class="tab-item" :class="{active: activeName == 'info'}" @click="activeName = 'info'">项目信息</a>
          <a href="javascript:;" class="tab-item" :class="{active: activeName == 'dynamic'}" @click="activeName = 'dynamic'">项目动态</a>
        </div>
        <div class="col-md-6 text-right hidden-xs hidden-sm">
          <ul class="list-inline text-darker">
            <li class="text-dark">社区</li>
            <li><a href="#" target="_blank"><span class="icon-bcv icon-weibo"></span></a></li>
            <li><a href="#" target="_blank"><span class="icon-bcv icon-github"></span></a></li>
            <li><a href="#" target="_blank"><span class="icon-bcv icon-facebook"></span></a></li>
            <li><a href="#" target="_blank"><span class="icon-bcv icon-telegram"></span></a></li>
            <li><a href="#" target="_blank"><span class="icon-bcv icon-twitter"></span></a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="component-wrapper">
      <div class="row">
        <div class="col-md-8">
          <ul v-if="activeName == 'info'" class="nav nav-tabs">
            <li><a href="#info">项目信息</a></li>
            <li><a href="#team">团队信息</a></li>
            <li><a href="#develop">项目发展</a></li>
            <li><a href="#partner">合作伙伴</a></li>
          </ul>
          <ul v-if="activeName == 'dynamic'" class="nav nav-tabs">
            <li><a href="#media">媒体报道</a></li>
            <li><a href="#offical">官方公告</a></li>
            <li><a href="#community">社区发布</a></li>
          </ul>
        </div>
      </div>
      <div class="row outer">
        <div class="col-md-8 col">
          <div v-if="activeName == 'info'" class="tab-content">
            <div id="info" class="panel-body">
              <div class="sub-content">
                <swiper :options="swiperOption" ref="swiper">
                  <swiper-slide>
                    <div class="text-center">
                      <figure>
                        <p>
                          <img class="img-circle" src="https://placehold.it/100x100">
                        </p>
                        <figcaption>伍星</figcaption>
                      </figure>
                      <p class="desc">清华大学五道口金融学院全球创业领袖班学员。2004年毕业于北京工商大学，和第六届自组织理事团队核心成员清华大学五道口金融学院全球创业领袖班学员。2004年毕业于北京工商大学，和第六届自组织理事团队核心成员清华大学五道口金融学院全球创业领袖班学员。2004年毕业于北京工商大学，和第六届自组织理事团队核心成员清华大学五道口金融学院全球创业领袖班学员。2004年毕业于北京工商大学，和第六届自组织理事团队核心成员</p>
                    </div>
                  </swiper-slide>
                  <swiper-slide>
                    <div class="text-center">
                      <figure>
                        <p><img class="img-circle" src="https://placehold.it/100x100"></p>
                        <figcaption>伍星</figcaption>
                      </figure>
                      <p class="desc">清华大学五道口金融学院全球创业领袖班学员。2004年毕业于北京工商大学，和第六届自组织理事团队核心成员清华大学五道口金融学院全球创业领袖班学员。2004年毕业于北京工商大学，和第六届自组织理事团队核心成员清华大学五道口金融学院全球创业领袖班学员。2004年毕业于北京工商大学，和第六届自组织理事团队核心成员清华大学五道口金融学院全球创业领袖班学员。2004年毕业于北京工商大学，和第六届自组织理事团队核心成员</p>
                    </div>
                  </swiper-slide>
                  <swiper-slide>
                    <div class="text-center">
                      <figure>
                        <p><img class="img-circle" src="https://placehold.it/100x100"></p>
                        <figcaption>伍星</figcaption>
                      </figure>
                      <p class="desc">清华大学五道口金融学院全球创业领袖班学员。2004年毕业于北京工商大学，和第六届自组织理事团队核心成员清华大学五道口金融学院全球创业领袖班学员。2004年毕业于北京工商大学，和第六届自组织理事团队核心成员清华大学五道口金融学院全球创业领袖班学员。2004年毕业于北京工商大学，和第六届自组织理事团队核心成员清华大学五道口金融学院全球创业领袖班学员。2004年毕业于北京工商大学，和第六届自组织理事团队核心成员</p>
                    </div>
                  </swiper-slide>
                  <div class="swiper-button-prev" slot="button-prev" @click="swiperPrev"><span class="icon-bcv icon-arrow-left"></span></div>
                  <div class="swiper-button-next" slot="button-next" @click="swiperNext"><span class="icon-bcv icon-arrow-right"></span></div>
                </swiper>
              </div>
            </div><!-- /#info -->
            <div id="team" class="panel-body">
              <h4 class="sub-title">项目顾问</h4>
              <div class="sub-content">
                <div class="row adviser-list">
                  <div class="col-xs-6 col-sm-4 col-md-3" v-for="(item, index) in info.advisorList" :key="index">
                    <div class="thumbnail text-center">
                      <img v-if="item.photoUrl" :src="item.photoUrl" class="img-circle">
                      <span v-else class="avatar-span">{{ item.name ? item.name.substr(0, 1) : '' }}</span>
                      <div class="caption">
                        <p class="text-darker">
                          <span>{{ item.name }}</span><br>
                          <small class="text-dark">{{ item.intro }}</small>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <h4 class="sub-title">项目简介</h4>
              <div class="sub-content">
                <article class="text-darker">
                  <p v-if="info.bannerUrl"><img :src="info.bannerUrl"></p>
                  <p>info.abstract</p>
                </article>
              </div>
            </div><!-- /#team -->
            <div id="develop" class="panel-body">
              <h4 class="sub-title">项目发展</h4>
              <div class="sub-content">
                <div class="timelime">
                  <div class="timelime-item">
                    <p class="label">2019年第一季度</p>
                    <ul class="time-list">
                      <li class="item">
                        <p class="time">03月01日</p>
                        <p class="content">资管生态建立完成</p>
                      </li>
                    </ul>
                  </div>
                  <div class="timelime-item">
                    <p class="label">2018年第三季度</p>
                    <ul class="time-list">
                      <li class="item">
                        <p class="time">08月31日</p>
                        <p class="content">资管链整体上线启动运营</p>
                      </li>
                    </ul>
                  </div>
                  <div class="timelime-item">
                    <p class="label">2018年第二季度</p>
                    <ul class="time-list">
                      <li class="item">
                        <p class="time">04月25日</p>
                        <p class="content">链评测投入运营</p>
                      </li>
                    </ul>
                  </div>
                  <div class="timelime-item">
                    <p class="label">2018年第一季度</p>
                    <ul class="time-list">
                      <li class="item">
                        <p class="time">03月01日</p>
                        <p class="content">链服务投入运营</p>
                      </li>
                      <li class="item">
                        <p class="time">02月20日</p>
                        <p class="content">投融SaaS测试运行</p>
                      </li>
                      <li class="item">
                        <p class="time">02月01日</p>
                        <p class="content">财经通开始运营</p>
                      </li>
                      <li class="item">
                        <p class="time">01月15日</p>
                        <p class="content">第一版产品原型Demo完成，白皮书V1.1完成</p>
                      </li>
                      <li class="item">
                        <p class="time">01月05日</p>
                        <p class="content">启动天使轮融资</p>
                      </li>
                    </ul>
                  </div>
                  <div class="timelime-item">
                    <p class="label">2017年第四季度</p>
                    <ul class="time-list">
                      <li class="item">
                        <p class="time">12月01日</p>
                        <p class="content">项目白皮书第一版完成，正式启动项目开发</p>
                      </li>
                      <li class="item">
                        <p class="time">11月01日</p>
                        <p class="content">项目初始原型与产品架构设计与规划完成</p>
                      </li>
                      <li class="item">
                        <p class="time">10月01日</p>
                        <p class="content">初始团队构建完成</p>
                      </li>
                    </ul>
                  </div>
                  <div class="timelime-item">
                    <p class="label">2017年第三季度</p>
                    <ul class="time-list">
                      <li class="item">
                        <p class="time">09月01日</p>
                        <p class="content">项目初始规划启动</p>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div><!-- /#develop -->
            <div id="partner" class="panel-body">
              <h4 class="sub-title">合作伙伴</h4>
              <div class="sub-content">
                <div class="row partner-list">
                  <div class="col col-xs-6 col-sm-4 col-md-3" v-for="(item, index) in info.partnerList" :key="index">
                    <a class="partner-item" :title="item.title">
                      <img class="img-responsive" :src="item.logoUrl" :alt="item.name">
                    </a>
                  </div>
                </div>
              </div>
            </div><!-- /#partner -->
          </div>
          <div v-if="activeName == 'dynamic'" class="tab-content">
            <div id="media" class="panel-body">
              <h4 class="sub-title">媒体报道</h4>
              <div class="sub-content" :href="report.bannerUrl" target="_blank" v-for="(report, index) in info.reportList" :key="index">
                <div class="media">
                  <div class="media-left">
                    <img :src="report.logoUrl" alt="" class="media-object" width="160">
                  </div>
                  <div class="media-body">
                    <p class="media-heading">{{report.title }}</p>
                    <p class="text-darker" v-html="report.content"></p>
                    <p class="text-darker text-right">
                      <span>{{ report.name }}</span>
                      <span class="text-dark">{{ report.releaseTime}}</span>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div id="offical" class="panel-body" v-if="info.publicList && info.publicList.length">
              <h4 class="sub-title">官方公告</h4>
              <div class="sub-content">
                <ul class="list-unstyled notice-list">
                  <li class="clearfix" v-for="(notice, index) in info.publicList" :key="index">
                    <span class="date">{{ notice.postTime | formatDate }}</span>
                    <p class="content"><span v-html="notice.title"></span></p>
                  </li>
                </ul>
              </div>
            </div>
            <div id="community" class="panel-body" v-if="info.dynamicList && info.dynamicList.length">
              <h4 class="sub-title">社区发布</h4>
              <div class="sub-content community">
                <div class="media" v-for="(social, index) in info.dynamicList" :key="index">
                  <div class="media-left">
                    <img :src="social.logoUrl" alt="" class="media-object" width="40">
                  </div>
                  <div class="media-body text-darker">
                    <p class="media-heading">{{ social.officialName }}</p>
                    <p class="small text-dark">{{ social.postTime }} 来自 {{ social.name }}</p>
                    <p class="content"><span v-html="social.title"></span><a :href="social.referUrl" class="more">查看原文</a></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 col">
          <div class="panel-body text-darker" style="padding-top: 0">
            <h4>认领该公司</h4>
            <p class="text-center" style="height:200px;">
              <img src="https://placehold.it/100x100" style="width:100px;height100px;margin-top:50px;">
            </p>
            <div v-if="address">
              <h4>公司联系信息</h4>
              <p><span class="icon-bcv icon-loufang01"></span> {{ address }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex'
import {swiper, swiperSlide} from 'vue-awesome-swiper'
import {formatDate} from '@/utils/utils'
export default {
  components: {
    swiper,
    swiperSlide
  },
  data () {
    return {
      info: {},
      activeName: 'info',
      swiperOption: {
        autoplay: true
      }
    }
  },
  filters: {
    formatDate (time) {
      let date = new Date(time)
      return formatDate(date, 'yyyy-MM-dd')
    }
  },
  computed: {
    ...mapState({
      proId: state => state.route.params.id
    }),
    swiper () {
      return this.$refs.swiper.swiper
    },
    address () {
      const {
        companyAddr,
        companyEmail
      } = this.info
      if (companyAddr && companyEmail) {
        return `${companyEmail} ${companyAddr}`
      }
      return ''
    }
  },
  created () {
    this.fetch()
  },
  methods: {
    ...mapActions(['getProDetail']),
    swiperPrev () {
      this.swiper.slidePrev()
    },
    swiperNext () {
      this.swiper.slideNext()
    },
    fetch () {
      this.getProDetail({projId: this.proId})
        .then((data = {}) => (this.info = data))
    },
    handleFocus () {
      this.updateFocus({projId: this.proId})
        .then(data => {
          this.$set(this.info, 'focusStatus', this.info.focusStatus === 0 ? 1 : 0)
        })
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~@/styles/variables';
.span-group {
  margin-top: 15px;
  margin-bottom: 15px;
  > span {
    display: inline-block;
    vertical-align: middle;
    & + span {
      margin-left: 10px;
    }
  }
}
.tab-bar {
  .tab-item {
    display: inline-block;
    margin-left: 30px;
    margin-right: 20px;
    padding-bottom: 15px;
    color: $gray-dark;
    border-bottom: 2px solid transparent;
    &:hover {
      color: $primary-color;
    }
    &.active {
      color: $primary-color;
      border-color: $primary-color;
    }
  }
}
.component-wrapper {
  background-color: #fff;
  > .outer {
    margin: 0;
    > .col {
      padding: 0;
    }
  }
  .nav-tabs {
    > li {
      > a {
        padding: 10px 0;
        margin-left: 30px;
        color: $gray-dark;
        border: none;
        border-radius: 0;
        border-bottom: 2px solid transparent;
        &:hover, &:focus {
          color: $primary-color;
          background-color: transparent;
        }
      }
      &.active > a {
        color: $black-light;
        border-bottom-color: $primary-color;
      }
    }
  }
  .sub-title {
    margin-bottom: 15px;
  }
  .swiper-slide {
    figure {
      img {
        width: 120px;
        height: 120px;
      }
      figcaption {
        font-size: 16px;
        margin: 20px 0;
      }
    }
    .desc {
      font-size: 14px;
      text-align: left;
      line-height: 1.5;
      color: $gray-dark;
    }
  }
  .swiper-button-prev, .swiper-button-next {
    background-image: none;
    width: 30px;
    height: 30px;
    top: 30%;
    line-height: 25px;
    font-size: 24px;
    text-align: center;
    color: #fff;
    background-color: $gray;
    border-radius: 50%;
    &:hover {
      background-color: $primary-color;
    }
  }
  .swiper-button-prev {
    left: 20px;
  }
  .swiper-button-next {
    right: 20px;
  }
  .adviser-list {
    .thumbnail {
      border-radius: 0;
      > img,
      > .avatar-span {
        width: 100px;
        height: 100px;
        margin: 15px auto 10px;
      }
      .avatar-span {
        position: relative;
        display: block;
        line-height: 100px;
        font-size: 30px;
        color: $gray-darker;
        text-align: center;
        text-transform: uppercase;
        background-color: $gray-light;
        border-radius: 50%;
      }
      .caption > p {
        margin: 0;
      }
    }
  }
  .partner-list {
    margin-left: -5px;
    margin-right: -5px;
    > .col {
      position: relative;
      padding-left: 5px;
      padding-right: 5px;
      padding-bottom: 80px;
      margin-bottom: 10px;
      img {
        max-width: 100%;
        max-height: 100%;
      }
    }
    .partner-item {
      display: flex;
      position: absolute;
      top: 0;
      right: 5px;
      bottom: 0;
      left: 5px;
      align-items: center;
      justify-content: center;
      border: 1px solid $gray;
    }
  }
}
.timelime {
  position: relative;
  padding-top: 20px;
  overflow: hidden;
  &:before {
    content: '';
    position: absolute;
    width: 1px;
    top: 0;
    left: 160px;
    bottom: 10px;
    background-color: $gray;
  }
  .timelime-item {
    .label {
      display: block;
      width: 140px;
      padding: 0;
      margin: 0;
      font-size: 16px;
      font-weight: normal;
      white-space: nowrap;
      color: $primary-color;
      text-align: right;
    }
    .time-list {
      position: relative;
      margin-top: 10px;
      margin-bottom: 20px;
      list-style-type: none;
      padding-left: 0;
      .item {
        padding-left: 180px;
        color: $gray-dark;
        .time {
          position: absolute;
          width: 140px;
          white-space: nowrap;
          left: 0;
          text-align: right;
          &:after {
            content: '';
            position: absolute;
            width: 10px;
            height: 10px;
            top: 50%;
            right: -25px;
            margin-top: -4px;
            background-color: $gray;
            border: 2px solid #fff;
            border-radius: 50%;
          }
        }
      }
    }
  }
}
.notice-list {
  li {
    line-height: 2;
    > .date {
      float: right;
      width: 120px;
      color: $gray-dark;
    }
    .content {
      margin-right: 120px;
      color: $gray-darker;
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
    }
  }
}
.community .media {
  border-bottom: 1px dashed $gray-light;
  .content {
    margin-top: 15px;
    > a.more {
      float: right;
      color: $primary-color;
    }
  }
}
@media (min-width: 992px) {
  .component-wrapper {
    > .outer {
      display: flex;
      flex-direction: row;
      > .col {
        position: relative;
        float: none;
        &:first-child:after {
          content: '';
          position: absolute;
          width: 2px;
          right: 0;
          top: 0;
          bottom: 0;
          background-color: $gray-light;
        }
      }
    }
    .sub-content {
      padding-left: 20px;
    }
    .swiper-slide {
      figure {
        img {
          width: 150px;
          height: 150px;
        }
        figcaption {
          font-size: 18px;
        }
      }
      .desc {
        font-size: 16px;
      }
    }
    .swiper-button-prev {
      left: 50px;
    }
    .swiper-button-next {
      right: 50px;
    }
  }
}
</style>
