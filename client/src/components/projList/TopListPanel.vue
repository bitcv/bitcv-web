<template>
  <div class="top-list-panel">
    <h3 class="panel-title">{{ listData.title }}</h3>
    <ul class="list-container">
      <li class="list-item" v-for="(project, index) in projList" :key="project.projId">
        <router-link :to="{ path: 'projDetail/' + project.projId}">
          <span class="index">{{ index + 1 }}</span>
          <span class="title">{{ project.nameCn }}</span>
          <span class="count">{{ project.count }}</span>
        </router-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    listData: Object
  },
  data () {
    return {
      projList: []
    }
  },
  mounted () {
    var that = this
    this.$http.post('/api/getProjTopList', {
      type: this.listData.type,
      count: 10
    }).then(function (res) {
      var resData = res.data
      if (resData.errcode === 0) {
        that.projList = resData.data
      }
    })
  }
}
</script>

<style lang="scss" scoped>
.top-list-panel {
  box-sizing: border-box;
  width: 100%;
  background-color: #FFF;
  padding: 20px;
  .list-container {
    margin-top: 13px;
    width: 100%;
    .list-item {
      a {
        height: 34px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        .index {
          display: inline-block;
          background-color: #EAEAEA;
          width: 24px;
          height: 24px;
          border-radius: 2px;
          line-height: 24px;
          text-align: center;
          color: #FFF;
        }
        .title {
          font-size: 14px;
          line-height: 20px;
          color: #4A4A4A;
          width: 220px;
          margin-left: 10px;
        }
        .count {
          width: 60px;
          text-align: right;
          font-size: 14px;
          line-height: 20px;
          color: #9B9B9B;
        }
      }
      &:first-child .index {
        background-color: #D0021B;
      }
      &:first-child .title {
        color: #D0021B;
      }
      &:nth-child(2) .index {
        background-color: #FF6B7D;
      }
      &:nth-child(2) .title {
        color: #FF6B7D;
      }
      &:nth-child(3) .index {
        background-color: #FFCF81;
      }
      &:nth-child(3) .title {
        color: #FFCF81;
      }
    }
  }
}
</style>
