<template>
  <div class="proj-list-panel">
    <h3 class="panel-title">项目直通车</h3>
    <proj-item-box></proj-item-box>
    <proj-item-box></proj-item-box>
    <proj-item-box></proj-item-box>
    <proj-item-box></proj-item-box>
    <div class="bottom-btn">更多优秀项目</div>
  </div>
</template>

<script>
import ProjItemBox from '@/components/home/ProjItemBox'

export default {
  data () {
    return {}
  },
  components: {
    ProjItemBox
  }
}
</script>

<style lang="scss" scoped>
.proj-list-panel {
  padding: 20px;
  background-color: #FFF;
  width: 100%;
  box-sizing: border-box;
  position: relative;
  .bottom-btn {
    width: 826px;
    height: 54px;
    line-height: 54px;
    text-align: center;
    background-color: #D8D8D8;
    color: #FFF;
    margin: 0 -20px -20px;
  }
}
</style>
