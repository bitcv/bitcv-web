<template>
  <div class="home">
    <div class="main-panel">
      <search></search>
      <proj-show-panel></proj-show-panel>
      <proj-list-panel></proj-list-panel>
    </div>
    <div class="aside-panel">
      <top-list></top-list>
    </div>
  </div>
</template>

<script>
import Search from '@/components/home/Search'
import TopList from '@/components/home/TopList'
import ProjShowPanel from '@/components/home/ProjShowPanel'
import ProjListPanel from '@/components/home/ProjListPanel'

export default {
  data () {
    return {}
  }, 
  components: {
    Search,
    TopList,
    ProjShowPanel,
    ProjListPanel
  }
}
</script>

<style lang="scss" scoped>
.home {
  width: 1200px;
  display: flex;
  justify-content: space-between;
  .main-panel {
    width: 826px;
    min-width: 500px;
    div {
      margin-bottom: 24px;
    }
  }
  .aside-panel {
    flex-shrink: 0;
    width: 350px;
    margin-left: 24px;
  }
}
</style>
