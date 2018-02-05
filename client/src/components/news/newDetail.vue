<template>
  <div class = "main-panel">
      <span class = "new-title">{{"比特币价格失守9000美元 多国监管风向突变"}}</span>
      <span class = "new-time">{{"近期,比特币可谓是“跌跌不休”。本周二,比特币价格跌幅逾10%,失守10000美元大关。2月2日,据韩国虚拟货币交易平台Bithumb显示,比特币价格已跌至8247美元。"}}</span>
      <span class = "new-content">{{"2018-02-01"}}</span>
  </div>
</template>
<script>
export default {
  data () {
    return {
    }
  }
}
</script>
<style lang="scss" scoped>
.main-panel{
  width: 826px;
  height: 1330px;
  text-align: center;
  background:rgba(255,255,255,1);
  border-radius: 4px;
  display: inline-flex;
//   justify-content: space-between;
  flex-direction: column;
  .new-title{
    width:593px;
    height:33px;
    text-align: center;
    font-size:24px;
    margin-left: 117px;
    margin-top: 52px;
    font-family:PingFangSC-Medium;
    color:rgba(74,74,74,1);
    line-height:33px;
  }
  .new-time{
    width:82px;
    height:20px;
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
    margin-left: 20px;
  }
}
</style>
