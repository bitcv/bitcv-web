<template>
  <div class="nav container">
    <el-menu default-active="0" background-color="#545c64" text-color="#fff" active-text-color="#ffd04b">
      <el-menu-item v-for="(item, index) in itemList" :index="index + ''" :key="index">
        <router-link :to="item.url" class="router">
          <div>
            <i :class="item.icon"></i>
            <span slot="title">{{ item.text }}</span>
          </div>
        </router-link>
      </el-menu-item>
    </el-menu>
  </div>
</template>

<script>
export default {
  data () {
    return {
      itemList: []
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
      this.$http.post('/api/getUser', {}).then((res) => {
        var resData = res.data
        if (resData.errcode === 0) {
          var user = resData.data
          // 超级管理员
          if (user['isSys'] === 1) {
            this.$router.push('/admin/project')
            this.itemList = [{
              icon: 'el-icon-menu',
              url: '/admin/project',
              text: '项目管理'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/depositBox',
              text: '余币宝'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/token',
              text: '通证配置'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/social',
              text: '社群配置'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/media',
              text: '媒体配置'
            // }, {
              // icon: 'el-icon-menu',
              // url: '/admin/module',
              // text: '运营工具管理'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/mediareport',
              text: '项目动态'
            }]
          // 项目管理员
          } else if (user['projId'] > 0) {
            this.$router.push('/admin/editProject/' + user['projId'])
            this.itemList = [{
              icon: 'el-icon-menu',
              url: '/admin/editProject/' + user['projId'],
              text: '项目信息'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/projDepositBox/' + user['projId'],
              text: '余币宝'
            }]
          // 个人
          } else {
            this.$router.push('/admin/setting')
            this.itemList = [{
              icon: 'el-icon-menu',
              url: '/admin/setting',
              text: '个人中心'
            }]
          }
        } else {
          this.$router.push('/signin')
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.container {
  width: 100%;
  height: 100%;
  .el-menu {
    height: 100%;
    border-right: none;
    .router {
      display: block;
      width: 100%;
      height: 100%;
    }
  }
}
</style>
