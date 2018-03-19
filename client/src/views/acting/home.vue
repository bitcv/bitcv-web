<template>
  <div class="acting-home">
    <el-steps :active="active" align-center>
      <el-step title="上传"></el-step>
      <el-step title="确认"></el-step>
      <el-step title="发放"></el-step>
    </el-steps>

    <step1 v-if="active === 0" @finished="handleFinshed(0)"></step1>
    <step2 v-if="active === 1" @finished="handleFinshed(1)"></step2>
    <step3 v-if="active === 2" @finished="handleFinshed(2)"></step3>
  </div>
</template>

<script>
import {mapState} from 'vuex'
import Step1 from './home-step1'
import Step2 from './home-step2'
import Step3 from './home-step3'

export default {
  components: {
    Step1,
    Step2,
    Step3
  },
  data () {
    return {
      active: 0
    }
  },
  computed: {
    ...mapState({
      path: state => state.route.path
    })
  },
  methods: {
    handleFinshed (index) {
      if (index < 2) this.active = index + 1
    }
  }
}
</script>

<style lang="scss">
@import '~@/styles/variables';
</style>
