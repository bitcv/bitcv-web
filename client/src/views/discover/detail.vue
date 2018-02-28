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
                <p>项目简称：<span class="text-primary">{{ info.nameEn }}</span></p>
                <p>项目符号：<span class="text-primary">{{ info.tokenSymbol }}</span></p>
              </div>
            </div>
            <p class="span-group">
              <span class="text-dark">标签</span>
              <span v-for="(item, index) in info.tagList" :key="index">{{ item }}</span>
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
                    <a href="javascript:;" :style="info.focusStatus ? 'color:#f10808;': 'color:#999'" class="text-dark" @click="handleFocus">
                      <span class="icon-bcv" :class="{'icon-heart': info.focusStatus == 0, 'icon-heart-fill': info.focusStatus == 1}"></span>
                    </a>
                  </p>
                </li>
                <li><p>分享&nbsp;&nbsp;&nbsp;<a href="javascript:;" @click="openShare"><span class="icon-bcv icon-share"></span></a></p></li>
                <li><p>关注：<span class="text-black fnum">{{ info.focusNum }}</span></p></li>
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
          <ul v-if="activeName == 'info'" class="nav nav-tabs">
            <li><a href="#info" onclick="javascript:$(this).css('border-color','#fdb76e').parent().siblings().children('li a').css('border-color','')">项目信息</a></li>
            <li><a href="#team" onclick="javascript:$(this).css('border-color','#fdb76e').parent().siblings().children('li a').css('border-color','')">团队信息</a></li>
            <li><a href="#develop" onclick="javascript:$(this).css('border-color','#fdb76e').parent().siblings().children('li a').css('border-color','')">项目发展</a></li>
            <li><a href="#partner" onclick="javascript:$(this).css('border-color','#fdb76e').parent().siblings().children('li a').css('border-color','')">合作伙伴</a></li>
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
                  <swiper-slide v-for="(item, index) in info.memberList" :key="index">
                    <div class="text-center">
                      <figure>
                        <p>
                          <!--<img class="img-circle" src="https://placehold.it/100x100">-->
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
                  <p>{{ info.abstract }}</p>
                </article>
              </div>
            </div><!-- /#team -->
            <div id="develop" class="panel-body">
              <h4 class="sub-title">项目发展</h4>
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
              <div class="sub-content">
                <div class="media">
                  <div class="media-left">
                    <img src="https://placehold.it/160x100" class="media-object" width="160">
                  </div>
                  <div class="media-body">
                    <p class="media-heading">区块链新锐BitCV获千万级天使融资</p>
                    <p class="text-darker">数字资产管理数字资产管理数字资产管理数字资产管理数字资产管理数字资产管理数字资产管理数字资产管理数字资产管理</p>
                    <p class="text-darker text-right">
                      <span>亿欧网</span>
                      <span class="text-dark">2018-01-30 10:00</span>
                    </p>
                  </div>
                </div>
                <div class="media">
                  <div class="media-left">
                    <img src="https://placehold.it/160x100" class="media-object" width="160">
                  </div>
                  <div class="media-body">
                    <p class="media-heading">区块链新锐BitCV获千万级天使融资</p>
                    <p class="text-darker">数字资产管理数字资产管理数字资产管理数字资产管理数字资产管理数字资产管理数字资产管理数字资产管理数字资产管理</p>
                    <p class="text-darker text-right">
                      <span>亿欧网</span>
                      <span class="text-dark">2018-01-30 10:00</span>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div id="offical" class="panel-body">
              <h4 class="sub-title">官方公告</h4>
              <div class="sub-content">
                <ul class="list-unstyled notice-list">
                  <li class="clearfix">
                    <span class="date">2018-02-21 13:58</span>
                    <p class="content">[总结] BCV空投活动总结</p>
                  </li>
                  <li class="clearfix">
                    <span class="date">2018-02-21 13:58</span>
                    <p class="content">[总结] BCV空投活动总结</p>
                  </li>
                  <li class="clearfix">
                    <span class="date">2018-02-21 13:58</span>
                    <p class="content">[总结] BCV空投活动总结</p>
                  </li>
                  <li class="clearfix">
                    <span class="date">2018-02-21 13:58</span>
                    <p class="content">[总结] BCV空投活动总结</p>
                  </li>
                  <li class="clearfix">
                    <span class="date">2018-02-21 13:58</span>
                    <p class="content">[总结] BCV空投活动总结</p>
                  </li>
                </ul>
              </div>
            </div>
            <div id="community" class="panel-body">
              <h4 class="sub-title">社区发布</h4>
              <div class="sub-content community">
                <div class="media">
                  <div class="media-left">
                    <img src="https://placehold.it/40x40" class="media-object" width="40">
                  </div>
                  <div class="media-body text-darker">
                    <p class="media-heading">渡惊鹭mask</p>
                    <p class="small text-dark">2月25日 11:21 来自 微博</p>
                    <p class="content">大过年就好好过年撕什么撕？领个BCV糖果。<a href="#" class="more">查看原文</a></p>
                  </div>
                </div>
                <div class="media">
                  <div class="media-left">
                    <img src="https://placehold.it/40x40" class="media-object" width="40">
                  </div>
                  <div class="media-body text-darker">
                    <p class="media-heading">渡惊鹭mask</p>
                    <p class="small text-dark">2月25日 11:21 来自 微博</p>
                    <p class="content">大过年就好好过年撕什么撕？领个BCV糖果。<a href="#" class="more">查看原文</a></p>
                  </div>
                </div>
                <div class="media">
                  <div class="media-left">
                    <img src="https://placehold.it/40x40" class="media-object" width="40">
                  </div>
                  <div class="media-body text-darker">
                    <p class="media-heading">渡惊鹭mask</p>
                    <p class="small text-dark">2月25日 11:21 来自 微博</p>
                    <p class="content">大过年就好好过年撕什么撕？领个BCV糖果。<a href="#" class="more">查看原文</a></p>
                  </div>
                </div>
                <div class="media">
                  <div class="media-left">
                    <img src="https://placehold.it/40x40" class="media-object" width="40">
                  </div>
                  <div class="media-body text-darker">
                    <p class="media-heading">渡惊鹭mask</p>
                    <p class="small text-dark">2月25日 11:21 来自 微博</p>
                    <p class="content">大过年就好好过年撕什么撕？领个BCV糖果。<a href="#" class="more">查看原文</a></p>
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
      shareUrl: ''
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
    fetch () {
      this.getProDetail({projId: this.proId})
        .then((data = {}) => (this.info = data))
    },
    handleFocus () {
      this.updateFocus({projId: this.proId})
        .then(data => {
          this.$set(this.info, 'focusStatus', this.info.focusStatus === 0 ? 1 : 0)
        })
          var tempnum = $('.fnum').text();
          var num = this.info.focusStatus === 0 ? parseInt(tempnum) + 1 : parseInt(tempnum) - 1 ;
          $(".fnum").text(num);
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
      },
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
