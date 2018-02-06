<template>
  <!-- <div class = "main-panel"> -->
    <!-- <span class = "new-title">{{"比特币价格失守9000美元 多国监管风向突变"}}</span> -->
    <!-- <span class = "new-time">{{"2018-02-02"}}</span> -->
    <!-- <span class = "new-content">{{"近期,比特币可谓是“跌跌不休”。本周二,比特币价格跌幅逾10%,失守10000美元大关。2月2日,据韩国虚拟货币交易平台Bithumb显示,比特币价格已跌至8247美元。2月2日,据韩国虚拟货币交易平台Bithumb显示,比特币价格已跌至8247美元。"}}</span> -->
  <!-- </div> -->
  <div class = "new-list">
    <div class="main-panel">
      <span class = "new-title">{{"比特币价格失守9000美元 多国监管风向突变"}}</span>
      <span class = "new-time">{{"2018-02-02"}}</span>
      <span class = "new-content">{{"近期,比特币可谓是“跌跌不休”。本周二,比特币价格跌幅逾10%,失守10000美元大关。2月2日,据韩国虚拟货币交易平台Bithumb显示,比特币价格已跌至8247美元。2月2日,据韩国虚拟货币交易平台Bithumb显示,比特币价格已跌至8247美元。"}}</span>
    </div>
    <div class="aside-panel">
      <div class="new-column">
        <h3>看资讯，看项目</h3>
      </div>
      <top-list-panel v-for="item in topList" :key="item.type" :list-data="item"></top-list-panel>
    </div>
  </div>
</template>
<script>
import TopListPanel from '@/components/projList/TopListPanel'
export default {
  data () {
    return {
      topList: [
        { title: '关注TOP10', type: 'focus' },
        { title: '浏览TOP10', type: 'view' }
      ],
      newdetail: {}
    }
  },
  mounted () {
    var id = this.$route.params.id
    this.$http.post('api/getNewsDetail/', {
      id: this.id
    }).then(function (res) {
      var resdata = res.data
      if (resData.errcode === 0) {
        this.newdetail = resdata.data
      } else {
        alert(resData.errmsg)
      }
    })
  },
  components: {
    TopListPanel
  }
}
</script>
<style lang="scss" scoped>
.new-list{
  width: 1200px;
  max-width: 100%;
  display: flex;
  justify-content: space-between;
  .main-panel{
    //flex: 1 1 auto;
    //min-width: 826px;
    width: 826px;
    height: 1330px;
    // margin-left: 13px;
    text-align: center;
    background:rgba(255,255,255,1);
    border-radius: 4px;
    display: inline-flex;
    //   justify-content: space-between;
    flex-direction: column;
    .new-title{
    width:593px;
    height:40px;
    text-align: center;
    font-size:24px;
    margin-left: 117px;
    margin-top: 52px;
    margin-right: 116px;
    font-family:PingFangSC-Medium;
    color:rgba(74,74,74,1);
    line-height:33px;
    }
    .new-time{
    width:82px;
    height:50px;
    font-size:14px;
    margin-left: 371px;
    text-align: center;
    font-family:PingFangSC-Medium;
    color:rgba(155,155,155,1);
    line-height:20px;
    }
    .new-content{
    width:693px;
    height:1064px;
    font-size:14px;
    text-align: center;
    font-family:PingFangSC-Regular;
    color:rgba(74,74,74,1);
    line-height:28px;
    margin-left: 60px;
    margin-right: 173px;
    }
  }
  .aside-panel{
    flex-shrink: 0;
    width: 350px;
    margin-left: 24px;
    margin-right: 83px;
    div {
      margin-bottom: 24px;
    }
    .new-column{
      width:350px;
      height:135px;
      background:rgba(255,255,255,1);
      border-radius: 4px;
      h3 {
        width:140px;
        height:28px;
        margin: 0px auto;
        position: relative;
        top: 40%;
        //margin-left: 122px;
        -webkit-margin-top-collapse: 51px;
        font-size:20px;
        font-family:STBaoliSC-Regular;
        color:rgba(74,74,74,1);
        line-height:28px;
      }
    }
  }
}
</style>
