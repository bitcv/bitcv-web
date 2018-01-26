<template>
  <div class="proj-detail">
    <div class="main-panel">
      <proj-info-panel></proj-info-panel>
      <proj-detail-panel></proj-detail-panel>
    </div>
    <div class="aside-panel">
      <div class="record-panel">
        <div class="num-box">
          <div class="view"><span>398</span></div>
          <div class="focus"><span>2</span></div>
        </div>
      </div>
      <div class="btn-panel">
        <h3 class="center-title panel-title">认领该公司</h3>
        <img src="/static/img/logo.png" alt="">
      </div>
      <div class="contact-panel">
        <h3 class="center-title panel-title">公司联系信息</h3>
        <div class="info-row">
          <img src="/static/img/logo.png" alt="">
          <span class="text">18392357025</span>
        </div>
        <div class="info-row">
          <img src="/static/img/logo.png" alt="">
          <span class="text">3252352@ucai.com</span>
        </div>
        <div class="info-row">
          <img src="/static/img/logo.png" alt="">
          <span class="text">北京中关村</span>
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
    return {}
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
  display: flex;
  justify-content: space-between;
  .main-panel {
    width: 826px;
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
        img {
          width: 17px;
          height: 17px;
        }
        .text {
          font-size: 18px;
          color: #4A4A4A;
        }
      }
    }
  }
}
</style>
