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
          <img src="/static/img/logo.png" alt="">
        </div>
        <div class="abstract">
          <span>帝高阳之苗裔兮，朕皇考曰伯庸，摄提贞于孟陬兮，惟庚寅吾以降；皇览揆余初度兮，肇锡余以嘉名：名余曰正则兮，字余曰灵均。纷吾既有此内美兮，又重之以修能。 扈江离与辟芷兮，纫秋兰以为佩。 汨余若将不及兮，恐年岁之不吾与。 朝搴阰之木兰兮，夕揽洲之宿莽。 日月忽其不淹兮，春与秋其代序。</span>
        </div>
      </div>
      <div class="team-area">
        <el-carousel :interval="5000" arrow="always" height="378px">
          <el-carousel-item class="member" v-for="item in 4" :key="item">
            <img class="avatar" src="/static/img/logo.png" alt="">
            <span class="name">Allan</span>
            <span class="intro">帝高阳之苗裔兮，朕皇考曰伯庸，摄提贞于孟陬兮，惟庚寅吾以降；皇览揆余初度兮，肇锡余以嘉名：名余曰正则兮，字余曰灵均。纷吾既有此内美兮，又重之以修能。 扈江离与辟芷兮，纫秋兰以为佩。 汨余若将不及兮，恐年岁之不吾与。 朝搴阰之木兰兮，夕揽洲之宿莽。 日月忽其不淹兮，春与秋其代序。</span>
          </el-carousel-item>
        </el-carousel>
      </div>
      <div class="event-area">
        hello
      </div>
      <div class="partner-area">
        <h3 class="panel-title center-title">合作伙伴</h3>
        <div class="logo-box">
          <img v-for="item in 6" :key="item" src="/static/img/logo.png" alt="">
        </div>
      </div>
      <div class="media-area">
        <h3 class="center-title panel-title">媒体报道</h3>
        <div class="item-box">
          <div v-for="item in 3" :key="item" class="item">
            <div class="image-area">
              <img class="log" src="/static/img/logo.png" alt="">
            </div>
            <span class="content">媒体对这个项目的评价非常不错。</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {}
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
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-content: space-between;
      align-items: center;
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
          width: 100%;
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
