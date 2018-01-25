<template>
  <div class="search">
    <div class="title">
      <span>更多的机构， 遇见更多的财富</span>
    </div>
    <div class="input-area">
      <div class="input-box">
        <img src="/static/img/search-2x.png" alt="">
        <input type="text" placeholder="代币名称、符号、项目名称">
      </div>
      <div class="btn"><span>搜索机构</span></div>
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
.search {
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
  }
  .input-area {
    width: 728px;
    height: 45px;
    display: flex;
    .input-box {
      width: 613px;
      height: 45px;
      border: 0.5px solid #E4E4E4;
      border-radius: 23px 0 0 23px;
      display: flex;
      align-items: center;
      img {
        width: 30px;
        height: 30px;
        padding-left: 12px;
      }
      input {
        display: block;
        border: none;
        outline: none;
        margin-left: 14px;
        height: 20px;
      }
    }
    .btn {
      width: 115px;
      height: 45px;
      border-radius: 0 23px 23px 0;
      background-color: #4A4A4A;
      text-align: center;
      span {
        color: #FFCF81;
        font-size: 18px;
        line-height: 45px;
      }
    }
  }
}
</style>
