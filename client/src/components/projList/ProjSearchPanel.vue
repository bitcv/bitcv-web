<template>
  <div class="proj-search-panel">
    <search></search>
    <div class="sort-container">
      <div class="sort-row" v-for="item in 4" :key="item">
        <span class="title">地区</span>
        <span class="tag cur">不限</span>
        <span class="tag">国内</span>
        <span class="tag">国外</span>
      </div>
    </div>
    <div class="btn-box">
      <img src="/static/img/Triangle@2x.png" alt="">
      <span>全部筛选项</span>
    </div>
  </div>
</template>

<script>
import Search from '@/components/home/Search'

export default {
  data () {
    return {}
  },
  components: {
    Search
  }
}
</script>

<style lang="scss" scoped>
.proj-search-panel {
  box-sizing: border-box;
  width: 100%;
  background-color: #FFF;
  padding: 49px 30px 10px;
  display: flex;
  flex-direction: column;
  align-items: center;
  .search {
    margin: 0 19px;
  }
  .sort-container {
    width: 100%;
    margin-top: 40px;
    border-bottom: 0.5px solid #979797;
    .sort-row {
      width: 100%;
      font-size: 0;
      margin-bottom: 23px;
      .title {
        font-size: 16px;
        line-height: 22px;
        color: #8D8D8D;
        margin-right: 20px;
      }
      .tag {
        padding: 0 12px;
        background-color: #EAEAEA;
        font-size: 16px;
        line-height: 24px;
        color: #9B9B9B;
        border-radius: 2px;
        margin: 0 10px;
        &.cur {
          color: #FFCF81;
          background-color: #4A4A4A;
        }
      }
    }
  }
  .btn-box {
    margin-top: 10px;
    img {
      width: 9.8px;
      height: 9.8px;
    }
    span {
      font-size: 12px;
      line-height: 17px;
      color: #9B9B9B;
    }
  }
}
</style>
