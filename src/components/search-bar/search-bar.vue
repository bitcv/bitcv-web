<template>
  <form class="search-bar" @submit="onSubmit">
    <div class="input-group input-group-lg">
      <input type="text" v-model="keywords" class="form-control" :placeholder="$t('label.search_bar')" @input="onInput">
      <span class="icon-bcv icon-search"></span>
      <span class="input-group-btn">
        <button type="button" class="btn btn-primary" @click="onSubmit">{{ $t('label.search_btn') }}</button>
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
    onSubmit (e) {
      e.preventDefault()

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
