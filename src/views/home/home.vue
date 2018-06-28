<template>
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="hearder-banner">
          <a href="https://bitcv.app/?from=bitcvCom" target="_blank"><img src="/static/img/banner_downapp.png" alt=""></a>
        </div>

        <div class="row">
          <h4 class="section-title">{{ $t('label.section_title') }}</h4>
          <div class="col-md-10 col-md-offset-1">
            <search-bar v-model="keywords" @submit="handleFilter"></search-bar>
          </div>
        </div>
        <h4 class="section-title">{{ $t('label.find_project') }}</h4>
        <div class="figure-group" v-loading="loading">
          <div class="row">
            <div class="col-md-2 col-xs-4" v-for="item in disList" :key="item.id">
              <router-link class="figure" :to="`/discover/detail/${item.id}`">
                <img class="img-rounded" :src="item.logoUrl">
                <p class="caption text-ellipsis">{{ item.tokenSymbol }}</p>
              </router-link>
            </div>
          </div>
        </div>
        <h4 class="section-title">{{ $t('label.pro_train') }}</h4>
        <div style="background-color: #fff;" v-loading="loading">
          <table class="table">
            <thead>
              <tr class="text-dark">
                <th style="width:50px;">&nbsp;</th>
                <th>{{ $t('label.pro_train_name') }}</th>
                <th>{{ $t('label.pro_train_type') }}</th>
                <th>{{ $t('label.pro_train_industry') }}</th>
                <th style="width:100px">{{ $t('label.pro_train_status') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in list" :key="item.id">
                <td class="text-center" v-if="language === 'cn'">
                  <!--<a href="javascript:;" :style="item.focusStatus ? 'color:#f10808;': 'color:#999'" class="text-dark" :title="['关注', '取消关注'][item.focusStatus]" @click="handleFav(item)">-->
                    <!--<i class="icon-bcv" :class="{'icon-heart': item.focusStatus == 0, 'icon-heart-fill': item.focusStatus == 1}"></i>-->
                  <!--</a>-->
                </td>
                <td class="text-center" v-else>
                  <!--<a href="javascript:;" :style="item.focusStatus ? 'color:#f10808;': 'color:#999'" class="text-dark" :title="['follow', 'unfollow'][item.focusStatus]" @click="handleFav(item)">-->
                    <!--<i class="icon-bcv" :class="{'icon-heart': item.focusStatus == 0, 'icon-heart-fill': item.focusStatus == 1}"></i>-->
                  <!--</a>-->
                </td>
                <td>
                  <router-link :to="`/discover/detail/${item.id}`">
                    <img :src="item.logoUrl" class="img-rounded" width="30">
                    <span>{{ item.nameCn }}</span>
                  </router-link>
                </td>
                <td><span class="text-primary">{{ item.tokenSymbol }}</span></td>
                <td>{{ item.buzType | buzType(language) }}</td>
                <td><span class="text-primary">{{ item.fundStage | fundStage(language) }}</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-4">
        <!-- <div class="panel panel-custom text-darker" style="min-height:150px;">
          <div class="panel-heading">
            <h4 class="panel-title">{{ $t('label.re_project') }}</h4>
          </div>
          <div class="panel-body" v-loading="loading">
            <p class="recommend" v-for="item in recommend" :key="item.id">
              <router-link :to="`/discover/detail/${item.id}`">
                <img :src="item.logoUrl" class="img-rounded" width="32">
                <span>{{ item.nameCn }}</span>
              </router-link>
            </p>
          </div>
        </div> -->

        <!-- 币威指数 BCI -->
        <div class="panel panel-custom text-darker bci" style="min-height:150px;">
          <div class="panel-heading clearfix">
            <h4 class="bci-title">{{ $t('label.bci') }}</h4>
            <p class="bci-time">{{ $t('label.last_update_time') }} {{ indexBcvUpdateTime }}</p>
          </div>
          <div class="panel-body">
            <div class="bci-list clearfix">
              <div class="bci-item-wrap">
                <!-- down:跌，up:涨 -->
                <div class="bci-item" :class='indexBcv.bcv30Trend'>
                  <h3 class="name">BCI 30</h3>
                  <p class="index">{{ indexBcv.bcv30 }}</p>
                  <p class="trend">{{ indexBcv.bcv30TrendPerc }}%</p>
                </div>
              </div>
              <div class="bci-item-wrap">
                <div class="bci-item" :class='indexBcv.bcv150Trend'>
                  <h3 class="name">BCI 150</h3>
                  <p class="index">{{ indexBcv.bcv150 }}</p>
                  <p class="trend">{{ indexBcv.bcv150TrendPerc }}%</p>
                </div>
              </div>
            </div>
            <router-link to="/discover/indexBcv" class="bci-intro">{{ $t('label.bci_more') }}<span class="icon"></span></router-link>
          </div>
        </div>

        <p style="margin-bottom:20px;">
          <router-link to="/apply" class="btn btn-primary btn-block btn-outline btn-lg btn-nocorner" style="line-height:2;">{{ $t('label.create_project') }}</router-link>
        </p>

        <!-- 链币说 -->
        <!--<div class="panel panel-custom text-darker" style="min-height:150px;">
          <div class="panel-heading">
            <h4 class="panel-title">lianbi说</h4>
          </div>
          <ul class="list-group">
            <router-link class="list-group-item" v-for="item in news" :key="item.id" :to="`/newdetail/${item.id}`">
              <p class="list-group-item-heading text-ellipsis">{{ item.title }}</p>
              <p class="list-group-item-text text-right text-dark">{{ item.releaseTime }}</p>
            </router-link>
          </ul>
        </div>-->
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions} from 'vuex'
import SearchBar from '@/components/search-bar'
import {getBuzType, getFundStage} from '@/utils/utils'
import moment from 'moment'

export default {
  components: {
    SearchBar
  },
  data () {
    return {
      keywords: '',
      list: [],
      news: [],
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
      loading: false
    }
  },
  computed: {
    disList () {
      let list = [].concat(this.list)

      return list.splice(0, 6)
    },
    recommend () {
      let list = [].concat(this.list)

      return list.splice(0, 3)
    },
    language () {
      return this.$i18n.locale
    },
    indexBcvUpdateTime () {
      if (this.indexBcv.createTimeStamp) {
        return moment(+this.indexBcv.createTimeStamp).format('MM-DD HH:mm')
      } else {
        return ''
      }
    }
  },
  filters: {
    buzType: getBuzType,
    fundStage: getFundStage
  },
  created () {
    this.loading = true
    this.getProList({
      pageno: 1,
      perpage: 10
    }).then((data = {}) => {
      this.list = [...data.projList]
      this.loading = false
    }).catch(() => {
      this.loading = false
    })

    this.getNewsList({
      pageno: 1,
      perpage: 10
    })
      .then((data = []) => {
        this.news = data
      })

    this.refreshIndexBcv()
    this.refreshTimer = setInterval(this.refreshIndexBcv, 300000)
  },
  beforeDestroy () {
    clearInterval(this.refreshTimer)
  },
  methods: {
    ...mapActions([
      'getProList',
      'getNewsList',
      'updateFocus',
      'getIndexBcv'
    ]),
    handleFilter () {
      this.$router.push({
        path: '/discover',
        query: {q: this.keywords}
      })
    },
    // 关注|取消关注
    handleFav (item) {
      this.updateFocus({projId: item.id})
        .then(data => {
          this.$set(item, 'focusStatus', item.focusStatus === 0 ? 1 : 0)
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
@import '~@/styles/variables';

.section-title {
  margin-top: 30px;
  margin-bottom: 20px;
  text-align: center;
}

.figure-group {
  padding: 20px 20px 0;
  background-color: #fff;

  .figure {
    display: block;
    margin-bottom: 20px;
    text-align: center;

    > img {
      width: 80px;
      height: 80px;
    }

    > .caption {
      display: block;
      margin-top: 10px;
      margin-bottom: 0;
      line-height: 1;
      color: $black-light;
    }
  }
}

.table > thead > tr > th {
  padding-top: 12px;
  padding-bottom: 12px;
  font-weight: normal;
  border-bottom: 1px solid #eee;
  color: #ccc;
}
.table > tbody > tr > td {
  vertical-align: middle;
  border-color: #eee;
}

.recommend {
  margin-bottom: 15px;
  > img {
    width: 32px;
    height: 32px;
    margin-right: 10px;
  }
}

// 币威指数
.bci {
  .bci-title {
    float: left;
    font-size: 18px;
    line-height: 1;
    color: #444;
    font-weight: bold;
  }
  .bci-time {
    padding-top: 12px;
    float: right;
    font-size: 14px;
    line-height: 1;
    color: #999;
  }
  .panel-body {
    padding: 0;
  }
  .bci-list {
  }
  .bci-item-wrap {
    float: left;
    width: 50%;
    &:first-child .bci-item {
      margin-left: 15px;
      margin-right: 6px;
    }
    &:last-child .bci-item {
      margin-right: 15px;
      margin-left: 6px;
    }
  }
  .bci-item {
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
  .bci-intro {
    position: relative;
    display: block;
    margin: 0;
    padding: 20px 0;
    border-top: solid 2px #EEE;
    text-align: center;
    font-size: 18px;
    line-height: 1;
    color: #666;
    .icon {
      display: inline-block;
      margin-left: 6px;
      margin-top: 2px;
      width: 9px;
      height: 13px;
      background-image: url('./arrow_right.png');
      background-size: 100% 100%;
    }
  }
}

.hearder-banner {
  margin-bottom: 50px;
  img {
    width: 100%;
  }
}
</style>
