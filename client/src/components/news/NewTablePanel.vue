<template>
<div class = "new-area">
  <a v-for="(news, index) in newList" :key="index">
  <div class = "new-table-panel">
    <router-link :to="{ path: 'newdetail/' + news.id}">
    <img :src="news.bannerUrl" alt="">
      <div class = "content">
        <span class="content-title">{{news.title}}</span>
        <!-- <span class="content-text">{{news.content}}</span> -->
        <span class = "content-text" v-html="news.content"></span>
        <span class="content-time">{{news.releaseTime}}</span>
      </div>
    </router-link>
  </div>
  </a>
</div>
</template>
<script>
export default {
  data () {
    return {
      newList: []
    }
  },
  mounted () {
    var that = this
    this.$http.post('api/getNewsList', {
      pageno: 1,
      perpage: 10
    }).then(function (res) {
      var resdata = res.data
      console.log(resdata)
      if (resdata.errcode === 0) {
        that.newList = resdata.data
      }
    })
  }
}
</script>

<style lang="scss" scoped>
.new-area{
  div {
    margin-bottom: 24px;
  }
  .new-table-panel{
    width: 826px;
    height: 190px;
    background: rgba(255,255,255,1);
    img{
      width: 219px;
      height: 150px;
      margin: 20px 20px 10px 10px;
    }
    .content{
      //margin-left: 2px;
      vertical-align: top;
      display: inline-flex;
      justify-content: space-between;
      flex-direction: column;
      .content-title{
        width: 540px;
        height: 16px;
        margin-top: 27px;
        font-size: 14px;
        float: left;
        font-family: PingFangSC-Regular;
        color: rgba(245,166,35,1);
        line-height: 28px;
      }
      .content-text{
        width: 540px;
        height: 40px;
        font-size: 14px;
        margin-top: 20px;
        font-family: PingFangSC-Medium;
        color: rgba(155,155,155,1);
        line-height: 20px;
        word-break: break-all;
        text-overflow: ellipsis;
        display: -webkit-box; /** 对象作为伸缩盒子模型显示 **/
        -webkit-box-orient: vertical; /** 设置或检索伸缩盒对象的子元素的排列方式 **/
        -webkit-line-clamp: 2; /** 显示的行数 **/
        overflow: hidden;  /** 隐藏超出的内容 **/
      }
      .content-time{
        width: 170px;
        height: 57px;
        font-size: 12px;
        font-family: PingFangSC-Regular;
        color: rgba(155,155,155,1);
        line-height: 17px;
        margin-top: 40px;
      }
    }
  }
}
</style>
