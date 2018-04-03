<template>
  <div class="page-panel" v-if="pageCount > 1">
    <span class="pageno" :class="{cur: pageno===index}" v-for="index in showCount" :key="index" @click="changePage(index)">{{index}}</span>
  </div>
</template>

<script>
export default {
  props: {
    pageCount: Number
  },
  data () {
    return {
      pageno: 1
    }
  },
  watch: {
    pageCount () {
      this.pageno = 1
    }
  },
  computed: {
    showCount () {
      return this.pageCount > 10 ? 10 : this.pageCount
    }
  },
  methods: {
    changePage (pageno) {
      this.pageno = pageno
      this.$emit('changePage', pageno)
    }
  }
}
</script>

<style lang="scss" scoped>
.page-panel {
  width: 100%;
  text-align: center;
  font-size: 0;
  padding: 10px 0;
  .pageno {
    display: inline-block;
    width: 20px;
    height: 20px;
    color: #4A4A4A;
    font-size: 12px;
    border-radius: 50%;
    line-height: 20px;
    margin: 0 10px;
    cursor: pointer;
    &.cur {
      background-color: #EAEAEA;
    }
  }
}
</style>
