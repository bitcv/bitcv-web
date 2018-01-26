<template>
  <div class="search">
    <div class="input-box">
      <img src="/static/img/search-2x.png" alt="">
      <input v-model="keyword" type="text" placeholder="代币名称、符号、项目名称">
    </div>
    <div class="btn" @click="searchProject"><span>搜索机构</span></div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      keyword: ''
    }
  },
  methods: {
    searchProject () {
      if (this.keyword === '') {
        return
      }
      this.$http.request('/api/searchProject', {
        keyword: this.keyword
      }).then(function (res) {
        var resData = res.data
        if (resData.errcode === 0) {
          console.log('success')
        } else {
          alert(resData.errmsg)
        }
      }).cache(function (err) {
        console.log(err)
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.search {
  width: 728px;
  height: 45px;
  display: flex;
  margin-top: 16px;
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
      margin-left: 10px;
      font-size: 14px;
      padding: 4px;
      height: 20px;
      width: calc(100% - 56px);
    }
    input::-webkit-input-placeholder {
      font-size: 14px;
      color: #9B9B9B;
      padding: 4px;
    }
  }
  .btn {
    width: 115px;
    height: 45px;
    border-radius: 0 23px 23px 0;
    background-color: #4A4A4A;
    text-align: center;
    cursor: pointer;
    span {
      color: #FFCF81;
      font-size: 18px;
      line-height: 45px;
    }
  }
}
</style>
