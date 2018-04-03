<template>
  <div class="proj-table-panel">
    <h3 class="panel-title">共 {{ projData.dataCount }} 项</h3>
    <table class='info-table'>
      <tr class="info-header">
        <th>公司名称</th>
        <th class="mobile-hide">代币符号</th>
        <th class="mobile-hide">行业</th>
        <th class="mobile-hide"></th>
        <th class="mobile-hide">融资状态</th>
      </tr>
      <tr class="info-row" @click="projNav(project.id)" v-for="(project, index) in projData.projList" :key="project.id">
        <td>
          <div class="logo-box">
            <img :src="project.logoUrl" alt="">
          </div>
          <div class="info-box">
            <span class="name">{{ project.nameCn }}</span>
            <span class="intro">{{ project.nameEn }}</span>
          </div>
        </td>
        <td class="mobile-hide"><span>{{ project.tokenSymbol }}</span></td>
        <td class="mobile-hide"><div><span>{{ convertBuzType(project.buzType) }}</span></div></td>
        <td class="mobile-hide"><img :src="project.focusStatus === 1 ? focusUrl : unfocusUrl" alt="" @click.stop.prevent="focus(project.id, index)"></td>
        <td class="mobile-hide"><span>{{ convertFundStage(project.fundStage) }}</span></td>
      </tr>
    </table>
    <page-panel :page-count="pageCount" @changePage="changePage"></page-panel>
  </div>
</template>

<script>
import PagePanel from '@/components/projList/PagePanel'

export default {
  data () {
    return {
      projData: function () {
        return {
          fundStage: '',
          buzType: ''
        }
      },
      focusUrl: '/static/img/focus@2x.png',
      unfocusUrl: '/static/img/unfocus@2x.png',
      params: {
        pageno: 1,
        perpage: 10
      }
    }
  },
  created () {
    this.$root.eventHub.$on('searchProject', this.searchProject)
  },
  beforeDestroy () {
    this.$root.eventHub.$off('searchProject', this.searchProject)
  },
  mounted () {
    this.params.keyword = this.$route.query.keyword
    this.updateProjList()
  },
  computed: {
    pageCount () {
      return Math.ceil(this.projData.dataCount / 10) || 0
    }
  },
  methods: {
    changePage (pageno) {
      this.params.pageno = pageno
      this.updateProjList()
    },
    searchProject (params) {
      params.pageno = 1
      params.perpage = 10
      this.params = params
      this.updateProjList()
    },
    updateProjList: function () {
      this.$http.post('/api/getProjList', this.params).then((res) => {
        if (res.data.errcode === 0) {
          this.projData = res.data.data
          this.$root.eventHub.$emit('updateProjPage', this.projData.dataCount)
        }
      })
    },
    projNav: function (projId) {
      this.$router.push('projDetail/' + projId)
    },
    focus: function (projId, index) {
      var userId = localStorage.getItem('userId')
      if (!userId) {
        return alert('请登录')
      }
      var that = this
      this.$http.post('/api/toggleFocus', {
        projId: projId
      }).then(function (res) {
        var resData = res.data
        if (resData.errcode === 0) {
          that.projData.projList[index].focusStatus = resData.data.status
        }
      })
    }
  },
  components: {
    PagePanel
  }
}
</script>

<style lang="scss" scoped>
.proj-table-panel {
  box-sizing: border-box;
  width: 100%;
  background-color: #FFF;
  padding: 20px;
  .info-table {
    box-sizing: border-box;
    width: calc(100% + 40px);
    margin: 10px -20px 0;
    text-align: center;
    .info-header {
      th {
        padding: 20px 0;
        &:nth-child(1) {
          width: 40%;
          padding-left: 80px;
          text-align: left;
        }
      }
    }
    .info-row {
      cursor: pointer;
      border-top: 1px solid #E4E4E4;
      td {
        padding: 10px 0;
        height: 60px;
        vertical-align: middle;
        &:nth-child(1) {
          display: flex;
          .logo-box {
            flex-shrink: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: 20px;
            width: 60px;
            height: 60px;
            img {
              max-width: 100%;
              max-height: 100%;
            }
          }
          .info-box {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            font-size: 14px;
            text-align: left;
            margin-left: 10px;
            .name {
              color: #000;
              word-break:break-all
            }
            .intro {
              display: block;
              font-size: 12px;
              line-height: 17px;
              color: #9B9B9B;
            }
          }
        }
        &:nth-child(2) {
          font-size: 16px;
          line-height: 22px;
          color: #F5A623;
        }
        &:nth-child(3)>div>span {
          padding: 0 16px;
          color: #FFCF81;
          background-color: #4A4A4A;
          border-radius: 2px;
          font-size: 14px;
          line-height: 24px;
        }
        &:nth-child(4)>img {
          width: 18px;
          height: 18px;
        }
        &:nth-child(5) {
          font-size: 14px;
          line-height: 20px;
          color: #F5A623;
        }
      }
    }
  }
}
</style>
