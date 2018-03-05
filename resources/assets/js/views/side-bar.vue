<template>
  <div class="content sideBar">
    <el-menu ref="app" class="app" :default-active="$route.fullPath" key="app" router>
      <el-menu-item v-for="(item, index) in app" :index="'/' + language + item.index" :class="item.classes" :key="index">
        <i :class="item.icon"></i>
        <a href="#" @click.prevent>{{ item.text }}</a>
      </el-menu-item>
    </el-menu>

    <transition name="slide">
      <el-menu v-if="isAdminBarShown" ref="admin" class="admin" :default-active="$route.fullPath" key="admin" router>
        <el-menu-item-group title="Administration">
          <el-menu-item v-for="(item, index) in admin" :index="'/' + language + item.index" :class="item.classes" :key="index">
            <i :class="item.icon"></i>
            <a href="#" @click.prevent>{{ item.text }}</a>
          </el-menu-item>
        </el-menu-item-group>
      </el-menu>
    </transition>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    name: 'side-bar',
    computed: {
      ...mapGetters([
        'language',
        'isAdminBarShown'
      ]),

      app() {
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
      },
      admin() {
        return [
          {
            text: this.trans('navigation.admin_dashboard'),
            icon: 'el-icon-menu',
            classes: '',
            index: '/admin'
          },
          {
            text: this.trans('navigation.admin_user_list'),
            icon: 'el-icon-menu',
            classes: '',
            index: '/admin/users'
          }
        ];
      }
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
        let menu = this.isAdminBarShown ? this.$refs.admin : this.$refs.app;
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
  .sideBar {
    border-right: solid 1px #e6e6e6;
    height: 100%;
    overflow: hidden;
  }

  .app, .admin {
    position: absolute;
    user-select: none;
    width: 100%;
    height: 100%;
    a {
      text-decoration: none;
      height: 100%;
      width: 100%;
      display: inline-block;
    }
    li.el-menu-item.is-active a {
      color: $--color-primary;
    }
  }
  .admin {
    z-index: 1000;
    border-right: 1px solid #313131;
    background-color: #efefef;
    box-sizing: border-box;
  }
</style>
