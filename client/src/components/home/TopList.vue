<template>
  <div class="top-list">
    <h3 class="panel-title">明星项目</h3>
    <div class="info-container">
      <div class="info-area index1">
        <span>至各基金</span>
        <span>$0.004</span>
      </div>
      <div class="info-area index2">
        <span>至各基金</span>
        <span>$0.004</span>
      </div>
      <div class="info-area index3">
        <span>至各基金</span>
        <span>$0.004</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {}
  }
}
</script>

<style lang="scss" scoped>
.top-list {
  box-sizing: border-box;
  width: 350px;
  height: 339px;
  padding: 20px;
  background-color: #FFF;
  .info-container {
    box-sizing: border-box;
    width: 100%;
    padding: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    .info-area {
      box-sizing: border-box;
      width: 294px;
      height: 81px;
      background-color: #000;
      margin-top: 4px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 30px;
      color: #FFCF81;
      :last-child {
        color: #FFF;
      }
      &:first-child {
        background: linear-gradient(#000, #525252);
      }
      &:nth-child(2) {
        background: linear-gradient(#616161, #9BC5C3);
      }
      &:last-child {
        background: linear-gradient(#243949, #517FA4);
      }
    }
  }
}
</style>
