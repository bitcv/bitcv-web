<template>
  <div class = "new-list">
    <div class="main-panel">
      <new-table-panel></new-table-panel>
    </div>
    <div class="aside-panel">
      <top-list-panel v-for="item in topList" :key="item.type" :list-data="item"></top-list-panel>
    </div>
  </div>
</template>
<script>
import TopListPanel from '@/components/projList/TopListPanel'
import NewTablePanel from '@/components/news/NewTablePanel'
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
    NewTablePanel
  }
}
</script>
<style lang="scss" scoped>
.new-list{
  width: 1200px;
  max-width: 100%;
  display: flex;
  justify-content: space-between;
  .main-panel{
    flex: 1 1 auto;
    min-width: 500px;
  }
  .aside-panel{
    flex-shrink: 0;
    width: 350px;
    margin-left: 24px;
  }
}
</style>
