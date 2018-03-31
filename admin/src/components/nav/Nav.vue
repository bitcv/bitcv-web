<template>
  <div class="nav container">
    <el-collapse v-model="activeName" accordion>
      <el-collapse-item class="zhedie" v-for="(item, index) in itemList" :index="index + ''" :key="index" :title="item.text" :name="index === 0 ? '1' : index + 1 ">
        <el-menu default-active="0" background-color="#545c64" text-color="#fff" active-text-color="#ffd04b">
          <el-menu-item v-for="(item, index) in item.child" :index="index + ''" :key="index">
            <router-link :to="item.url" class="router">
              <div>
                <i :class="item.icon"></i>
                <span slot="title">{{ item.text }}</span>
              </div>
            </router-link>
          </el-menu-item>
        </el-menu>
      </el-collapse-item>
    </el-collapse>
  </div>
</template>

<script>
export default {
  data () {
    return {
      itemList: [],
      activeName: '1'
    }
  },
  mounted () {
    // this.updateData()
    this.authUserData()
  },
  methods: {
    /* updateData () {
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
            }, {
              icon: 'el-icon-menu',
              url: '/admin/perlist',
              text: '成员配置'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/institution',
              text: '机构管理'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/exchange',
              text: '交易所管理'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/finance',
              text: '币财报'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/data',
              text: '内容运营'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/projdata',
              text: '项目更新'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/user',
              text: '用户管理'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/authuser',
              text: '后台用户'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/editor',
              text: '运营人员'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/permission',
              text: '权限管理'
            }, {
              icon: 'el-icon-menu',
              url: '/admin/vcode',
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
    } */
    // 自己的后台
    authUserData () {
      this.$http.post('/api/getAuthUser', {}).then((res) => {
        var resData = res.data
        if (resData.errcode === 0) {
          this.$router.push(resData.data.menu[0].url)
          this.itemList = resData.data.menu
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
  background: rgb(84, 92, 100);
  .el-collapse {
      border-top: 0px;
  }
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
<style type="text/css" lang="scss">
  .zhedie {
    .el-collapse-item__header {
      padding-left: 10px;
      color: #fff;
      background: rgb(84, 92, 100);
      border: 0px;
    }
    .el-collapse-item__content {
      padding-bottom: 0px;
    }
    // .el-collapse-item__wrap {
    //   border-bottom: 0px;
    // }
  }
</style>
