<template>
  <div id="app"
    v-loading.fullscreen.lock="isLoading"
    element-loading-background="#FFF"
    v-cloak>
    <el-container v-show="isLoading === false">
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
            <keep-alive>
              <transition name="fade" mode="out-in">
                <router-view/>
              </transition>
            </keep-alive>
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
  import EventBus from './helpers/event-bus';

  import TopBar from './views/top-bar.vue';
  import SideBar from './views/side-bar.vue';
  import Breadcrumb from './views/breadcrumb.vue';

  export default {
    name: 'app',

    components: { TopBar, SideBar, Breadcrumb },

    data() {
      return {
        isLoading: true
      }
    },

    beforeCreate() {
      EventBus.$once('App:ready', () => {
        this.isLoading = false;
      });
    }
  };
</script>

<style lang="scss">
  [v-cloak] {
    display: none;
  }
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
  .el-header {
    padding: 0;
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
