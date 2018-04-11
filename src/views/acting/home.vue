<template>
  <div class="acting-home">
    <el-steps :active="active" align-center>
      <el-step title="上传"></el-step>
      <el-step title="确认"></el-step>
      <el-step title="发放"></el-step>
    </el-steps>

    <step1 v-if="active === 0" @finished="finishStep1"></step1>
    <step2 v-if="active === 1" :orderData="orderData" @finished="finishStep2"></step2>
    <step3 v-if="active === 2" :taskId='taskId' @finished="handleFinished(2)"></step3>
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
      active: 0,
      orderData: {
        tokenId: 0,
        tokenSymbol: '',
        logoUrl: '',
        totalAmount: 0,
        totalCount: 0
      },
      taskId: 0
    }
  },
  computed: {
    ...mapState({
      path: state => state.route.path
    })
  },
  methods: {
    finishStep1 (data) {
      this.orderData.tokenId = data.tokenId
      this.orderData.tokenSymbol = data.tokenSymbol
      this.orderData.logoUrl = data.logoUrl
      this.orderData.totalAmount = data.totalAmount
      this.orderData.totalCount = data.totalCount
      this.active = 1
    },
    finishStep2 (data) {
      this.taskId = data.taskId
      this.active = 2
    },
    handleFinished (index) {
      if (index < 2) {
        this.active = index + 1
      } else {
        this.active = 0
      }
    }
  }
}
</script>

<style lang="scss">
</style>
