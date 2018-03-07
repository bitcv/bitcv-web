<template>
  <div>
    <el-menu mode="horizontal" router :default-active="activeIndex" active-text-color="#ee9429">
      <el-menu-item v-for="item in menus" :index="item.key" :route="item.route" :key="item.key">{{ item.text }}</el-menu-item>
    </el-menu>

    <router-view></router-view>
  </div>
</template>

<script>
import {mapState} from 'vuex'

export default {
  data () {
    return {
      menus: [
        {key: '1', text: '基本信息', route: {path: 'info'}},
        {key: '2', text: '团队成员', route: {path: 'team'}},
        {key: '3', text: '发展历程', route: {path: 'process'}},
        {key: '4', text: '社交信息', route: {path: 'social'}},
        {key: '5', text: '项目顾问', route: {path: 'adviser'}},
        {key: '6', text: '投资机构', route: {path: 'institution'}},
        {key: '7', text: '交易所', route: {path: 'exchange'}},
        {key: '8', text: '媒体报道', route: {path: 'news'}}
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
