<template>
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <h4 class="section-title">更多的机构，遇见更多的财富</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="row">
          <div class="col-md-10 col-md-offset-1">
            <search-bar v-model="keywords" @submit="handleFilter"></search-bar>
          </div>
        </div>

        <h4 class="section-title">发现新项目</h4>
        <div class="figure-group">
          <div class="row">
            <div class="col-md-2 col-xs-4" v-for="item in disList" :key="item.id">
              <router-link class="figure" :to="`/discover/detail/${item.id}`">
                <img class="img-rounded" :src="item.logoUrl">
                <p class="caption text-ellipsis">{{ item.tokenSymbol }}</p>
              </router-link>
            </div>
          </div>
        </div>

        <h4 class="section-title">项目直通车</h4>
        <div style="background-color: #fff;">
          <table class="table">
            <thead>
              <tr class="text-dark">
                <th style="width:50px;">&nbsp;</th>
                <th>公司名称</th>
                <th>代币符号</th>
                <th>所属行业</th>
                <th style="width:100px">融资状态</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in list" :key="item.id">
                <td class="text-center">
                  <a href="javascript:;" :style="item.focusStatus ? 'color:#f10808;': 'color:#999'" class="text-dark" :title="['关注', '取消关注'][item.focusStatus]" @click="handleFav(item)">
                    <i class="icon-bcv" :class="{'icon-heart': item.focusStatus == 0, 'icon-heart-fill': item.focusStatus == 1}"></i>
                  </a>
                </td>
                <td>
                  <router-link :to="`/discover/detail/${item.id}`">
                    <img :src="item.logoUrl" class="img-rounded" width="30">
                    <span>{{ item.nameCn }}</span>
                  </router-link>
                </td>
                <td><span class="text-primary">{{ item.tokenSymbol }}</span></td>
                <td>{{ item.buzType | buzType }}</td>
                <td><span class="text-primary">{{ item.fundStage | fundStage }}</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-custom text-darker" style="min-height:150px;">
          <div class="panel-heading">
            <h4 class="panel-title">推荐项目</h4>
          </div>
          <div class="panel-body">
            <p class="recommend" v-for="item in recommend" :key="item.id">
              <router-link :to="`/discover/detail/${item.id}`">
                <img :src="item.logoUrl" class="img-rounded" width="32">
                <span>{{ item.nameCn }}</span>
              </router-link>
            </p>
          </div>
        </div>
        <p style="margin-bottom:20px;">
          <router-link to="/apply" class="btn btn-primary btn-block btn-outline btn-lg btn-nocorner" style="line-height:2;">立即创建项目</router-link>
        </p>
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

export default {
  components: {
    SearchBar
  },
  data () {
    return {
      keywords: '',
      list: [],
      news: []
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
    }
  },
  filters: {
    buzType: getBuzType,
    fundStage: getFundStage
  },
  created () {
    this.getProList({
      pageno: 1,
      perpage: 10
    })
      .then((data = {}) => {
        this.list = [...data.projList]
      })

    this.getNewsList({
      pageno: 1,
      perpage: 10
    })
      .then((data = []) => {
        this.news = data
      })
  },
  methods: {
    ...mapActions([
      'getProList',
      'getNewsList',
      'updateFocus'
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
</style>
