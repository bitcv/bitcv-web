<template>
  <div class="footer">
    <div class="content">
      <div class="icon-box">
        <img src="/static/img/webchat-2x.png" alt="">
        <img src="/static/img/blog-2x.png" alt="">
      </div>
      <div class="name">
        <span class="name">LIANBI</span>
      </div>
      <div class="addr">
        <span>联系地址：北京石景山实兴大街苹果园街道朗诚大厦二楼</span>
      </div>
      <div class="right">
        <span>@storiqa.2018 保留所有权利</span>
        <span>收集到的信息有助于展示个性化广告，为客户提供服务，并接受市场的统计数据。继续浏览即表示同意使用cookie</span>
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
.footer {
  display: flex;
  justify-content: center;
  background-color: #FFF;
  .content {
    width: 1200px;
    height: 222px;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 26px 0 17px;
    box-sizing: border-box;
    .icon-box {
      position: absolute;
      right: 0;
      top: 24px;
      color: #D8D8D8;
      img {
        width: 51px;
        height: 51px;
        margin-left: 20px;
      }
    }
    .name {
      font-size: 30px;
      line-height: 42px;
      color: #F5A623;
    }
    .addr {
      font-size: 16px;
      line-height: 22px;
      color: #9B9B9B;
    }
    .right {
      font-size: 14px;
      line-height: 20px;
      color: #9B9B9B;
    }
  }
}
</style>
