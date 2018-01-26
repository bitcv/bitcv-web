<template>
  <div class="home">
    <div class="main-panel">
      <search-panel></search-panel>
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
  },
  beforeCreate: function () {
    console.log(this)
    alert('beforeCrete')
  },
  created: function () {
    console.log(this)
    alert('created')
  },
  beforeMount: function () {
    console.log(this)
    alert('beforeMount')
  },
  mounted: function () {
    console.log(this)
    alert('mounted')
  },
  beforeUpdate: function () {
    console.log(this)
    alert('beforeUpdate')
  },
  updated: function () {
    console.log(this)
    alert('updated')
  },
  beforeDestroy: function () {
    console.log(this)
    alert('beforeDestroy')
  },
  destoried: function () {
    console.log(this)
    alert('destoried')
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
    div {
      margin-bottom: 24px;
    }
  }
}
</style>
