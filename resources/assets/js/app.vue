<template>
  <div id="app">
    <el-container>
      <el-header height="auto">
        <top-bar/>
      </el-header>

      <el-container>
        <el-aside>
          <side-bar/>
        </el-aside>

        <el-container>
          <el-main>
            <breadcrumb/>
            <transition name="fade" mode="out-in">
              <keep-alive>
                <router-view/>
              </keep-alive>
            </transition>
          </el-main>
        </el-container>
      </el-container>
      <!-- <el-container>
        <el-footer>Footer</el-footer>
      </el-container> -->
    </el-container>
  </div>
</template>

<script>
  import TopBar from './views/top-bar.vue';
  import SideBar from './views/side-bar.vue';
  import Breadcrumb from './views/breadcrumb.vue';

  export default {
    name: 'app',
    components: { TopBar, SideBar, Breadcrumb }
  };
</script>

<style lang="scss">
  #app, #app > .el-container {
    height: 100%;
  }
  .el-aside {
    overflow: visible;
  }
  .el-main {
    padding: 30px;
    padding-top: 10px;
  }
  @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
    .el-main {
      // fixes a layout bug for IE11
      overflow: visible;
    }
  }

  hr {
    border-color: #E6E6E6;
    border-style: solid;
    margin-bottom: 20px;
  }
</style>
