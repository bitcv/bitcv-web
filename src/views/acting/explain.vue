<template>
  <div>
    帮助说明
  </div>
</template>

<script>
import {mapState} from 'vuex'

export default {
  data () {
    return {
    }
  },
  computed: {
    ...mapState({
      path: state => state.route.path
    })
  }
}
</script>

<style lang="scss">
</style>
