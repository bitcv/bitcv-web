<template>
  <div class="project-main" v-loading="loading">
    <div class="add-box">
      <el-button type="warning" plain>+添加</el-button>
    </div>
  </div>
</template>
<script type="text/javascript">
import VueQr from 'vue-qr'

export default {
  components: {
    VueQr
  },
  data () {
    return {
      loading: false
    }
  }
}

</script>
<style lang="scss">
.project-main {
  .add-box {
    overflow: hidden;
    text-align: right;
    margin: 20px 0 25px;
  }
}

</style>
