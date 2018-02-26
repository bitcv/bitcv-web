<template>
  <div class="container">
    <div class="row" style="margin-bottom:20px">
      <div class="col-md-8">
        <search-bar v-model="keywords"></search-bar>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="panel panel-custom">
          <div class="panel-body filter-list">
            <dl class="dl-horizontal">
              <dt>地区</dt>
              <dd>
                <a href="javascript:;" class="active">不限</a>
                <a href="javascript:;">美国</a>
                <a href="javascript:;">日韩</a>
                <a href="javascript:;">欧洲</a>
                <a href="javascript:;">东南亚</a>
                <a href="javascript:;">其他地区</a>
              </dd>
            </dl>
            <dl class="dl-horizontal">
              <dt>行业</dt>
              <dd>
                <a href="javascript:;" class="active">不限</a>
                <a href="javascript:;">金融</a>
                <a href="javascript:;">数字资产</a>
                <a href="javascript:;">娱乐</a>
                <a href="javascript:;">供应链管理</a>
                <a href="javascript:;">法律服务</a>
                <a href="javascript:;">医疗</a>
                <a href="javascript:;">能源服务</a>
                <a href="javascript:;">公益</a>
                <a href="javascript:;">农业</a>
                <a href="javascript:;">社交</a>
                <a href="javascript:;">物联网</a>
                <a href="javascript:;">其他</a>
              </dd>
            </dl>
            <dl class="dl-horizontal">
              <dt>阶段</dt>
              <dd>
                <a href="javascript:;" class="active">不限</a>
                <a href="javascript:;">募资前</a>
                <a href="javascript:;">募资中</a>
                <a href="javascript:;">公开发行</a>
                <a href="javascript:;">产品落地</a>
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
              <tr>
                <td class="text-center">
                  <a href="javascript:;" class="text-dark" title="收藏"><i class="icon-bcv icon-heart"></i></a>
                </td>
                <td>
                  <img src="https://placehold.it/30x30" class="img-rounded">
                  <span>币威_BitCV</span>
                </td>
                <td><span class="text-primary">BCV</span></td>
                <td>金融</td>
                <td><span class="text-primary">已融资</span></td>
              </tr>
              <tr>
                <td class="text-center">
                  <a href="javascript:;" class="text-dark" title="收藏"><i class="icon-bcv icon-heart"></i></a>
                </td>
                <td>
                  <img src="https://placehold.it/30x30" class="img-rounded">
                  <span>币威_BitCV</span>
                </td>
                <td><span class="text-primary">BCV</span></td>
                <td>金融</td>
                <td><span class="text-primary">已融资</span></td>
              </tr>
              <tr>
                <td class="text-center">
                  <a href="javascript:;" class="text-dark" title="收藏"><i class="icon-bcv icon-heart"></i></a>
                </td>
                <td>
                  <img src="https://placehold.it/30x30" class="img-rounded">
                  <span>币威_BitCV</span>
                </td>
                <td><span class="text-primary">BCV</span></td>
                <td>金融</td>
                <td><span class="text-primary">已融资</span></td>
              </tr>
              <tr>
                <td class="text-center">
                  <a href="javascript:;" class="text-dark" title="收藏"><i class="icon-bcv icon-heart"></i></a>
                </td>
                <td>
                  <img src="https://placehold.it/30x30" class="img-rounded">
                  <span>币威_BitCV</span>
                </td>
                <td><span class="text-primary">BCV</span></td>
                <td>金融</td>
                <td><span class="text-primary">已融资</span></td>
              </tr>
              <tr>
                <td class="text-center">
                  <a href="javascript:;" class="text-dark" title="收藏"><i class="icon-bcv icon-heart"></i></a>
                </td>
                <td>
                  <img src="https://placehold.it/30x30" class="img-rounded">
                  <span>币威_BitCV</span>
                </td>
                <td><span class="text-primary">BCV</span></td>
                <td>金融</td>
                <td><span class="text-primary">已融资</span></td>
              </tr>
              <tr>
                <td class="text-center">
                  <a href="javascript:;" class="text-dark" title="收藏"><i class="icon-bcv icon-heart"></i></a>
                </td>
                <td>
                  <img src="https://placehold.it/30x30" class="img-rounded">
                  <span>币威_BitCV</span>
                </td>
                <td><span class="text-primary">BCV</span></td>
                <td>金融</td>
                <td><span class="text-primary">已融资</span></td>
              </tr>
              <tr>
                <td class="text-center">
                  <a href="javascript:;" class="text-dark" title="收藏"><i class="icon-bcv icon-heart"></i></a>
                </td>
                <td>
                  <img src="https://placehold.it/30x30" class="img-rounded">
                  <span>币威_BitCV</span>
                </td>
                <td><span class="text-primary">BCV</span></td>
                <td>金融</td>
                <td><span class="text-primary">已融资</span></td>
              </tr>
              <tr>
                <td class="text-center">
                  <a href="javascript:;" class="text-dark" title="收藏"><i class="icon-bcv icon-heart"></i></a>
                </td>
                <td>
                  <img src="https://placehold.it/30x30" class="img-rounded">
                  <span>币威_BitCV</span>
                </td>
                <td><span class="text-primary">BCV</span></td>
                <td>金融</td>
                <td><span class="text-primary">已融资</span></td>
              </tr>
              <tr>
                <td class="text-center">
                  <a href="javascript:;" class="text-dark" title="收藏"><i class="icon-bcv icon-heart"></i></a>
                </td>
                <td>
                  <img src="https://placehold.it/30x30" class="img-rounded">
                  <span>币威_BitCV</span>
                </td>
                <td><span class="text-primary">BCV</span></td>
                <td>金融</td>
                <td><span class="text-primary">已融资</span></td>
              </tr>
              <tr>
                <td class="text-center">
                  <a href="javascript:;" class="text-dark" title="收藏"><i class="icon-bcv icon-heart"></i></a>
                </td>
                <td>
                  <img src="https://placehold.it/30x30" class="img-rounded">
                  <span>币威_BitCV</span>
                </td>
                <td><span class="text-primary">BCV</span></td>
                <td>金融</td>
                <td><span class="text-primary">已融资</span></td>
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
        <div class="panel panel-custom text-darker">
          <div class="panel-heading">
            <h4 class="panel-title">关注TOP10</h4>
          </div>
          <div class="list-group list-counter">
            <a class="list-group-item" href="javascript:;">
              <span>币威</span>
              <span class="count">12</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>LITEX</span>
              <span class="count">10</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>智能链</span>
              <span class="count">8</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>未来版权</span>
              <span class="count">6</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>Stox</span>
              <span class="count">4</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>币威</span>
              <span class="count">12</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>LITEX</span>
              <span class="count">10</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>智能链</span>
              <span class="count">8</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>未来版权</span>
              <span class="count">6</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>Stox</span>
              <span class="count">4</span>
            </a>
          </div>
        </div>
        <div class="panel panel-custom text-darker">
          <div class="panel-heading">
            <h4 class="panel-title">浏览TOP10</h4>
          </div>
          <div class="list-group list-counter">
            <a class="list-group-item" href="javascript:;">
              <span>币威</span>
              <span class="count">12</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>LITEX</span>
              <span class="count">10</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>智能链</span>
              <span class="count">8</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>未来版权</span>
              <span class="count">6</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>Stox</span>
              <span class="count">4</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>币威</span>
              <span class="count">12</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>LITEX</span>
              <span class="count">10</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>智能链</span>
              <span class="count">8</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>未来版权</span>
              <span class="count">6</span>
            </a>
            <a class="list-group-item" href="javascript:;">
              <span>Stox</span>
              <span class="count">4</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SearchBar from '@/components/search-bar/search-bar'
import Pagination from '@/components/pagination/pagination'

export default {
  components: {
    SearchBar,
    Pagination
  },
  data () {
    return {
      keywords: '',
      total: 85,
      currentPage: 1
    }
  },
  methods: {
    onPageClick (page) {
      this.currentPage = page
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
  // line-height: 30px;
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
