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
            <search-bar v-model="keywords"></search-bar>
          </div>
        </div>

        <h4 class="section-title">发现新公司</h4>
        <div class="figure-group">
          <div class="row">
            <div class="col-md-2 col-xs-4">
              <a class="figure" href="#" title="">
                <img class="img-rounded" src="https://placehold.it/100x100">
                <span class="caption">BCV</span>
              </a>
            </div>
            <div class="col-md-2 col-xs-4">
              <a class="figure" href="#" title="">
                <img class="img-rounded" src="https://placehold.it/100x100">
                <span class="caption">LXT</span>
              </a>
            </div>
            <div class="col-md-2 col-xs-4">
              <a class="figure" href="#" title="">
                <img class="img-rounded" src="https://placehold.it/100x100">
                <span class="caption">UIP</span>
              </a>
            </div>
            <div class="col-md-2 col-xs-4">
              <a class="figure" href="#" title="">
                <img class="img-rounded" src="https://placehold.it/100x100">
                <span class="caption">EEE</span>
              </a>
            </div>
            <div class="col-md-2 col-xs-4">
              <a class="figure" href="#" title="">
                <img class="img-rounded" src="https://placehold.it/100x100">
                <span class="caption">PHD</span>
              </a>
            </div>
            <div class="col-md-2 col-xs-4">
              <a class="figure" href="#" title="">
                <img class="img-rounded" src="https://placehold.it/100x100">
                <span class="caption">GTO</span>
              </a>
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
      </div>
      <div class="col-md-4">
        <div class="panel panel-custom text-darker">
          <div class="panel-heading">
            <h4 class="panel-title">推荐项目</h4>
          </div>
          <div class="panel-body">
            <p class="recommend">
              <img src="https://placehold.it/32x32" class="img-rounded">
              <span>BitCapitalVendorToken</span>
            </p>
            <p class="recommend">
              <img src="https://placehold.it/32x32" class="img-rounded">
              <span>Gamechain System</span>
            </p>
            <p class="recommend">
              <img src="https://placehold.it/32x32" class="img-rounded">
              <span>ValueCyber</span>
            </p>
            <p class="recommend">
              <img src="https://placehold.it/32x32" class="img-rounded">
              <span>ValueCyber</span>
            </p>
          </div>
        </div>
        <p style="margin-bottom:20px;">
          <a href="#" class="btn btn-primary btn-block btn-outline btn-lg btn-nocorner" style="line-height:2;">立即创建项目</a>
        </p>
        <div class="panel panel-custom text-darker">
          <div class="panel-heading">
            <h4 class="panel-title">lianbi说</h4>
          </div>
          <ul class="list-group">
            <li class="list-group-item">
              <p class="list-group-item-heading text-ellipsis">区块链新锐BitCV获千万级天使融资</p>
              <p class="list-group-item-text text-right text-dark">2018-01-30 10:10:00</p>
            </li>
            <li class="list-group-item">
              <p class="list-group-item-heading text-ellipsis">区块链新锐BitCV获千万级天使融资区块链新锐BitCV获千万级天使融资</p>
              <p class="list-group-item-text text-right text-dark">2018-01-30 10:10:00</p>
            </li>
            <li class="list-group-item">
              <p class="list-group-item-heading text-ellipsis">区块链新锐BitCV获千万级天使融资</p>
              <p class="list-group-item-text text-right text-dark">2018-01-30 10:10:00</p>
            </li>
            <li class="list-group-item">
              <p class="list-group-item-heading text-ellipsis">区块链新锐BitCV获千万级天使融资</p>
              <p class="list-group-item-text text-right text-dark">2018-01-30 10:10:00</p>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SearchBar from '@/components/search-bar/search-bar'

export default {
  components: {
    SearchBar
  },
  data () {
    return {
      keywords: ''
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
    }

    > .caption {
      display: block;
      margin-top: 10px;
      line-height: 1;
      color: $black-light;
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

.recommend {
  margin-bottom: 15px;
  > img {
    width: 32px;
    height: 32px;
    margin-right: 10px;
  }
}
</style>
