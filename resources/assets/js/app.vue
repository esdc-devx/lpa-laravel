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
        <el-aside>
          <side-bar/>
        </el-aside>

        <el-container class="content-wrap">
          <el-main>
            <breadcrumb/>
            <keep-alive>
              <transition :name="transitionName">
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
  import { mapGetters } from 'vuex';
  import EventBus from './helpers/event-bus';

  import TopBar from './views/top-bar.vue';
  import SideBar from './views/side-bar.vue';
  import Breadcrumb from './views/breadcrumb.vue';

  export default {
    name: 'app',

    components: { TopBar, SideBar, Breadcrumb },

    computed: {
      ...mapGetters([
        'language'
      ])
    },

    data() {
      return {
        isLoading: true,
        transitionName: 'fade'
      }
    },

    watch: {
      // handles the transition when changing pages
      $route(to, from) {
        // example url transitions:
        //    /en/projects => /en/projects/1:       swipe
        //    /en/projects/1 => /en/projects:       swipe
        //    /en/projects/1 => /en/admin/users:    fade
        //    /en/admin/users => /en:               fade

        const toDepth = to.path.split('/').length;
        const fromDepth = from.path.split('/').length;
        let isSameLevel = toDepth === fromDepth;
        let isFromOrToHome = from.path === '/' || from.path === `/${this.language}` || to.path === `/${this.language}`;

        // check for direct hierarchy
        let isParentChild = this.isParentChild(to, from);
        // when same level, => fade
        // when parent-child, => swipe
        if (isSameLevel || !isParentChild || isFromOrToHome) {
          this.transitionName = 'fade';
        } else {
          this.transitionName = toDepth < fromDepth ? 'swipe-right' : 'swipe-left';
        }
      }
    },

    methods: {
      isParentChild(to, from) {
        const toSplit = to.path.split('/');
        const fromSplit = from.path.split('/');
        const toDepth = toSplit.length;
        const fromDepth = fromSplit.length;

        // look up max 2 level up in case we get url change like so:
        // /en/admin/users => /en/admin/users/edit/3
        if (fromSplit[fromDepth - 1] === toSplit[toDepth - 2] || toSplit[toDepth - 1] === fromSplit[fromDepth - 2] ||
            fromSplit[fromDepth - 1] === toSplit[toDepth - 3] || toSplit[toDepth - 1] === fromSplit[fromDepth - 3]) {
          return true;
        }
        return false;
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
  }
  .el-aside {
    position: relative;
    overflow: visible;
    z-index: $--index-top;
  }
  .el-main {
    position: relative;
    overflow-x: hidden;
    padding: 30px;
    padding-top: 10px;
  }
  .el-header {
    padding: 0;
  }

  // IE10+
  @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
    $header-height: 60px;
    // elementUI's el-aside width value
    $sidebar-width: 300px;
    .el-main {
      // fixes a layout bug for IE11
      overflow: visible;
    }
    .el-header {
      position: fixed;
      width: 100%;
      // get to the top most index + 1 so that loading overlays in components don't go over the header
      z-index: $--index-notify + 1;
    }
    .body-wrap {
      display: table;
      width: 100%;
      height: 100%;
      padding-top: $header-height;
      .el-aside {
        position: fixed;
        left: 0;
        height: 100%;
      }
      .content-wrap {
        overflow-x: hidden;
        height: 100%;
        margin-left: $sidebar-width;
      }
    }
  }

  hr {
    border-color: #E6E6E6;
    border-style: solid;
    margin-bottom: 20px;
  }
</style>
