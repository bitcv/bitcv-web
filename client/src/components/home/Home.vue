<template>
  <div class="home">
    <div class="main-panel">
      <search-panel :select-result="{}"></search-panel>
      <proj-show-panel></proj-show-panel>
      <proj-list-panel></proj-list-panel>
    </div>
    <div class="aside-panel">
      <top-list></top-list>
      <news-panel></news-panel>
    </div>
  </div>
</template>

<script>
import SearchPanel from '@/components/home/SearchPanel'
import TopList from '@/components/home/TopList'
import ProjShowPanel from '@/components/home/ProjShowPanel'
import ProjListPanel from '@/components/home/ProjListPanel'
import NewsPanel from '@/components/home/NewsPanel'

export default {
  data () {
    return {}
  },
  components: {
    SearchPanel,
    TopList,
    ProjShowPanel,
    ProjListPanel,
    NewsPanel
  }
}
</script>

<style lang="scss" scoped>
.home {
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
