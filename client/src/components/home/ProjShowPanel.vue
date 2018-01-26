<template>
  <div class="proj-show-panel">
    <h3 class="panel-title">发现新公司</h3>
    <div class="main-container">
      <proj-intro></proj-intro>
      <proj-intro></proj-intro>
      <proj-intro></proj-intro>
      <proj-intro></proj-intro>
      <proj-intro></proj-intro>
      <proj-intro></proj-intro>
    </div>
  </div>
</template>

<script>
import ProjIntro from '@/components/home/ProjIntro'

export default {
  data () {
    return {}
  },
  components: {
    ProjIntro
  }
}
</script>

<style lang="scss" scoped>
.proj-show-panel {
  padding: 20px;
  background-color: #FFF;
  .main-container {
    margin: 0 -20px;
    width: 826px;
    height: 324px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-content: space-around;
  }
}
</style>
