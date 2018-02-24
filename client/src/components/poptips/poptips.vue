<template>
  <div class="poptips" :class="{'poptips-show': value}">
    <div class="container">
      <slot>
        <i class="icon poptips-icon"></i>
        <span>{{ message }}</span>
      </slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    message: String,
    value: Boolean
  },
  data () {
    return {
      show: false
    }
  },
  watch: {
    value: {
      handler (val) {
        this.show = val
      },
      immediate: true
    }
  }
}
</script>

<style lang="scss">
@import '~@/styles/variables';

.poptips {
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  z-index: 100;
  padding: 10px 0;
  font-size: 14px;
  text-align: center;
  color: $gray-darker;
  background-color: #fce3bf;

  opacity: 0;
  visibility: hidden;
  transition: all .35s ease-in;
  transform: translateY(-40px);

  &.poptips-show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
  }
}
.poptips-icon {
  display: inline-block;
  vertical-align: middle;
  width: 20px;
  height: 20px;
  margin-right: 10px;
  background-color: transparent;
  background-repeat: no-repeat;
  background-position: center;
  background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB0PSIxNTE5NDU1ODI3NzM4IiBjbGFzcz0iaWNvbiIgc3R5bGU9IiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHAtaWQ9IjE5MTYiIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iNDgiIGhlaWdodD0iNDgiPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PC9zdHlsZT48L2RlZnM+PHBhdGggZD0iTTUxMiAwYy0yODIuNTcyNzA0IDAtNTExLjY0Mzg4OSAyMjkuMDcxMTg2LTUxMS42NDM4ODkgNTExLjY0Mzg4OXMyMjkuMDcxMTg2IDUxMS42NDM4ODkgNTExLjY0Mzg4OSA1MTEuNjQzODg5IDUxMS42NDM4ODktMjI5LjA3MTE4NiA1MTEuNjQzODg5LTUxMS42NDM4ODlTNzk0LjU3MjcwNCAwIDUxMiAwek01MTIgOTU4LjgzNDgzOGMtMjQ2Ljk3Njk5OCAwLTQ0Ny4xOTA5NDktMjAwLjIxMzk1MS00NDcuMTkwOTQ5LTQ0Ny4xOTA5NDlTMjY1LjAyMzAwMiA2NC40NTI5NDEgNTEyIDY0LjQ1Mjk0MXM0NDcuMTkwOTQ5IDIwMC4yMTM5NTEgNDQ3LjE5MDk0OSA0NDcuMTkwOTQ5Uzc1OC45NzY5OTggOTU4LjgzNDgzOCA1MTIgOTU4LjgzNDgzOHoiIGZpbGw9IiNmNTg0MjciIHAtaWQ9IjE5MTciPjwvcGF0aD48cGF0aCBkPSJNNTExLjY5NjA3OCA2NzEuNzE0NTYyTDUxMS42OTYwNzggNjcxLjcxNDU2MmMtMTcuNjYxMjQyIDAtMzEuOTc4MzE5LTE0LjMxNzA3Ny0zMS45NzgzMTktMzEuOTc4MzE5TDQ3OS43MTc3NTkgMjU1LjkyMjc0YzAtMTcuNjYxMjQyIDE0LjMxNzA3Ny0zMS45NzgzMTkgMzEuOTc4MzE5LTMxLjk3ODMxOWwwIDBjMTcuNjYxMjQyIDAgMzEuOTc4MzE5IDE0LjMxNzA3NyAzMS45NzgzMTkgMzEuOTc4MzE5bDAgMzgzLjgxMjQ3OUM1NDMuNjc0Mzk3IDY1Ny4zOTY0NjEgNTI5LjM1NzMyIDY3MS43MTQ1NjIgNTExLjY5NjA3OCA2NzEuNzE0NTYyeiIgZmlsbD0iI2Y1ODQyNyIgcC1pZD0iMTkxOCI+PC9wYXRoPjxwYXRoIGQ9Ik01MTEuNjc5NzA1IDc2Ny42OTI0OTZtLTMxLjk3ODMxOSAwYTMxLjI1IDMxLjI1IDAgMSAwIDYzLjk1NjYzNyAwIDMxLjI1IDMxLjI1IDAgMSAwLTYzLjk1NjYzNyAwWiIgZmlsbD0iI2Y1ODQyNyIgcC1pZD0iMTkxOSI+PC9wYXRoPjwvc3ZnPg==);
  background-size: 100%;
}
</style>
