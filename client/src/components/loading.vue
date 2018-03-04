<template>
  <div>
    <div class="loading" v-if="load">
      <div class="loading_bg"></div>
      <div class="loader">
        <div class="loader-inner ball-spin-fade-loader">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    load: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    load (val) {
      // console.log(val)
    }
  }
}
</script>

<style lang="scss" scoped>
.loading{
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  z-index: 10000;
  .loading_bg{
    border-radius: 10px;
    background: #333;
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 5;
    opacity: 0.8;
  }

  .loader {
    box-sizing: border-box;
    display: flex;
    flex: 0 1 auto;
    flex-direction: column;
    flex-grow: 1;
    flex-shrink: 0;
    flex-basis: 25%;
    max-width: 100%;
    height: 100%;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 100002;
  }
  .ball-spin-fade-loader {
    position: relative;
  }
  .ball-spin-fade-loader > div:nth-child(1) {
    top: 25px;
    left: 0;
    animation: ball-spin-fade-loader 1s 0s infinite linear;
  }
  .ball-spin-fade-loader > div:nth-child(2) {
    top: 17.04545px;
    left: 17.04545px;
    animation: ball-spin-fade-loader 1s 0.12s infinite linear;
  }
  .ball-spin-fade-loader > div:nth-child(3) {
    top: 0;
    left: 25px;
    animation: ball-spin-fade-loader 1s 0.24s infinite linear;
  }
  .ball-spin-fade-loader > div:nth-child(4) {
    top: -17.04545px;
    left: 17.04545px;
    animation: ball-spin-fade-loader 1s 0.36s infinite linear;
  }
  .ball-spin-fade-loader > div:nth-child(5) {
    top: -25px;
    left: 0;
    animation: ball-spin-fade-loader 1s 0.48s infinite linear;
  }
  .ball-spin-fade-loader > div:nth-child(6) {
    top: -17.04545px;
    left: -17.04545px;
    animation: ball-spin-fade-loader 1s 0.6s infinite linear;
  }
  .ball-spin-fade-loader > div:nth-child(7) {
    top: 0;
    left: -25px;
    animation: ball-spin-fade-loader 1s 0.72s infinite linear;
  }
  .ball-spin-fade-loader > div:nth-child(8) {
    top: 17.04545px;
    left: -17.04545px;
    animation: ball-spin-fade-loader 1s 0.84s infinite linear;
  }
  .ball-spin-fade-loader > div {
    background-color: #fff;
    width: 15px;
    height: 15px;
    border-radius: 100%;
    margin: 2px;
    animation-fill-mode: both;
    position: absolute;
  }

  @keyframes ball-spin-fade-loader {
    50% {
      opacity: 0.3;
      transform: scale(0.4);
    }

    100% {
      opacity: 1;
      transform: scale(1);
    }
  }
}
</style>
