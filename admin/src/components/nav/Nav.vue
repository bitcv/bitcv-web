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
            this.$router.push('/project')
            this.itemList = [{
              icon: 'el-icon-menu',
              url: '/project',
              text: '项目管理'
            }, {
              icon: 'el-icon-menu',
              url: '/depositBox',
              text: '余币宝'
            }, {
              icon: 'el-icon-menu',
              url: '/token',
              text: '通证配置'
            }, {
              icon: 'el-icon-menu',
              url: '/social',
              text: '社群配置'
            }, {
              icon: 'el-icon-menu',
              url: '/media',
              text: '媒体配置'
            // }, {
              // icon: 'el-icon-menu',
              // url: '/module',
              // text: '运营工具管理'
            }, {
              icon: 'el-icon-menu',
              url: '/mediareport',
              text: '项目动态'
            }, {
              icon: 'el-icon-menu',
              url: '/perlist',
              text: '成员配置'
            }, {
              icon: 'el-icon-menu',
              url: '/institution',
              text: '机构管理'
            }, {
              icon: 'el-icon-menu',
              url: '/exchange',
              text: '交易所管理'
            }, {
              icon: 'el-icon-menu',
              url: '/editor',
              text: '运营人员'
            }, {
              icon: 'el-icon-menu',
              url: '/user',
              text: '用户管理'
            }, {
              icon: 'el-icon-menu',
              url: '/vcode',
              text: '验证码'
            }]
          // 项目管理员
          } else if (user['isSys'] === 2) {
            this.$router.push('/admin/project')
            this.itemList = [{
              icon: 'el-icon-menu',
              url: '/project',
              text: '项目管理'
            }, {
              icon: 'el-icon-menu',
              url: '/depositBox',
              text: '余币宝'
            }, {
              icon: 'el-icon-menu',
              url: '/token',
              text: '通证配置'
            }, {
              icon: 'el-icon-menu',
              url: '/social',
              text: '社群配置'
            }, {
              icon: 'el-icon-menu',
              url: '/media',
              text: '媒体配置'
            // }, {
              // icon: 'el-icon-menu',
              // url: '/admin/module',
              // text: '运营工具管理'
            }, {
              icon: 'el-icon-menu',
              url: '/mediareport',
              text: '项目动态'
            }, {
              icon: 'el-icon-menu',
              url: '/perlist',
              text: '成员配置'
            }, {
              icon: 'el-icon-menu',
              url: '/institution',
              text: '机构管理'
            }, {
              icon: 'el-icon-menu',
              url: '/exchange',
              text: '交易所管理'
            }]
          } else if (user['projId'] > 0) {
            this.$router.push('/editProject/' + user['projId'])
            this.itemList = [{
              icon: 'el-icon-menu',
              url: '/editProject/' + user['projId'],
              text: '项目信息'
            }, {
              icon: 'el-icon-menu',
              url: '/projDepositBox/' + user['projId'],
              text: '余币宝'
            }]
          // 个人
          } else {
            this.$router.push('/setting')
            this.itemList = [{
              icon: 'el-icon-menu',
              url: '/setting',
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
