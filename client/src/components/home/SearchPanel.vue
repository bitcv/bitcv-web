<template>
  <div class="search-panel">
    <div class="title">
      <span>更多的机构， 遇见更多的财富</span>
    </div>
    <search></search>
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
.search-panel {
  box-sizing: border-box;
  width: 100%;
  height: 255px;
  background-color: #FFF;
  padding: 32px 62px 82px 36px;
  .title {
    width: 196px;
    height: 80px;
    font-size: 28px;
    line-height: 40px;
    color:#392F2F;
  }
}
</style>
