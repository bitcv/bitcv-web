<template>
  <div class="container">
    <div class="row" style="margin-bottom:20px">
      <div class="col-md-8">
        <search-bar v-model="keywords" @submit="onSearch"></search-bar>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="panel panel-custom">
          <div class="panel-body filter-list">
            <dl class="dl-horizontal">
              <dt>{{ region.label }}</dt>
              <dd>
                <a href="javascript:;"
                  v-for="item in region.optionList"
                  :key="item.value"
                  :class="{active: region.default == item.value}"
                  @click="onFilterClick(region, item.value)"
                >{{ item.label }}</a>
              </dd>
            </dl>
            <dl class="dl-horizontal">
              <dt>{{ buzType.label }}</dt>
              <dd>
                <a href="javascript:;"
                  v-for="item in buzType.optionList"
                  :key="item.value"
                  :class="{active: buzType.default == item.value}"
                  @click="onFilterClick(buzType, item.value)"
                >{{ item.label }}</a>
              </dd>
            </dl>
            <dl class="dl-horizontal">
              <dt>{{ stage.label }}</dt>
              <dd>
                <a href="javascript:;"
                  v-for="item in stage.optionList"
                  :key="item.value"
                  :class="{active: stage.default == item.value}"
                  @click="onFilterClick(stage, item.value)"
                >{{ item.label }}</a>
              </dd>
            </dl>
          </div>
        </div>
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
                  <a href="javascript:;" class="text-dark" :title="['关注', '取消关注'][item.focusStatus]" @click="handleFav(item)">
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
        <div class="text-right">
          <span class="pull-left" style="line-height:2">共{{ total }}项</span>
          <pagination :total="total" :current-page="currentPage" @onPageClick="onPageClick"></pagination>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-custom text-darker" style="min-height:200px;">
          <div class="panel-heading">
            <h4 class="panel-title">关注TOP10</h4>
          </div>
          <div class="list-group list-counter">
            <router-link class="list-group-item" v-for="item in focusList" :to="`/discover/detail/${item.projId}`" :key="item.projId">
              <span>{{ item.nameCn }}</span>
              <span class="count">{{ item.count }}</span>
            </router-link>
          </div>
        </div>
        <div class="panel panel-custom text-darker" style="min-height:200px;">
          <div class="panel-heading">
            <h4 class="panel-title">浏览TOP10</h4>
          </div>
          <div class="list-group list-counter">
            <router-link class="list-group-item" v-for="item in viewList" :to="`/discover/detail/${item.projId}`" :key="item.projId">
              <span>{{ item.nameCn }}</span>
              <span class="count">{{ item.count }}</span>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex'
import SearchBar from '@/components/search-bar'
import Pagination from '@/components/pagination'
import {getBuzType, getFundStage} from '@/utils/utils'

export default {
  components: {
    SearchBar,
    Pagination
  },
  data () {
    return {
      keywords: '',
      buzType: {},
      region: {},
      stage: {},
      total: 0,
      list: [],
      focusList: [],
      viewList: [],
      currentPage: 1
    }
  },
  computed: {
    ...mapState({
      query: state => state.route.query
    })
  },
  filters: {
    buzType: getBuzType,
    fundStage: getFundStage
  },
  watch: {
    query: {
      handler ({q}) {
        if (q) {
          this.keywords = q
          this.onSearch()
        }
      },
      immediate: true,
      deep: true
    }
  },
  created () {
    this.getFilterParams()
      .then((data = {}) => {
        const {
          stage = {},
          region = {},
          buzType = {}
        } = data

        this.buzType = buzType
        this.region = region
        this.stage = stage
      })

    // 关注top10
    this.getTop10({type: 'focus', count: 10})
      .then((data = []) => (this.focusList = data))

    // 浏览top10
    this.getTop10({type: 'view', count: 10})
      .then((data = []) => (this.viewList = data))
  },
  mounted () {
    if (!this.list.length) {
      this.handleFilter()
    }
  },
  methods: {
    ...mapActions([
      'getTop10',
      'getProList',
      'updateFocus',
      'getFilterParams'
    ]),
    // 翻页
    onPageClick (page) {
      this.currentPage = page
      this.handleFilter()
    },
    // 筛选
    onFilterClick (o, v) {
      o.default = v
      this.onSearch()
      this.handleFilter()
    },
    // 搜索
    onSearch () {
      this.currentPage = 1
      this.handleFilter()
    },
    getParams () {
      return {
        perpage: 10,
        keyword: this.keywords,
        pageno: this.currentPage,
        stage: this.stage.default || 0,
        region: this.region.default || 0,
        buzType: this.buzType.default || 0
      }
    },
    // 加载数据
    handleFilter () {
      this.getProList(this.getParams())
        .then((data = {}) => {
          const {
            dataCount = 0,
            projList = []
          } = data

          this.total = dataCount
          this.list = projList
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

.filter-list {
  padding-bottom: 0;

  > .dl-horizontal {
    > dt {
      width: 50px;
      float: left;
      text-align: left;
      font-weight: normal;
      color: $gray-darker;
      line-height: 30px;
    }

    > dd {
      margin-left: 50px;

      a {
        display: inline-block;
        vertical-align: middle;
        padding: 6px 12px;
        margin-bottom: 5px;
        line-height: 1.4;
        color: $black-light;

        &.active {
          color: #fff;
          background-color: $primary-color;
        }
      }
    }
  }
}

.table > thead > tr > th {
  padding-top: 12px;
  padding-bottom: 12px;
  font-weight: normal;
}
.table > tbody > tr > td {
  vertical-align: middle;
}

.list-counter {
  counter-reset: rank;
  padding-bottom: 15px;

  .list-group-item {
    position: relative;
    padding-left: 40px;
    padding-right: 50px;
    padding-top: 6px;
    padding-bottom: 6px;
    counter-increment: rank;
    border-width: 0;

    &:before {
      position: absolute;
      content: counter(rank);
      left: 15px;
      width: 20px;
      height: 20px;
      line-height: 20px;
      text-align: center;
      border-radius: 50%;
    }

    &:nth-child(1):before {
      color: #fff;
      background-color: #fa662b;
    }

    &:nth-child(2):before {
      color: #fff;
      background-color: #fd8825;
    }

    &:nth-child(3):before {
      color: #fff;
      background-color: #fbbb30;
    }

    .count {
      position: absolute;
      right: 20px;
    }
  }
}
</style>
