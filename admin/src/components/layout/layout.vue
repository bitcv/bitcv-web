<template>
  <el-container style="height:100%;">
    <el-header>
      <v-header></v-header>
    </el-header>

    <el-container>
      <el-aside width="200px">
        <el-menu class="side-menu" router :default-active="activeIndex">
          <el-menu-item v-for="item in menus" :index="item.key" :route="item.route" :key="item.key">
            <i :class="item.icon"></i>
            <span>{{ item.text }}</span>
          </el-menu-item>
        </el-menu>
      </el-aside>

      <el-main>
        <router-view></router-view>
      </el-main>
    </el-container>

  </el-container>
</template>

<script>
import {mapState} from 'vuex'
import Header from '@/components/header'

export default {
  name: 'Layout',
  components: {
    'v-header': Header
  },
  data () {
    return {
      menus: [
        {key: '1', text: '项目信息', icon: 'el-icon-tickets', route: {path: '/project'}},
        {key: '2', text: '余币宝', icon: 'el-icon-news', route: {path: '/candy'}}
      ]
    }
  },
  computed: {
    ...mapState({
      path: state => state.route.path
    }),
    activeIndex () {
      const reg = new RegExp(this.path.replace(/^\//, '').replace(/\//g, '|'))

      const item = this.menus.find(item => reg.test(item.route.path))

      if (item) {
        return item.key
      }

      return '1'
    }
  }
}
</script>

<style lang="scss">
  .el-header {
    background-color: #656565;
  }

  .el-aside {
    background-color: #f5f5f5;

    .side-menu {
      background-color: #f5f5f5;

      .el-menu-item {
        color: #787878;

        &:hover, &:focus {
          background-color: #fcf7f1;
        }

        &.is-active {
          color: #fff;
          background-color: #fb9727;
        }
      }
    }
  }
</style>
