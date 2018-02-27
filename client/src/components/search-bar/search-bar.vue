<template>
  <form class="search-bar">
    <div class="input-group input-group-lg">
      <input type="text" v-model="keywords" class="form-control" placeholder="币名称、符号、项目名称" @input="onInput">
      <span class="icon-bcv icon-search"></span>
      <span class="input-group-btn">
        <button type="button" class="btn btn-primary" @click="onClick">搜索</button>
      </span>
    </div>
  </form>
</template>

<script>
export default {
  props: {
    value: String
  },
  data () {
    return {
      keywords: ''
    }
  },
  watch: {
    value: {
      handler (val) {
        this.keywords = val
      },
      immediate: true
    }
  },
  methods: {
    onInput () {
      this.$emit('input', this.keywords)
    },
    onClick () {
      this.$emit('submit')
    }
  }
}
</script>

<style lang="scss">
@import '~@/styles/variables';

.search-bar {
  .input-group {
    .icon-search {
      position: absolute;
      left: 14px;
      top: 14px;
      z-index: 5;
      font-size: 18px;
      line-height: 1;
      color: $gray-dark;
    }

    .form-control {
      padding-left: 40px;
      // box-shadow: none;
    }
  }
}
</style>
