<template>
  <div class="share">
    <img src="/static/img/Group 3@2x.png" alt="" class="share-img">
    <div class="share-content">
      <div class="logo"><img src="" alt=""></div>
      <div class="share-content-container">
        <div class="share-content-item">
          <span class="title">项目名称：</span>
          <span class="content">BitCapitalVendor 区块链数字资产管理服务引擎白皮书</span>
        </div>
        <div class="share-content-item">
          <span class="title">代币符号：</span>
          <span class="content">BCV</span>
        </div>
        <div class="share-content-item">
          <span class="title">所属行业：</span>
          <span class="content">数字资管平台</span>
        </div>
        <div class="share-content-item">
          <span class="title">项目介绍：</span>
          <span class="multi-content">区块链数字资产配置已经从早期极客，发展到今天公众投资人初步了解并参与，经历了巨大发展，而我们却看到在区块链数字资产投资配置以及各个服务环节中，存在大量的问题和不足，甚至有的与区块链的思想背道而驰。区块链追求的是去中介和透明化，而在数字资产的早期投资过程中，我们所见到大量依赖中介、依赖人、依赖消息源、不透明等的行为，也暗含着大量风险，而在后期的资产配置上面，更是一片空白。面对未来可能高达数 10 万亿美元的数字资产市场，必然存在数据资产管理的极大价值，交易、支付之外的资产管理和标准化服务过程的需求也将爆发，以能促进更好的行业生态。</span>
        </div>
      </div>
      <div class="more-info">（登陆<a class="link" href="lianbi.io">lianbi.io</a>获取更多项目详细信息）</div>
    </div>
    <img src="/static/img/Group 5@2x.png" alt="" class="share-img">
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
  .share {
    margin: -40px 0 -76px;
    .share-content {
      padding: 0 29px;
      .logo {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 17px auto;
        width: 50px;
        height: 50px;
        background-color: black;
        img {
          max-width: 100%;
          max-height: 100%;
        }
      }
      .share-content-container {
        margin-top: 15px;
      }
      .share-content-item {
        display: flex;
        margin-top: 3px;
        line-height: 16px;
        &:nth-child(1) {
          margin-top: 0;
        }
        .title {
          flex-shrink: 0;
          font-size: 8px;
          font-weight: 550;
          color: #F5A623;
        }
        .content {
          color: #4A4A4A;
          font-size: 9px;
          font-weight: 550;
        }
        .multi-content {
          color: #4A4A4A;
          font-size: 9px;
        }
      }
      .more-info {
        font-size: 7px;
        margin: 21px 0 18px;
        text-align: center;
        .link {
          color: #F5A623;
        }
      }
    }
    .share-img {
      width: 100%;
    }
  }
</style>
