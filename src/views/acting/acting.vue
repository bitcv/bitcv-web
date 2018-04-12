<template>
  <div class="container acting">
    <el-menu mode="vertical" router :default-active="activeIndex" active-text-color="#ee9429" class="menu">
      <el-menu-item v-for="item in menus" :index="item.key" :route="item.route" :key="item.key">{{ item.text }}</el-menu-item>
    </el-menu>

    <el-menu mode="horizontal" router :default-active="activeIndex" active-text-color="#ee9429" class="menu-samll">
      <el-menu-item v-for="item in menus" :index="item.key" :route="item.route" :key="item.key">{{ item.text }}</el-menu-item>
    </el-menu>

    <router-view class="content"></router-view>
  </div>
</template>

<script>
import {mapState} from 'vuex'

export default {
  data () {
    return {
      menus: [
        {key: '1', text: '首页', route: {path: '/acting/home'}},
        {key: '2', text: '代发账户', route: {path: '/acting/account'}},
        {key: '3', text: '发放记录', route: {path: '/acting/record'}}
        // {key: '4', text: '帮助说明', route: {path: '/acting/explain'}}
      ]
    }
  },
  computed: {
    ...mapState({
      path: state => state.route.path
    }),
    activeIndex () {
      const reg = new RegExp(this.path.split(/\//).pop())
      let item = this.menus.find(item => reg.test(item.route.path))
      if (item) {
        console.log(item.key)
        return item.key
      }
      return '3'
    }
  }
}
</script>

<style lang="scss">
.acting{
  overflow: hidden;
  background: #fff;
  .menu{
    width: 190px;
    height: 100%;
    text-align: center;
    float: left;
    border-right: none;
  }
  .content{
    border-left: 1px solid #e6e6e6;
    margin-left: 190px;
    padding: 40px 55px;
  }
  .menu-samll{
    display: none;
  }
  @media (max-width: 767px) {
    .menu{
      display: none;
    }
    .menu-samll{
      display: block;
      .el-menu-item{
        padding: 0 10px;
      }
    }
    .content{
      border-left: none;
      margin-left: 0;
      padding: 40px 0;
    }
  }
}
</style>
