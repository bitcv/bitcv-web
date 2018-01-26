<template>
  <div class="proj-list">
    <div class="main-panel">
      <proj-search-panel></proj-search-panel>
      <proj-table-panel></proj-table-panel>
    </div>
    <div class="aside-panel">
      <top-list-panel></top-list-panel>
      <top-list-panel></top-list-panel>
    </div>
  </div>
</template>

<script>
import ProjSearchPanel from '@/components/projList/ProjSearchPanel'
import TopListPanel from '@/components/projList/TopListPanel'
import ProjTablePanel from '@/components/projList/ProjTablePanel'

export default {
  data () {
    return {}
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
    div {
      margin-bottom: 24px;
    }
  }
}
</style>
