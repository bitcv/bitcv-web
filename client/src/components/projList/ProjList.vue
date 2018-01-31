<template>
  <div class="proj-list">
    <div class="main-panel">
      <proj-search-panel></proj-search-panel>
      <proj-table-panel></proj-table-panel>
    </div>
    <div class="aside-panel">
      <top-list-panel v-for="item in topList" :key="item.type" :list-data="item"></top-list-panel>
    </div>
  </div>
</template>

<script>
import ProjSearchPanel from '@/components/projList/ProjSearchPanel'
import TopListPanel from '@/components/projList/TopListPanel'
import ProjTablePanel from '@/components/projList/ProjTablePanel'

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
    ProjSearchPanel,
    TopListPanel,
    ProjTablePanel
  }
}
</script>

<style lang="scss" scoped>
.proj-list {
  width: 1200px;
  max-width: 100%;
  display: flex;
  justify-content: space-between;
  .main-panel {
    flex: 1 1 auto;
    min-width: 500px;
    &>div {
      margin-top: 24px;
      &:nth-child(1) {
        margin-top: 0;
      }
    }
  }
  .aside-panel {
    flex-shrink: 0;
    width: 350px;
    margin-left: 24px;
    div {
      margin-bottom: 24px;
    }
  }
  @media screen and (max-width: 850px) {
    .aside-panel {
      display: none;
    }
  }
}
</style>
