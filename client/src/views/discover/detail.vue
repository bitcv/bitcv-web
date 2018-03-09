<template>
  <div class="container">
    <div id="shareBox" v-show="showShare" class="share-container" @click="showShare = false">
      <share id="shareCard" :proj-detail="info"></share>
    </div>
    <!-- <div id="infoBox" class="info-box">
      <img :src="info.logoUrl" alt="">
      <div class="text-box">
        <div class="top-row">
          <span class="name-cn">{{ info.nameCn }}</span>
          <span class="name-en">({{ info.nameEn }})</span>
        </div>
        <div class="bottom-row">
          <span class="text-title">项目简称：</span>
          <span class="text">{{ info.tokenName }}</span>
          <span class="text-title">项目符号：</span>
          <span class="text">{{ info.tokenSymbol }}</span>
        </div>
      </div>
    </div> -->
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
                <p>{{ $t('label.project_shortname') }}：<span class="text-primary">{{ info.nameEn }}</span></p>
                <p>{{ $t('label.project_s') }}：<span class="text-primary">{{ info.tokenSymbol }}</span></p>
              </div>
            </div>
            <p class="span-group" v-if="info.tagList && info.tagList.length > 1">
              <span class="text-dark">{{ $t('label.detail_tag') }}</span>
              <span v-for="(item, index) in info.tagList" :key="index">{{ item }}</span>
            </p>
          </div>
          <div class="col-md-6 md-mg-t">
            <div class="col-xs-6 col-md-8">
              <p><a :href="info.homeUrl" class="btn btn-default btn-outline btn-sm" target="_blank" style="width:105px;">{{ $t('label.project_home') }}</a></p>
              <div style="height: 20px;"></div>
              <p><a :href="info.whitePaperUrl" class="btn btn-default btn-outline btn-sm" target="_blank" style="width:105px;">{{ $t('label.white_paper') }}</a></p>
            </div>
            <div class="col-xs-6 col-md-4">
              <ul class="list-unstyled text-dark">
                <li>
                  <p>
                    <span v-if="language === 'cn'" class="fixed-label">{{ ['关注', '取消关注'][info.focusStatus] }}&nbsp;&nbsp;</span>
                    <span v-else class="fixed-label">{{ ['follow', 'unfollow'][info.focusStatus] }}&nbsp;&nbsp;</span>
                    <a href="javascript:;" :style="info.focusStatus ? 'color:#f10808;': 'color:#999'" class="text-dark" @click="handleFocus">
                      <span class="icon-bcv" :class="{'icon-heart': info.focusStatus == 0, 'icon-heart-fill': info.focusStatus == 1}"></span>
                    </a>
                  </p>
                </li>
                <li>
                  <p>
                    <span class="fixed-label">{{ $t('label.share') }}：</span>
                    <a href="javascript:;" @click="openShare"><span class="icon-bcv icon-share"></span></a>
                  </p>
                </li>
                <li>
                  <p>
                    <span class="fixed-label">{{ $t('label.focus') }}：</span>
                    <span class="text-black fnum">{{ info.focusNum }}</span>
                  </p>
                </li>
                <li>
                  <p>
                    <span class="fixed-label">{{ $t('label.view') }}：</span>
                    <span class="text-black">{{ info.viewTimes }}</span>
                  </p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="tab-bar">
      <div class="row">
        <div class="col-md-6">
          <a href="javascript:;" class="tab-item" :class="{active: activeName == 'info'}" @click="activeName = 'info'">{{ $t('label.project_info') }}</a>
          <a href="javascript:;" class="tab-item" :class="{active: activeName == 'dynamic'}" @click="activeName = 'dynamic'">{{ $t('label.project_dy') }}</a>
        </div>
        <div class="col-md-6 text-right hidden-xs hidden-sm">
          <ul class="list-inline text-darker">
            <li class="text-dark">{{ $t('label.social') }}</li>
            <li v-for="(item, index) in info.socialList" :key="index">
              <a :href="item.linkUrl" target="_blank">
                <span class="icon-bcv" :class="'icon-'+item.name"></span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="component-wrapper">
      <div class="row">
        <div class="col-md-8">
          <ul v-if="activeName == 'info' && language === 'cn'" class="nav nav-tabs">
            <li v-for="(item, index) in infoList" :class="{active: item.active}" :key="index">
              <a :href="item.target" @click="onTabItemClick(infoList, item)">{{ item.text }}</a>
            </li>
          </ul>
          <ul v-else-if="activeName == 'info' && language === 'en'" class="nav nav-tabs">
            <li v-for="(item, index) in e_infoList" :class="{active: item.active}" :key="index">
              <a :href="item.target" @click="onTabItemClick(e_infoList, item)">{{ item.text }}</a>
            </li>
          </ul>
          <ul v-if="activeName == 'dynamic' && language === 'cn'" class="nav nav-tabs">
            <li v-for="(item, index) in dynamicList" :class="{active: item.active}" :key="index">
              <a :href="item.target" @click="onTabItemClick(dynamicList, item)">{{ item.text }}</a>
            </li>
          </ul>
          <ul v-if="activeName == 'dynamic' && language === 'en'" class="nav nav-tabs">
            <li v-for="(item, index) in e_dynamicList" :class="{active: item.active}" :key="index">
              <a :href="item.target" @click="onTabItemClick(e_dynamicList, item)">{{ item.text }}</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="row outer">
        <div class="col-md-8 col">
          <div v-if="activeName == 'info'" class="tab-content">
            <div id="info" class="panel-body">
              <h4 class="sub-title">{{ $t('label.project_intr') }}</h4>
              <div class="sub-content">
                <p>{{ info.shortDesc }}</p>
                <article class="text-darker">
                  <p v-if="info.bannerUrl"><img :src="info.bannerUrl" style="max-width:100%"></p>
                  <p>{{ info.abstract }}</p>
                </article>
              </div>
            </div><!-- /#info -->
            <div id="team" class="panel-body">
              <h4 class="sub-title">{{ $t('label.team_mem') }}</h4>
              <div class="sub-content">
                <swiper :options="swiperOption" ref="swiper">
                  <swiper-slide v-for="(item, index) in info.memberList" :key="index">
                    <div class="text-center">
                      <figure>
                        <p>
                          <img v-if="item.photoUrl" :src="item.photoUrl" class="img-circle">
                          <span v-else class="avatar-span">{{ item.name ? item.name.substr(0, 1) : '' }}</span>
                        </p>
                        <figcaption>{{ item.name }}</figcaption>
                      </figure>
                      <p class="desc">{{ item.intro}}</p>
                    </div>
                  </swiper-slide>
                  <div class="swiper-button-prev" slot="button-prev" @click="swiperPrev"><span class="icon-bcv icon-arrow-left"></span></div>
                  <div class="swiper-button-next" slot="button-next" @click="swiperNext"><span class="icon-bcv icon-arrow-right"></span></div>
                </swiper>
              </div>
              <div v-if="info.advisorList && info.advisorList.length">
                <h4 class="sub-title">{{ $t('label.project_advisor') }}</h4>
                <div class="sub-content">
                  <div class="row adviser-list">
                    <div class="col-xs-6 col-sm-4 col-md-3" v-for="(item, index) in info.advisorList" :key="index">
                      <div class="thumbnail text-center">
                        <div class="img img-circle" v-if="item.logoUrl" :style="{backgroundImage: `url(${item.logoUrl})`}"></div>
                        <span v-else class="avatar-span">{{ item.name ? item.name.substr(0, 1) : '' }}</span>
                        <div class="caption">
                          <div class="text-darker">
                            <span>{{ item.name }}</span><br>
                            <p class="text-ellipsis text-dark" style="margin:0;"><small>{{ item.intro }}</small></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- /#team -->
            <div id="develop" class="panel-body" v-if="info.eventList && info.eventList.length">
              <h4 class="sub-title">{{ $t('label.project_dev') }}</h4>
              <div class="sub-content">
                <div class="timelime">
                  <div class="timelime-item" v-for="(item, index) in info.eventList" :key="index">
                    <p class="label">{{ item.eventKey }}</p>
                    <ul class="time-list">
                      <li class="item" v-for="(item2,i) in item.eventNode" :key="i">
                        <p class="time">{{ item2.time}}</p>
                        <p class="content">{{ item2.title}}</p>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div><!-- /#develop -->
            <div id="partner" class="panel-body" v-if="info.partnerList && info.partnerList.length">
              <h4 class="sub-title">{{ $t('label.brother') }}</h4>
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
              <h4 class="sub-title">{{ $t('label.media') }}</h4>
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
              <h4 class="sub-title">{{ $t('label.anno') }}</h4>
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
              <h4 class="sub-title">{{ $t('label.comm_anno') }}</h4>
              <div class="sub-content community">
                <div class="media" v-for="(social, index) in info.dynamicList" :key="index">
                  <div class="media-left">
                    <img :src="social.logoUrl" alt="" class="media-object" width="40">
                  </div>
                  <div class="media-body text-darker">
                    <p class="media-heading">{{ social.officialName }}</p>
                    <p class="small text-dark">{{ social.postTime }} 来自 {{ social.name }}</p>
                    <p class="content"><span v-html="social.title"></span><a :href="social.referUrl" target="_blank" class="more">{{ $t('label.view_full') }}</a></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 col">
          <div class="panel-body text-darker" style="padding-top: 0">
            <h4>{{ $t('label.claim_co') }}</h4>
            <p class="text-center" style="height:200px;">
              <img src="https://placehold.it/100x100" style="width:100px;height100px;margin-top:50px;">
            </p>
            <div v-if="address">
              <h4>{{ $t('label.co_address') }}</h4>
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
import Share from '@/components/share/Share'
import html2canvas from 'html2canvas'

