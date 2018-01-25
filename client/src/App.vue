<template>
  <div id="app">
    <v-header></v-header>
    <div class="main-container">
      <router-view/>
    </div>
  </div>
</template>

<script>
import Header from '@/components/header/Header.vue'

export default {
  name: 'App',
  components: {
    vHeader: Header
  }
}
</script>

<style lang="scss"> @import "./App.scss"; </style>

<style lang="scss" scoped>
.main-container {
  width: 100%;
  background-color: #F2F2F2;
  margin: 40px 0 76px;
  display: flex;
  justify-content: center;
}
</style>
