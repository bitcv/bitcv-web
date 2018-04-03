<template>
  <ul class="pagination">
    <li :class="{disabled: prevDisabled}">
      <span v-if="prevDisabled"><slot name="prev"><span class="icon-bcv icon-arrow-left"></span></slot></span>
      <a v-else href="javascript:;" @click="handlePrev"><slot name="prev"><span class="icon-bcv icon-arrow-left"></span></slot></a>
    </li>
    <li v-for="(item, index) in pageList" :class="item.cls" :key="index">
      <span v-if="item.disabled" v-html="item.html"></span>
      <a v-else href="javascript:;" @click="handleJump(item.html)" v-html="item.html"></a>
    </li>
    <li :class="{disabled: nextDisabled}">
      <span v-if="nextDisabled"><slot name="next"><span class="icon-bcv icon-arrow-right"></span></slot></span>
      <a v-else href="javascript:;" @click="handleNext"><slot name="next"><span class="icon-bcv icon-arrow-right"></span></slot></a>
    </li>
  </ul>
</template>

<script>
const validator = val => /^[0-9]+$/.test(val) && val > 0

export default {
  props: {
    total: {
      type: Number,
      default: 0
    },
    pageSize: {
      type: Number,
      default: 10
    },
    currentPage: {
      validator,
      type: Number,
      default: 1
    },
    maxPage: {
      type: Number,
      default: 5
    },
    hellipText: {
      type: String,
      default: '&hellip;'
    }
  },
  computed: {
    pageCount () {
      return Math.ceil(this.total / this.pageSize)
    },
    prevDisabled () {
      return this.pageCount === 0 || this.currentPage === 1
    },
    nextDisabled () {
      return this.pageCount === 0 || this.currentPage === this.pageCount
    },
    pageList () {
      let {currentPage, maxPage, pageCount, hellipText} = this
      let list = []

      // 首页
      list.push({
        disabled: currentPage === 1,
        cls: currentPage === 1 ? 'active' : '',
        html: 1
      })

      let start, end
      let p0 = Math.floor(maxPage / 2)
      // 首页 + 省略至少2页 + 中间页的一半
      let p1 = 1 + 2 + p0

      if (currentPage >= p1) {
        start = currentPage - p0
        // 前置省略号
        list.push({
          disabled: true,
          cls: 'hellip',
          html: hellipText
        })
      } else {
        start = 2
      }
      let p2 = currentPage + p0
      if (p2 < pageCount) {
        end = p2
      } else {
        end = pageCount - 1
      }

      // 页码列表
      for (let i = start; i <= end; i++) {
        let isCurrent = currentPage === i

        list.push({
          disabled: isCurrent,
          cls: isCurrent ? 'active' : '',
          html: i
        })
      }

      if (end < pageCount - 1) {
        // 后置省略
        list.push({
          disabled: true,
          cls: 'hellip',
          html: hellipText
        })
      }

      if (pageCount > 1) {
        list.push({
          disabled: currentPage === pageCount,
          cls: currentPage === pageCount ? 'active' : '',
          html: pageCount
        })
      }

      return list
    }
  },
  methods: {
    handlePrev () {
      this.handleJump(this.currentPage - 1)
    },
    handleNext () {
      this.handleJump(this.currentPage + 1)
    },
    handleJump (page) {
      this.$emit('onPageClick', page)
    }
  }
}
</script>

<style lang="scss">
@import '~@/styles/variables';

.pagination {
  margin-top: 0;
  border-radius: 0;
  & > li > a,
  & > li > span {
    width: 30px;
    height: 30px;
    margin: 0 5px;
    padding: 0;
    line-height: 28px;
    text-align: center;
    white-space: nowrap;
    font-size: 14px;
    color: $gray-darker;
    border-radius: 50%;
    overflow: hidden;
  }
  & > li:first-child > a,
  & > li:first-child > span,
  & > li:last-child > a,
  & > li:last-child > span {
    border-radius: 50%;
  }

  & > li > a:focus,
  & > li > a:hover,
  & > li > span:focus,
  & > li > span:hover {
    color: $primary-color;
  }

  & > .active > a,
  & > .active > a:focus,
  & > .active > a:hover,
  & > .active > span,
  & > .active > span:focus,
  & > .active > span:hover {
    background-color: $primary-color;
    border-color: $primary-color;
  }

  & > .disabled > a,
  & > .disabled > a:focus,
  & > .disabled > a:hover,
  & > .disabled > span,
  & > .disabled > span:focus,
  & > .disabled > span:hover {
    color: $gray-dark;
  }

  & > .hellip > a,
  & > .hellip > a:focus,
  & > .hellip > a:hover,
  & > .hellip > span,
  & > .hellip > span:focus,
  & > .hellip > span:hover {
    color: $gray-darker;
    background-color: lighten($gray, 15%);
  }
}
</style>
