<template>
  <div id="app">
    <el-container>
      <el-header height="auto">
        <navigation/>
      </el-header>
      <el-main>
        <transition name="fade" mode="out-in">
          <keep-alive>
            <router-view/>
          </keep-alive>
        </transition>
      </el-main>
      <el-footer>Footer</el-footer>
    </el-container>
  </div>
</template>

<script>
import Navigation from './views/navigation.vue';
export default {
  name: 'app',
  components: { Navigation }
};
</script>

<style lang="scss">
#app {
  .content {
    padding: 0 2em;
  }
}
</style>
