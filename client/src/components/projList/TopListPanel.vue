<template>
  <div class="top-list-panel">
    <h3 class="panel-title">关注TOP10</h3>
    <ul class="list-container">
      <li class="list-item" v-for="item in 10" :key="item">
        <span class="index">{{ item }}</span>
        <span class="title">网易严选</span>
        <span class="count">2342</span>
      </li>
    </ul>
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
.top-list-panel {
  box-sizing: border-box;
  width: 100%;
  background-color: #FFF;
  padding: 20px;
  .list-container {
    margin-top: 13px;
    width: 100%;
    .list-item {
      height: 34px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      .index {
        display: inline-block;
        background-color: #EAEAEA;
        width: 24px;
        height: 24px;
        border-radius: 2px;
        line-height: 24px;
        text-align: center;
        color: #FFF;
      }
      &:first-child>.index {
        background-color: #D0021B;
      }
      &:first-child>.title {
        color: #D0021B;
      }
      &:nth-child(2)>.index {
        background-color: #FF6B7D;
      }
      &:nth-child(2)>.title {
        color: #FF6B7D;
      }
      &:nth-child(3)>.index {
        background-color: #FFCF81;
      }
      &:nth-child(3)>.title {
        color: #FFCF81;
      }
      .title {
        font-size: 14px;
        line-height: 20px;
        color: #4A4A4A;
        width: 220px;
      }
      .count {
        font-size: 14px;
        line-height: 20px;
        color: #9B9B9B;
      }
    }
  }
}
</style>
