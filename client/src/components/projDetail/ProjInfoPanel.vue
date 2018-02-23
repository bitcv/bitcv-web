<template>
  <div class="proj-info">
    <div id="shareBox" v-show="showShare" class="share-container" @click="showShare = false">
      <share id="shareCard" :proj-detail="projDetail"></share>
    </div>
    <div id="infoBox" class="info-box">
      <img :src="projDetail.logoUrl" alt="">
      <div class="text-box">
        <div class="top-row">
          <span class="name-cn">{{ projDetail.nameCn }}</span>
          <span class="name-en">({{ projDetail.nameEn }})</span>
        </div>
        <div class="bottom-row">
          <span class="text-title">项目简称：</span>
          <span class="text">{{ projDetail.tokenName }}</span>
          <span class="text-title">项目符号：</span>
          <span class="text">{{ projDetail.tokenSymbol }}</span>
        </div>
      </div>
    </div>
    <div class="btn-box">
        <a :href="projDetail.homeUrl" target="_blank">
          <div class="white-paper">项目主页</div>
        </a>
        <a :href="projDetail.whitePaperUrl" target="_blank">
          <div class="white-paper">白皮书</div>
        </a>
        <div :class="{ focused: projDetail.focusStatus }" @click="toggleFocus()">
          {{ projDetail.focusStatus ? '取消关注' : '关注项目' }}
        </div>
    </div>
    <div class="tag-box">
      <span class="title">标签</span>
      <span class="tag" v-for="(tag, index) in projDetail.tagList" :key="index">{{ tag }}</span>
    </div>
    <div class="social-box">
      <span class="title">社群：</span>
      <a :href="social.linkUrl" v-for="(social, index) in projDetail.socialList" :key="index" target="_blank">
        <i class="fab" :class="social.fontClass"></i>
      </a>
    </div>
    <div class="share-box" @click="openShare">
      <span class="title">分享</span>
      <img src="/static/logo/share.png" alt="">
    </div>
  </div>
</template>

<script>
import Share from '@/components/share/Share'
import html2canvas from 'html2canvas'

export default {
  props: {
    projDetail: Object
  },
  data () {
    return {
      showShare: false,
      shareUrl: ''
    }
  },
  methods: {
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
  },
  components: {
    Share
  }
}
</script>

<style lang="scss" scoped>
.proj-info {
  box-sizing: border-box;
  width: 100%;
  padding: 54px 41px 0 22px;
  background-color: #FFF;
  position: relative;
  .btn-box {
    position: absolute;
    top: 50px;
    right: 41px;
    div {
      width: 111px;
      height: 28px;
      margin-bottom: 10px;
      line-height: 28px;
      text-align: center;
      font-size: 14px;
      color: #FFCF81;
      background-color: #000;
      border-radius: 2px;
      border: 1px solid #000;
      cursor: pointer;
    }
    div.focused {
      background-color: #FFF;
      color: #9B9B9B;
    }
  }
  .info-box {
    img {
      width: 80px;
      height: 80px;
      margin-right: 24px;
    }
    .text-box {
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
    a {
      margin-right: 10px;
      font-size: 24px;
      color: #9B9B9B;
      &:hover {
        color: #F5A623;
      }
    }
    img {
      width: 26px;
      height: 26px;
      margin: 0 5px;
    }
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
}
</style>
