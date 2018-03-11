<template>
  <div class="container acting">
    <el-menu mode="vertical" router :default-active="activeIndex" active-text-color="#ee9429" class="menu">
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
        {key: '1', text: '首页', route: {path: 'home'}},
        {key: '2', text: '代发账户', route: {path: 'account'}},
        {key: '3', text: '发放记录', route: {path: 'record/detail' || 'record'}},
        {key: '4', text: '帮助说明', route: {path: 'explain'}}
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
        return item.key
      }
      return '1'
    }
  }
}
</script>

<style lang="scss">
.acting{
  overflow: hidden;
  background: #fff;
  .menu{
    width: 150px;
    height: 100%;
    text-align: center;
    float: left;
    border-right: none;
  }
  .content{
    border-left: 1px solid #e6e6e6;
    margin-left: 150px;
    padding: 30px 50px;
  }
}
</style>
