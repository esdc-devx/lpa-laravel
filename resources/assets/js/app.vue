<template>
  <div id="app"
    v-loading.fullscreen.lock="isLoading"
    element-loading-background="#FFF"
    v-cloak>
    <el-container v-show="isLoading === false">
      <el-header height="auto">
        <top-bar/>
      </el-header>

      <el-container class="body-wrap">
        <el-aside width="auto">
          <side-bar/>
        </el-aside>

        <el-container class="content-wrap">
          <el-main>
            <breadcrumb/>
            <main-content/>
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
  import MainContent from './views/main-content.vue';

  export default {
    name: 'app',

    components: { TopBar, SideBar, Breadcrumb, MainContent },

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
  @import '../sass/vendors/elementui/vars';
  [v-cloak] {
    display: none;
  }
  #app, #app > .el-container {
    height: 100%;
    overflow: hidden;
  }
  .el-aside {
    position: relative;
    // this allows to make the content shift to the left and take the sidebar's space when collapsed
    width: auto !important;
    overflow: visible;
    z-index: $--index-top;
  }
  .el-main {
    position: relative;
    overflow-x: hidden;
    padding: 30px;
    padding-top: 10px;
    background-color: #fff;
  }
  .el-header {
    padding: 0;
  }

  // IE10+
  @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
    .el-main {
      // fixes a layout bug for IE11
      overflow: visible;
    }
    .body-wrap {
      .content-wrap {
        overflow-x: hidden;
        height: 100%;
      }
    }

    .el-aside, .el-menu.el-menu--collapse {
      min-width: 64px !important;
    }
  }

  hr {
    border-color: #E6E6E6;
    border-style: solid;
    margin-bottom: 20px;
  }
</style>
