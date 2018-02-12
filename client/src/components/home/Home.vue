<template>
  <div class="home">
    <div class="main-panel">
      <search-panel :select-result="{}"></search-panel>
      <proj-show-panel class="mobile-hide"></proj-show-panel>
      <proj-list-panel></proj-list-panel>
    </div>
    <div class="aside-panel">
      <top-list></top-list>
      <div class="apply-btn-box">
        <a @click="toApply" class="btn btn-cy transition">立即创建项目</a>
      </div>
      <!--<news-panel></news-panel>-->
    </div>
  </div>
</template>

<script>
import SearchPanel from '@/components/home/SearchPanel'
import TopList from '@/components/home/TopList'
import ProjShowPanel from '@/components/home/ProjShowPanel'
import ProjListPanel from '@/components/home/ProjListPanel'
import NewsPanel from '@/components/home/NewsPanel'

export default {
  data () {
    return {}
  },
  methods: {
    toApply () {
      var userId = localStorage.getItem('userId')
      if (!userId) {
        alert('请登录')
      } else {
        this.$router.push('/apply')
      }
    }
  },
  components: {
    SearchPanel,
    TopList,
    ProjShowPanel,
    ProjListPanel,
    NewsPanel
  }
}
</script>

<style lang="scss" scoped>
.home {
  width: 1200px;
  max-width: 100%;
  display: flex;
  justify-content: space-between;
  .main-panel {
    flex: 1 1 auto;
    min-width: 300px;
    &>div {
      margin-top: 24px;
      &:nth-child(1) {
        margin-top: 0;
      }
    }
  }
  .aside-panel {
    flex-shrink: 0;
    width: 350px;
    margin-left: 24px;
    div {
      margin-bottom: 24px;
    }
    .apply-btn-box {
      padding: 30px;
      background-color: #fff;
    }
    .btn {
      display: inline-block;
      padding: 6px 12px;
      margin-bottom: 0;
      font-size: 14px;
      font-weight: 400;
      text-align: center;
      white-space: nowrap;
      vertical-align: middle;
      -ms-touch-action: manipulation;
      touch-action: manipulation;
      cursor: pointer;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      background-image: none;
      border: 1px solid transparent;
      border-radius: 4px;
    }
    .transition {
      -webkit-transition: all .2s ease-out;
      -moz-transition: all .2s ease-out;
      -ms-transition: all .2s ease-out;
      -o-transition: all .2s ease-out;
      transition: all .2s ease-out;
    }
    .btn-cy {
      width: 270px;
      height: 40px;
      background-color: #4A4A4A;
      color: #FFCF81;
      font-size: 18px;
      text-align: center;
      line-height: 40px;
      border-radius: 0;
    }
    .btn {
      border-radius: 0;
    }
  }
  @media screen and (max-width: 850px) {
    .aside-panel {
      display: none;
    }
  }
  @media screen and (max-width: 500px) {
    .mobile-hide {
      display: none;
    }
  }
}
</style>
