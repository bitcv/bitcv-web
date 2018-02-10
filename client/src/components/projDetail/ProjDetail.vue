<template>
  <div class="proj-detail">
    <div class="main-panel">
      <proj-info-panel :proj-detail="projDetail"></proj-info-panel>
      <div class = "banner">
        <router-link class="detail-box" :class="{ active: current === 1 }" :to="{ path: '/projDetail/info/' + projDetail.id}">
          <img :src="'/static/img/project' + (current === 1 ? '.png' : '-default.png')" alt="">
          <span class="detail">项目信息</span>
          <!--<el-button class = "detail">项目信息</el-button>-->
        </router-link>
        <router-link class="news-box" :class="{ active: current === 2 }" :to="{ path: '/projDetail/dynamic/' + projDetail.id}">
          <img :src="'/static/img/dynamic' + (current === 2 ? '.png' : '-default.png')" alt="">
          <span class="news">项目动态</span>
          <!--<el-button class = "news">项目动态</el-button>-->
        </router-link>
      </div>
      <!--<proj-detail-panel :proj-detail="projDetail"></proj-detail-panel>-->
      <router-view :proj-detail="projDetail"></router-view>
    </div>
    <div class="aside-panel">
      <div class="record-panel">
        <div class="num-box">
          <div class="view"><span>{{ projDetail.viewTimes }}</span></div>
          <div class="focus"><span>{{ projDetail.focusNum }}</span></div>
        </div>
        <div class="icon-box">
          <div class="view">
            <img src="/static/img/view@2x.png" alt="">
            <span>浏览</span>
          </div>
          <div class="focus">
            <img src="/static/img/unfocus@2x.png" alt="">
            <span>关注</span>
          </div>
        </div>
      </div>
      <div class="btn-panel">
        <h3 class="center-title panel-title">认领该公司</h3>
        <img src="/static/img/头像扫描@2x.png" alt="">
      </div>
      <div v-if="projDetail.companyTel || projDetail.companyEmail || projDetail.companyAddr" class="contact-panel">
        <h3 class="center-title panel-title">公司联系信息</h3>
        <div v-if="projDetail.companyTel" class="info-row">
          <img src="/static/img/tel@2x.png" alt="">
          <span class="text">{{ projDetail.companyTel }}</span>
        </div>
        <div v-if="projDetail.companyEmail" class="info-row">
          <img src="/static/img/email@2x.png" alt="">
          <span class="text">{{ projDetail.companyEmail }}</span>
        </div>
        <div v-if="projDetail.companyAddr" class="info-row">
          <img src="/static/img/addr@2x.png" alt="">
          <span class="text">{{ projDetail.companyAddr }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ProjInfoPanel from '@/components/projDetail/ProjInfoPanel'
import ProjDetailPanel from '@/components/projDetail/ProjDetailPanel'

export default {
  data () {
    return {
      current: 1,
      showShare: false,
      projDetail: {
        nameCn: '',
        nameEn: '',
        logoUrl: '',
        tokenName: '',
        tokenSymbol: '',
        whitePaperUrl: '',
        abstract: '',
        tagList: [],
        partnerList: [],
        memberList: [],
        reportList: [],
        eventList: [],
        advisorList: [],
        bannerUrl: '',
        companyTel: '',
        companyAddr: '',
        companyEmail: '',
        viewTimes: 0,
        focusNum: 0,
        focusStatus: 0
      }
    }
  },
  mounted () {
    // 获取项目信息
    var projId = this.$route.params.id
    console.log('projId:' + projId)
    var that = this
    this.$http.post('/api/getProjDetail', {
      projId: projId
    }).then(function (res) {
      var resData = res.data
      if (resData.errcode === 0) {
        that.projDetail = resData.data
        console.log(that.projDetail)
      } else {
        alert(resData.errmsg)
      }
    })
    // 项目浏览数+1
    this.$http.post('/api/viewProject', {
      projId: projId
    })
  },
  watch: {
    $route () {
      var path = this.$route.path
      if (path.indexOf('dynamic') !== -1) {
        this.current = 2
      } else {
        this.current = 1
      }
    }
  },
  components: {
    ProjInfoPanel,
    ProjDetailPanel
  }
}
</script>

<style lang="scss" scoped>
.proj-detail {
  width: 1200px;
  max-width: 100%;
  display: flex;
  justify-content: space-between;
  .main-panel {
    flex: 1 1 auto;
    min-width: 300px;
    div {
      margin-bottom: 24px;
    }
    .banner{
      background-color: #FFF;
      width: 100%;
      height: 40px;
      margin-top: -11px;
      margin-bottom: 10px;
      display: flex;
      justify-content: space-around;
      align-items: center;
      color: #9B9B9B;
      font-size: 16px;
      text-align: center;
      .detail-box {
        width: 50%;
        height: 22px;
        display: inline-block;
        box-sizing: border-box;
        border-right: 1px solid #9B9B9B;
        &.active {
          color: #FF6276;
        }
        img {
          position: relative;
          top: 3px;
        }
      }
      .news-box {
        width: 50%;
        &.active {
          color: #FF6276;
        }
      }
    }
  }
  .aside-panel {
    flex-shrink: 0;
    width: 350px;
    margin-left: 24px;
    >div {
      box-sizing: border-box;
      width: 100%;
      margin-bottom: 24px;
      background-color: #FFF;
      padding: 20px;
    }
    .record-panel {
      .num-box {
        width: 100%;
        font-size: 0;
        line-height: 50px;
        color: #4A4A4A;
        >div {
          height: 69px;
          width: 50%;
          display: inline-flex;
          justify-content: center;
          align-items: center;
          font-size: 36px;
          box-sizing: border-box;
          &:first-child {
            border-right: 0.5px solid #979797;
          }
        }
        .view {

        }
        .focus {

        }
      }
      .icon-box {
        width: 100%;
        display: flex;
        justify-content: space-around;
        align-items: center;
        font-size: 14px;
        line-height: 20px;
        color: #9B9B9B;
        img {
          width: 14px;
          height: 11px;
        }
      }
    }
    .btn-panel {
      text-align: center;
      img {
        width: 64px;
        height: 64px;
        margin: 26px 0 8px;
      }
    }
    .contact-panel {
      .center-title {
        margin-bottom: 24px;
      }
      .info-row {
        margin: 0 0 17px;
        font-size: 0;
        vertical-align: top;
        img {
          width: 17px;
          height: 17px;
        }
        .text {
          margin-left: 16px;
          font-size: 14px;
          color: #4A4A4A;
          line-height: 17px;
          position: relative;
          bottom: 3px;
        }
      }
    }
  }
  @media screen and (max-width: 850px) {
    .aside-panel {
      display: none;
    }
  }
}
</style>
