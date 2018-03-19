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
        {key: '1', text: '用户列表', route: {path: 'list'}},
        {key: '2', text: '添加用户', route: {path: 'adduser'}}
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
</style>
