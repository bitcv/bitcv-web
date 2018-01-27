<template>
  <div class="proj-table-panel">
    <h3 class="panel-title">共{{ projData.dataCount }}项</h3>
    <table class='info-table'>
      <tr class="info-header">
        <th>公司名称</th>
        <th>代币名称</th>
        <th>当前代币价格</th>
        <th></th>
        <th>融资状态</th>
      </tr>
      <tr class="info-row" @click="projNav(project.id)" v-for="project in projData.projList" :key="project.id">
        <td>
          <div class="logo-box">
            <img :src="project.logoUrl" alt="">
          </div>
          <div class="info-box">
            <span class="name">{{ project.nameCn }}</span>
            <span class="intro">{{ project.nameEn }}</span>
          </div>
        </td>
        <td><span>{{ project.tokenSymbol }}</span></td>
        <td><div><span>{{ project.tokenPrice }}</span></div></td>
        <td><img src="/static/img/心@2x.png" alt=""></td>
        <td><span>已融资</span></td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  data () {
    return {
      projData: function () {
        return {}
      }
    }
  },
  created () {
    this.$root.eventHub.$on('updateProjList', this.updateProjList)
  },
  beforeDestroy () {
    this.$root.eventHub.$off('updateProjList', this.updateProjList)
  },
  mounted () {
    var that = this
    var keyword = this.$route.query.keyword
    this.$http.post('/api/getProjList', {
      keyword: keyword,
      pageno: 1,
      perpage: 10
    }).then(function (res) {
      var resData = res.data
      if (resData.errcode === 0) {
        that.updateProjList(resData.data)
      } else {
        alert(resData.errmsg)
      }
    })
  },
  methods: {
    updateProjList: function (projData) {
      this.projData = projData
    },
    projNav: function (projId) {
      this.$router.push('projDetail/' + projId)
    }
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
      }
    }
    .info-row {
      cursor: pointer;
      border-top: 0.5px solid #E4E4E4;
      td {
        padding: 20px 0 22px;
        height: 60px;
        box-sizing: border-box;
        vertical-align: middle;
        &:nth-child(1) {
          font-size: 0;
          .logo-box {
            display: inline-block;
            width: 60px;
            height: 60px;
            img {
              width: 60px;
              height: 60px;
            }
          }
          .info-box {
            display: inline-block;
            width: 150px;
            font-size: 14px;
            position: relative;
            height: 60px;
            vertical-align: top;
            text-align: left;
            margin-left: 10px;
            .name {
              color: #000;
            }
            .intro {
              display: block;
              font-size: 12px;
              line-height: 17px;
              color: #9B9B9B;
              position: relative;
              top: 30px;
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
