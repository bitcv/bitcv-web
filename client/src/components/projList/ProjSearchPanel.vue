<template>
  <div class="proj-search-panel">
    <search :filter-result="filterResult"></search>
    <div class="sort-container">
      <div class="sort-row" v-for="rule in filterRuleList" :key="rule.type">
        <span class="title">{{ rule.text }}</span>
        <div class="tag-wrapper">
           <span class="tag"
             :class="{ cur: rule.curOption === option.value }"
             @click="rule.curOption = option.value"
             v-for="option in rule.optionList" :key="option.value">{{ option.text }}</span>
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
      filterRuleList: [
        {
          type: 1,
          text: '地区',
          curOption: 0,
          optionList: [
            { text: '不限', value: 0 },
            { text: '国内', value: 1 },
            { text: '国外', value: 2 }
          ]
        },
        {
          type: 2,
          text: '行业',
          curOption: 0,
          optionList: [
            { text: '不限', value: 0 },
            { text: '人工智能', value: 1 },
            { text: '房产服务', value: 2 },
            { text: '教育', value: 3 },
            { text: '金融', value: 4 },
            { text: '硬件', value: 5 },
            { text: '医疗健康', value: 6 },
            { text: '文化娱乐', value: 7 }
          ]
        },
        {
          type: 3,
          text: '阶段',
          curOption: 0,
          optionList: [
            { text: '不限', value: 0 },
            { text: '初创期', value: 1 },
            { text: '成长发展期', value: 2 },
            { text: '上市公司', value: 3 },
            { text: '成熟期', value: 4 }
          ]
        }
      ]
    }
  },
  computed: {
    filterResult: function () {
      return {
        region: this.filterRuleList[0].curOption,
        bussinessType: this.filterRuleList[1].curOption,
        phrase: this.filterRuleList[2].curOption
      }
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
