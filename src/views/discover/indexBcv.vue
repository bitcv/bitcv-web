<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="indexbcv">
          <div class="indexbcv-item">
            <h2 class="title">{{ $t('label.bci') }}</h2>

            <div class="row">
              <div class="col-md-4">
                <!-- down:跌，up:涨 -->
                <div class="bci-item" :class='indexBcv.bcv30Trend'>
                  <h3 class="name">BCI 30</h3>
                  <p class="index">{{ indexBcv.bcv30 }}</p>
                  <p class="trend">{{ indexBcv.bcv30TrendPerc }}%</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="bci-item" :class='indexBcv.bcv150Trend'>
                  <h3 class="name">BCI 150</h3>
                  <p class="index">{{ indexBcv.bcv150 }}</p>
                  <p class="trend">{{ indexBcv.bcv150TrendPerc }}%</p>
                </div>
              </div>
            </div>
          </div>

          <div class="indexbcv-item">
            <h2 class="title">{{ $t('label.bci_intro') }}</h2>
            <div v-if="language == 'cn'" class="intro-wrap">
              <p class="intro">币威30指数和币威150指数（合称币威指数，英文名称Bit Capital Index，BCI）是Bit Capital Vendor于2018年4月发布的反应数字货币市场全貌的一组指数。币威指数使用客观科学的方法，在剔除稳定币后，选取主流交易所上市值最高，流动性最好的数字货币，使用市值加权指数的计算方式，显示交易性数字货币整体市值变化情况，在计算时不人为指定成分币权数，真实反应数字货币市场全貌。</p>
              <p class="intro">币威30指数是数字货币市场上市值最高，流动性最好的 30 只数字货币，其市值占数字货币市场总市值的 90% 左右，包含被广泛认可的公链币，拥有技术创新的区块链应用代币等最主流的 30 只数字货币，能够反映投资者整体收益情况和投资者的市场预期。</p>
              <p class="intro">币威150指数是市值最高，流动性最好的 180 只货币的后 150 只币，这些币的币值占数字货币市场总市值的 6% 到 8%，显示应用币等小币的整体行情。</p>
              <p class="intro">币威指数在币威官网 http://bitcv.com/ 发布，每五分钟更新一次，每过一个月调整一次成分币以及成分币的自由流通量，以流通市值和流动性为调整标准，同时筛除稳定币成分，定位于交易性成分指数。在成分币调整时采用缓冲区技术，保证成分币的稳定性。</p>
            </div>
            <div v-else-if="language == 'en'" class="intro-wrap">
              <p class="intro">The Bit Capital Index 30 and the Bit Capital Index 150 (known as the BCI) are a set of indexes released by Bit Capital Vendor in April 2018 to reflect the overall picture of the cryptocurrency exchange market. The BCV group uses an objective and scientific method to calculate BCI. After eliminating stable currencies, we select the cryptocurrency with the largest market cap and the best liquidity in qualified exchanges and uses the calculation method of the market-valued weighted index to show the changes of the overall market value. We do not artificially specify the weights of any cryptocurrency so that the BCI can truly reflect the market condition.</p>
              <p class="intro">The constituents of BCI 30 are 30 cryptocurrencies with the highest listing value and the best liquidity in the digital currency market. Their market value accounts for about 90% of the total market value. It contains widely recognized public chain tokens or tokens of technological innovation projects. Contained the 30 most popular cryptocurrency, BCI 30 can reflect the overall income situation of investors and investors' market expectations.</p>
              <p class="intro">The constituents of BCI 150 are 150 lowest-listing-value cryptocurrencies in 180 cryptocurrencies with highest market value and the best liquidity. The listing value of these cryptocurrencies accounts for 6% to 8% of the total market value, showing the overall performance of small market cap cryptocurrencies.</p>
              <p class="intro">The Bit Capital Indexes are published on the website http://bitcv.com/ and are updated every five minutes. The free circulation of constituent cryptocurrencies and constituent currency list will be adjusted every month. Also, a Buffer operation is used to adjust the component currency to ensure the stability of the index.</p>
            </div>
            <div v-else class="intro-wrap">
              <p class="intro">
                BCI30とBCI150（BCI指数：Bit Capital Index）とは、Bit Capital Vendorが2018年4月にリリースした仮想通貨の市場状況を反映する指数です。BCI指数は客観的で科学的な算定方法を使っています。安定した通貨を取り除き、取引所で時価・流通性の高い通貨を選定し、時価総額加重平均方式で指数を算出しています。人為的に通貨のウェイトを調整しないため、市場状況をリアルタイムで反映できます。
              </p>
              <p class="intro">
                BCI30とは、仮想通貨マーケットで時価総額90％の上位30種を指しています。
              </p>
              <p class="intro">
                BCI150とは、時価・流通性上位30位〜180位の通貨を指しており、時価総額の6％を占めています。
              </p>
              <p class="intro">
                BCI指数はBCVのホームページ http://bitcv.com/ でリアルタイムで更新されています（更新間隔：5分）。また、月一回通貨構成リストが調整されています（時価と流通性を基準）。
              </p>
            </div>
          </div>

          <div class="indexbcv-item">
            <h2 class="title">{{ $t('label.bci30_ctn') }}</h2>

            <ul class="token-list clearfix">
              <li v-for='item in bci30_ctn' :key='item' class="token-item" @click="redirect(item)">{{ item }}</li>
            </ul>
          </div>

          <div class="indexbcv-item">
            <h2 class="title">{{ $t('label.bci150_ctn') }}</h2>

            <ul class="token-list clearfix">
              <li v-for='item in bci150_ctn' :key='item' class="token-item" @click="redirect(item)">{{ item }}</li>
            </ul>
          </div>

          <div class="indexbcv-item">
            <h2 class="title">{{ $t('label.bciReserve') }}</h2>

            <ul class="token-list clearfix">
              <li v-for='item in bciReserve' :key='item' class="token-item">{{ item }}</li>
            </ul>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions} from 'vuex'

