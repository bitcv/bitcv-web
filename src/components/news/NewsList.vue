<template>
  <div class = "new-list">
    <div class="main-panel">
      <news-table-panel></news-table-panel>
    </div>
    <div class="aside-panel">
      <top-list-panel v-for="item in topList" :key="item.type" :list-data="item"></top-list-panel>
    </div>
  </div>
</template>
<script>
import TopListPanel from '@/components/projList/TopListPanel'
import NewsTablePanel from '@/components/news/NewsTablePanel'
export default {
  data () {
    return {
      topList: [
        { title: '关注TOP10', type: 'focus' },
        { title: '浏览TOP10', type: 'view' }
      ]
    }
  },
  components: {
    TopListPanel,
    NewsTablePanel
  }
}
</script>
<style lang="scss" scoped>
.new-list{
  width: 1200px;
  margin: 0 auto;
  //max-width: 100%;
  display: flex;
  justify-content: space-between;
  .main-panel{
    flex: 1 1 auto;
    max-width: 826px;
    height: 100%;
    width: 826px;
  }
  .aside-panel{
    flex-shrink: 0;
    width: 350px;
    margin-left: 24px;
    div {
      margin-bottom: 24px;
    }
  }
}
</style>
