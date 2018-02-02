<template>
  <div class="proj-search-panel">
    <search :select-result="selectResult"></search>
    <div class="sort-container">
      <div class="sort-row" v-for="(tagInfo, field) in projTagList" :key="tagInfo.field">
        <span class="title">{{ tagInfo.label }}</span>
        <div class="tag-wrapper">
           <span class="tag" :class="{ cur: selectResult[field] === tag.value }" @click="selectResult[field] = tag.value" v-for="tag in tagInfo.optionList" :key="tag.value">{{ tag.label }}</span>
        </div>
      </div>
    </div>
    <div class="btn-box">
      <img src="/static/img/Triangle@2x.png" alt="">
      <span>全部筛选项</span>
    </div>
  </div>
</template>

<script>
import Search from '@/components/home/Search'

export default {
  data () {
    return {
      projTagList: {},
      selectResult: {}
    }
  },
  mounted () {
    var that = this
    this.$http.get('/api/getProjTagList').then(function (res) {
      if (res.data.errcode === 0) {
        var resData = res.data.data
        for (let field in resData) {
          that.$set(that.selectResult, field, resData[field].default)
        }
        that.projTagList = resData
      }
    })
  },
  methods: {
    select (field, value) {
      this.selectResult[field] = value
      console.log(this.selectResult)
    }
  },
  components: {
    Search
  }
}
</script>

<style lang="scss" scoped>
.proj-search-panel {
  box-sizing: border-box;
  width: 100%;
  background-color: #FFF;
  padding: 49px 30px 10px;
  display: flex;
  flex-direction: column;
  align-items: center;
  .search {
    width: 100%;
    margin: 0 19px;
  }
  .sort-container {
    width: 100%;
    margin-top: 40px;
    border-bottom: 0.5px solid #979797;
    .sort-row {
      display: flex;
      width: 100%;
      font-size: 0;
      margin-bottom: 23px;
      span {
        margin: 5px;
      }
      .title {
        flex-shrink: 0;
        font-size: 16px;
        line-height: 22px;
        color: #8D8D8D;
        margin-right: 20px;
      }
      .tag-wrapper {
        overflow: hidden;
        .tag {
          float: left;
          display: inline-block;
          padding: 0 12px;
          background-color: #EAEAEA;
          font-size: 16px;
          line-height: 24px;
          color: #9B9B9B;
          border-radius: 2px;
          cursor: pointer;
          &.cur {
            color: #FFCF81;
            background-color: #4A4A4A;
          }
        }
      }
    }
  }
  .btn-box {
    margin-top: 10px;
    img {
      width: 9.8px;
      height: 9.8px;
    }
    span {
      font-size: 12px;
      line-height: 17px;
      color: #9B9B9B;
    }
  }
}
</style>
