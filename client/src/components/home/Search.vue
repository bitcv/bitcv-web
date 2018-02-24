<template>
  <div class="search">
    <div class="input-box">
      <img src="/static/img/search-2x.png" alt="">
      <input v-model="keyword" @keyup.enter="searchProject" type="text" placeholder="代币名称、符号、项目名称">
    </div>
    <div class="btn" @click="searchProject"><span>搜索机构</span></div>
  </div>
</template>

<script>
export default {
  props: {
    selectResult: Object
  },
  data () {
    return {
      keyword: ''
    }
  },
  mounted () {
    if (this.$route.query.keyword) {
      this.keyword = this.$route.query.keyword
    }
  },
  watch: {
    selectResult: {
      handler () {
        this.searchProject()
      },
      deep: true
    }
  },
  methods: {
    searchProject () {
      if (this.$route.path !== '/projList') {
        return this.$router.push('/projList?keyword=' + this.keyword)
      }
      var params = JSON.parse(JSON.stringify(this.selectResult))
      params.keyword = this.keyword
      this.$root.eventHub.$emit('searchProject', params)
    }
  }
}
</script>

<style lang="scss" scoped>
.search {
  margin-top: 16px;
  height: 45px;
  display: flex;
  .input-box {
    flex: 1 1 auto;
    box-sizing: border-box;
    height: 100%;
    border: 1px solid #E4E4E4;
    border-right: none;
    border-radius: 23px 0 0 23px;
    display: flex;
    align-items: center;
    img {
      width: 30px;
      height: 30px;
      margin-left: 12px;
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
    flex: 0 0 auto;
    padding: 0;
    width: 115px;
    height: 100%;
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
