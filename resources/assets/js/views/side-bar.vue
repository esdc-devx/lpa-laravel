<template>
  <div class="side-bar">
    <el-button class="side-bar-toggle" size="mini" type="primary" plain round :icon="collapsedIcon" @click="isCollapsed = !isCollapsed"></el-button>
    <el-menu class="menu" ref="menu" :default-active="$route.fullPath" :collapse="isCollapsed" router>
      <el-menu-item v-for="(item, index) in menu" :index="'/' + language + item.index" :class="item.classes" :key="index">
        <i :class="item.icon"></i>
        <span slot="title"><a href="#" @click.prevent>{{ item.text }}</a></span>
      </el-menu-item>
    </el-menu>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    name: 'side-bar',

    computed: {
      ...mapGetters([
        'language'
      ]),

      collapsedIcon() {
        return this.isCollapsed ? 'el-icon-arrow-right' : 'el-icon-arrow-left';
      },

      menu() {
        return [
          {
            text: this.trans('navigation.home'),
            icon: 'el-icon-menu',
            classes: '',
            index: ''
          },
          {
            text: this.trans('navigation.dashboard'),
            icon: 'el-icon-menu',
            classes: 'disabled',
            index: '/dashboard'
          },
          {
            text: this.trans('navigation.projects'),
            icon: 'el-icon-menu',
            classes: '',
            index: '/projects'
          },
          {
            text: this.trans('navigation.learning_products'),
            icon: 'el-icon-menu',
            classes: 'disabled',
            index: '/learning-products'
          },
          {
            text: this.trans('navigation.non_learning_products'),
            icon: 'el-icon-menu',
            classes: 'disabled',
            index: '/non-learning-products'
          }
        ];
      }
    },

    data() {
      return {
        isCollapsed: false
      };
    },

    watch: {
      '$route': function(to) {
        // since we do not know when ElementUI will update itself
        // we just wait until the DOM is updated
        this.$nextTick(() => {
          this.setActiveIndex(to);
        });
      }
    },

    methods: {
      setActiveIndex(to) {
        let menu = this.$refs.menu;
        // because of the $refs that cannot be reactive, we need to compare its items omitting the language
        let items = _.mapKeys(menu.items, (value, key) => {
          return this.removeLanguageFromString(key);
        });

        // remove the language from the route's fullPath as well
        let route = Object.assign({}, to);
        route.fullPath = this.removeLanguageFromString(route.fullPath);

        // check if the current page is found in the items paths
        if (items[route.fullPath]) {
          menu.activeIndex = items[route.fullPath].index;
        } else {
          // if not, just pop last index of path and try again
          let pathArray = route.fullPath.split('/');
          pathArray.pop();
          route.fullPath = pathArray.join('/');
          // fail safe in case we get a path that the sidebar doesn't recognize
          if (route.fullPath === '' || route.fullPath === '/') {
            return;
          }
          this.setActiveIndex(route);
        }
      },

      removeLanguageFromString(str) {
        if (str.match(/\/(en|fr)\//)) {
          let strParsed = str.split('/');
          // remove the language
          strParsed.splice(1, 1);
          // make sure that no empty strings are returned
          return strParsed.join('/') || '/';
        }
        return str;
      }
    },

    mounted() {
      this.setActiveIndex(this.$route);
    }
  };
</script>

<style lang="scss">
  @import '../../sass/vendors/elementui/vars';
  .side-bar {
    width: 100%;
    height: 100%;
    &-toggle {
      position: absolute;
      top: 50%;
      right: -13px;
      z-index: 1000;
      padding: 10px 4px !important;
      border-radius: 20% !important;
      i {
        font-size: 18px;
        font-weight: bold;
      }
    }
  }

  .menu {
    user-select: none;
    height: 100%;
    &:not(.el-menu--collapse) {
      width: 300px;
    }
    a {
      text-decoration: none;
      height: 100%;
      width: 100%;
      display: inline-block;
      color: initial;
    }
    li.el-menu-item.is-active a {
      color: $--color-primary;
    }
  }
</style>