export default {
  props: {
    projDetail: Object
  },
  components: {
    swiper,
    swiperSlide,
    Share
  },
  data () {
    return {
      info: {},
      activeName: 'info',
      swiperOption: {
        autoplay: true
      },
      showShare: false,
      shareUrl: '',
      infoList: [
        {text: '项目信息', target: '#info', active: true},
        {text: '团队信息', target: '#team', active: false},
        {text: '项目发展', target: '#develop', active: false},
        {text: '合作伙伴', target: '#partner', active: false}
      ],
      e_infoList: [
        {text: 'Project information', target: '#info', active: true},
        {text: 'Team Information', target: '#team', active: false},
        {text: 'Project development', target: '#develop', active: false},
        {text: 'Cooperative partner', target: '#partner', active: false}
      ],
      dynamicList: [
        {text: '媒体报道', target: '#media', active: true},
        {text: '官方发布', target: '#offical', active: false},
        {text: '社区发布', target: '#community', active: false}
      ],
      e_dynamicList: [
        {text: 'Media coverage', target: '#media', active: true},
        {text: 'Official announcement', target: '#offical', active: false},
        {text: 'Community release', target: '#community', active: false}
      ]
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
    },
    language () {
      return this.$i18n.locale
    }
  },
  created () {
    this.fetch()
  },
  methods: {
    ...mapActions([
      'getProDetail',
      'updateFocus'
    ]),
    swiperPrev () {
      this.swiper.slidePrev()
    },
    swiperNext () {
      this.swiper.slideNext()
    },
    onTabItemClick (list, item) {
      list.map(item => {
        item.active = false
      })

      item.active = true
    },
    fetch () {
      this.getProDetail({projId: this.proId})
        .then((data = {}) => (this.info = data))
    },
    handleFocus () {
      this.updateFocus({projId: this.proId})
        .then(data => {
          // 修改关注状态
          this.$set(this.info, 'focusStatus', this.info.focusStatus === 0 ? 1 : 0)

          // 修改关注数量
          let {focusNum} = this.info

          this.$set(this.info, 'focusNum', this.info.focusStatus === 0 ? focusNum - 1 : focusNum + 1)
        })
    },
    openShare: function () {
      this.showShare = true
      if (this.shareUrl) return
      this.$nextTick(() => {
        html2canvas(document.querySelector('#shareCard')).then(canvas => {
          document.querySelector('#shareBox').innerHTML = ''
          var url = canvas.toDataURL()
          this.shareUrl = url
          var newImg = document.createElement('img')
          newImg.src = url
          newImg.style.width = '90%'
          newImg.class = 'share-img'
          document.querySelector('#shareBox').appendChild(newImg)
        })
      })
    },
    toggleFocus: function () {
      var userId = localStorage.getItem('userId')
      if (!userId) {
        return alert('请登录')
      }
      this.$http.post('/api/toggleFocus', {
        projId: this.projDetail.id
      }).then((res) => {
        var resData = res.data
        if (resData.errcode === 0) {
          this.projDetail.focusStatus = resData.data.status
        }
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
.fixed-label {
  display: inline-block;
  vertical-align: middle;
  width: 80px;
  text-align: right;
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
      > .img {
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
      }
      > .img,
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
      // border: 1px solid $gray;
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
.avatar-span {
  width: 150px;
  height: 150px;
  position: relative;
  display: block;
  line-height: 150px;
  font-size: 30px;
  color: #666;
  text-align: center;
  text-transform: uppercase;
  background-color: #e5e5e5;
  border-radius: 50%;
  margin: 0 auto;
}
.share-box {
    height: 46px;
    display: flex;
    position: absolute;
    right: 40px;
    bottom: 0;
    align-items: center;
    font-size: 14px;
    line-height: 20px;
    color: #000;
    cursor: pointer;
    img {
      width: 23px;
      height: 23px;
      margin: 0 5px;
    }
  }
  .share-container {
    width: 400px;
    position: fixed;
    z-index: 10;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    cursor: pointer;
    margin: auto;
    display: flex;
    justify-content: center;
    align-items: center;
    &>img {
      width: 100%;
    }
  }
</style>
