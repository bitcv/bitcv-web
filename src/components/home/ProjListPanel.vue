<template>
  <div class="proj-list-panel">
    <h3 class="panel-title">项目直通车</h3>
    <div class="table-box">
    <table>
      <tr v-for="project in projList" :key="project.id" @click="toProjDetail(project.id)">
        <td>
          <img :src="project.logoUrl" alt="">
        </td>
        <td>
          <div class="item-box info-box">
            <span class="title">{{ project.nameCn }}</span>
            <span>{{ project.nameEn }}</span>
          </div>
        </td>
        <td class="mobile-hide">
          <div class="item-box">
            <span class="title">代币符号</span>
            <span class="content">{{ project.tokenSymbol }}</span>
          </div>
        </td>
        <td class="mobile-hide">
          <div class="item-box">
          <span class="title">行业</span>
          <span class="content">{{ convertBuzType(project.buzType) }}</span>
          </div>
        </td>
        <td class="mobile-hide">
          <div class="item-box">
          <span class="title">融资状态</span>
          <span class="content">{{ convertFundStage(project.fundStage) }}</span>
          </div>
        </td>
      </tr>
    </table>
    </div>
    <!--<div class="bottom-btn">更多优秀项目</div>-->
  </div>
</template>

<script>
export default {
  data () {
    return {
      dataCount: 0,
      projList: []
    }
  },
  mounted () {
    var that = this
    this.$http.post('/api/getProjList', {
      pageno: 1,
      perpage: 10
    }).then(function (res) {
      var resData = res.data
      if (resData.errcode === 0) {
        that.dataCount = resData.data.dataCount
        that.projList = resData.data.projList
      }
    })
  },
  methods: {
    toProjDetail (projId) {
      this.$router.push('/projDetail/' + projId)
    }
  }
}
</script>

<style lang="scss" scoped>
.proj-list-panel {
  background-color: #FFF;
  width: 100%;
  box-sizing: border-box;
  position: relative;
  .panel-title {
    padding: 20px;
  }
  .proj-item-wrapper {
    padding: 0 50px;
  }
  .table-box {
    table {
      width: 100%;
      tr {
        height: 80px;
        cursor: pointer;
        &:hover {
          background-color: rgba(0, 0, 0, 0.05)
        }
        td {
          font-size: 14px;
          color: #9B9B9B;
          text-align: center;
          vertical-align: middle;
          .item-box {
            height: 60px;
            display: inline-flex;
            padding: 5px 0;
            box-sizing: border-box;
            flex-direction: column;
            justify-content: space-between;
          }
          &:nth-child(1) {
            text-align: right;
            width: 100px;
            img {
              width: 60px;
              height: 60px;
              margin-right: 10px;
            }
          }
          &:nth-child(2) {
            text-align: left;
            max-width: 80px;
            .title {
              font-family: PingFangSC-Medium;
              color: #000;
            }
          }
          &:nth-child(3) {
            .content {
              color: #F5A623;
            }
          }
          &:nth-child(4) {
            .content {
              color: #FFCF81;
              line-height: 22px;
              padding: 0 8px;
              background-color: #4A4A4A;
              border-radius: 2px;
            }
          }
          &:nth-child(5) {
            .content {
              color: #F5A623;
            }
          }
        }
      }
    }
  }
  .bottom-btn {
    width: 100%;
    height: 54px;
    line-height: 54px;
    text-align: center;
    background-color: #D8D8D8;
    color: #FFF;
    cursor: pointer;
  }
}
</style>