export default {
  data () {
    return {
      indexBcv: {
        bcv30: 1000,
        bcv150: 1000,
        bcv30TrendPerc: 0.00,
        bcv30Trend: 'up',
        bcv150TrendPerc: 0.00,
        bcv150Trend: 'up',
        createTimeStamp: ''
      }, // 币威指数
      refreshTimer: null,
      loading: false,
      bci30_ctn: [], // BCI 30 成分币列表
      bci150_ctn: [], // BCI 150 成分币列表
      bciReserve: [] // 备选币列表
    }
  },
  computed: {
    language () {
      return this.$i18n.locale
    }
  },
  created () {
    this.refreshIndexBcv()
    this.refreshTimer = setInterval(this.refreshIndexBcv, 300000)

    // 更新成分币列表
    this.getIndexCoins()
      .then((data = {}) => {
        this.bci30_ctn = data.bcv30
        this.bci150_ctn = data.bcv150
        this.bciReserve = data.remain
      })
  },
  beforeDestroy () {
    clearInterval(this.refreshTimer)
  },
  methods: {
    ...mapActions([
      'getIndexBcv', 'toProject', 'getIndexCoins'
    ]),
    redirect (symbol) {
      this.toProject({
        symbol: symbol
      }).then((data) => {
        let projId = data.projId
        if (projId) {
          this.$router.push('/discover/detail/' + projId)
        }
      })
    },
    refreshIndexBcv () {
      // 获取币威指数
      this.getIndexBcv()
        .then((data = {}) => {
          // 计算币威指数趋势和百分比
          let bcv30TrendPerc = this.accounting.toFixed((data.bcv30 - data.preOneDayBcv30) / data.preOneDayBcv30 * 100, 2)
          if (bcv30TrendPerc >= 0) {
            data.bcv30TrendPerc = `+${bcv30TrendPerc}`
            data.bcv30Trend = 'up'
          } else {
            data.bcv30TrendPerc = `${bcv30TrendPerc}`
            data.bcv30Trend = 'down'
          }

          let bcv150TrendPerc = this.accounting.toFixed((data.bcv150 - data.preOneDayBcv150) / data.preOneDayBcv150 * 100, 2)
          if (bcv150TrendPerc >= 0) {
            data.bcv150TrendPerc = `+${bcv150TrendPerc}`
            data.bcv150Trend = 'up'
          } else {
            data.bcv150TrendPerc = `${bcv150TrendPerc}`
            data.bcv150Trend = 'down'
          }
          this.indexBcv = data
        })
    }
  }
}
</script>

<style lang="scss" scoped>
// 币威指数
.indexbcv {
  padding: 0 40px 44px;
  background-color: #fff;
  .indexbcv-item {
    padding-top: 44px;
    .title {
      position: relative;
      margin: 0;
      font-size: 24px;
      color: #333;
      line-height: 24px;
      &:after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: -18px;
        width: 4px;
        background-color: #FBB673;
      }
    }
  }
  .bci-item {
    margin-top: 22px;
    margin-bottom: 22px;
    border-radius: 5px;
    padding-bottom: 20px;
    background-color: #fff;
    box-shadow: 0 0 18px RGBA(0, 0, 0, 0.06);
    text-align: center;
    .name {
      margin: 0;
      padding-top: 20px;
      font-size: 14px;
      line-height: 1;
      color: #333;
      font-weight: 500;
    }
    .index {
      margin: 0;
      padding-top: 10px;
      padding-bottom: 14px;
      font-size: 32px;
      line-height: 1;
      color: #FC0D1C;
      font-weight: bold;
    }
    .trend {
      display: inline-block;
      margin: 0;
      padding: 0 9px;
      height: 24px;
      border-radius: 12px;
      background-color: #FC0D1C;
      color: #fff;
      font-size: 14px;
      line-height: 24px;
    }
    &.up {
      .index {
        color: #FC0D1C;
      }
      .trend {
        background-color: #fd4551;
      }
    }
    &.down {
      .index {
        color: #13a518;
      }
      .trend {
        background-color: #41b245;
      }
    }
  }

  .intro-wrap {
    margin-top: 22px;
    .intro {
      margin-top: 10px;
      font-size: 14px;
      line-height: 24px;
      color: #666;
      text-indent: 2em;
    }
  }

  .token-list {
    margin-top: 20px;
    .token-item {
      cursor: pointer;
      box-sizing: border-box;
      margin: 4px 8px;
      float: left;
      height: 28px;
      width: 80px;
      text-align: center;
      font-size: 14px;
      line-height: 28px;
      border: solid 1px #ddd;
      border-radius: 14px;
      background-color: #f5f5f5;
      transition: all .2s;
      &:hover {
        color: #fff;
        background-color: #fdb76e;
        border-color: #fdb76e;
      }
    }
  }
}
</style>
