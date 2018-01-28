<template>
  <div class="proj-detail">
    <div class="main-panel">
      <proj-info-panel :proj-detail="projDetail"></proj-info-panel>
      <proj-detail-panel :proj-detail="projDetail"></proj-detail-panel>
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
            <img src="/static/img/心@2x.png" alt="">
            <span>关注</span>
          </div>
        </div>
      </div>
      <div class="btn-panel">
        <h3 class="center-title panel-title">认领该公司</h3>
        <img src="/static/img/头像扫描@2x.png" alt="">
      </div>
      <div class="contact-panel">
        <h3 class="center-title panel-title">公司联系信息</h3>
        <div class="info-row">
          <img src="/static/img/tel@2x.png" alt="">
          <span class="text">{{ projDetail.companyTel }}</span>
        </div>
        <div class="info-row">
          <img src="/static/img/email@2x.png" alt="">
          <span class="text">{{ projDetail.companyEmail }}</span>
        </div>
        <div class="info-row">
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
      projDetail: {
        nameCn: '',
        nameEn: '',
        logoUrl: '',
        tokenName: '',
        tokenSymbol: '',
        tagList: [],
        partnerList: [],
        whitePaperUrl: '',
        abstract: '',
        memberList: [],
        mediaList: [],
        bannerUrl: '',
        companyTel: '',
        companyAddr: '',
        companyEmail: '',
        viewTimes: 0,
        focusNum: 0
      }
    }
  },
  mounted () {
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
    min-width: 500px;
    div {
      margin-bottom: 24px;
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
}
</style>
