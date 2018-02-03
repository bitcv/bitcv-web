<template>
  <div class = "new-table-panel">
    <img src="/static/img/bitcoin.jpeg" alt="">
      <div class = "content">
        <span class="content-title">{{"比特币价格失守9000美元 多国监管风向突变"}}</span>
        <span class="content-text">{{"近期,比特币可谓是“跌跌不休”。本周二,比特币价格跌幅逾10%,失守10000美元大关。2月2日,据韩国虚拟货币交易平台Bithumb显示,比特币价格已跌至8247美元。"}}</span>
        <span class="content-time">{{"2018-02-01"}}</span>
      </div>
  </div>
</template>
<script>
export default {
}
</script>

<style lang="scss" scoped>
.new-table-panel{
  width: 826px;
  height: 190px;
  background: rgba(255,255,255,1);
  img{
    width: 219px;
    height: 150px;
    margin: 20px 20px 20px 10px;
  }
  .content{
    margin-left: 2px;
    vertical-align: top;
    display: inline-flex;
    justify-content: space-between;
    flex-direction: column;
    .content-title{
      width: 540px;
      height: 16px;
      margin-top: 27px;
      font-size: 20px;
      font-family: PingFangSC-Regular;
      color: rgba(245,166,35,1);
      line-height: 28px;
    }
    .content-text{
      width: 540px;
      height: 90px;
      font-size: 14px;
      margin-top: 20px;
      font-family: PingFangSC-Medium;
      color: rgba(155,155,155,1);
      line-height: 20px;
    }
    .content-time{
      width: 70px;
      height: 17px;
      font-size: 12px;
      font-family: PingFangSC-Regular;
      color: rgba(155,155,155,1);
      line-height: 17px;
    }
  }
}
</style>
