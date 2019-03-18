<template>
  <div id="app" v-cloak>
    <el-container>
      <el-header height="auto">
        <top-bar/>
      </el-header>

      <el-container class="body-wrap">
        <el-aside width="auto">
          <side-bar/>
        </el-aside>

        <el-container class="content-wrap"
          v-loading.lock="isMainLoading"
          element-loading-background="rgba(250, 250, 250, 0.6)">
          <el-header height="auto">
            <breadcrumb/>
          </el-header>
          <el-main>
            <main-content @app:ready.once="onAppReady"/>
          </el-main>
        </el-container>
      </el-container>
    </el-container>
  </div>
</template>

<script>
  import { mapState, mapMutations } from 'vuex';

  import TopBar from '@views/top-bar.vue';
  import SideBar from '@views/side-bar.vue';
  import Breadcrumb from '@views/breadcrumb.vue';
  import MainContent from '@views/main-content.vue';

  export default {
    name: 'app',

    components: { TopBar, SideBar, Breadcrumb, MainContent },

    computed: {
      ...mapState([
        'isMainLoading'
      ])
    },

    methods: {
      ...mapMutations([
        'hideAppLoading'
      ]),

      onAppReady() {
        this.hideAppLoading();
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  [v-cloak] {
    display: none;
  }

  #app, #app > .el-container {
    height: 100%;
    overflow: hidden;
  }

  .body-wrap {
    > .el-aside {
      position: relative;
      // this allows to make the content shift to the left
      // and take the sidebar's space when collapsed
      width: auto !important;
      overflow: visible;
      z-index: $--index-top;
    }
    .content-wrap {
      background-color: #eaf0f8;
    }
  }

  .el-main {
    position: relative;
    overflow-x: hidden;
    padding: 0;
    padding-top: 0px;
  }

  .el-header {
    padding: 0;
  }

  // IE10+
  @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
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
</style>
