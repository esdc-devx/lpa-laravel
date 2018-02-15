<template>
  <div class="content">
    <el-menu v-if="!adminbarShown" ref="sideBar" class="sideBar" :default-active="$route.fullPath" key="sidebar" router>
      <el-menu-item v-for="(item, index) in app" :index="'/' + language + item.index" :class="item.classes" :key="index">
        <i :class="item.icon"></i>
        <a href="#" @click.prevent>{{ item.text }}</a>
      </el-menu-item>
    </el-menu>

    <transition name="slide" mode="in-out">
      <el-menu v-if="adminbarShown" ref="adminBar" class="adminBar" :default-active="$route.fullPath" key="adminBar" router>
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

  // this is the max level number the sidebar understands
  // the longuest sidebar's link's path split
  // example url: /:lang/users/create
  const maxLevelDeep = 4;

  export default {
    name: 'side-bar',
    computed: {
      ...mapGetters([
        'language',
        'adminbarShown'
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
          },
          {
            text: this.trans('navigation.admin_user_create'),
            icon: 'el-icon-menu',
            classes: '',
            index: '/admin/users/create'
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
        // example url: /:lang/projects/:id
        // by splicing the path, we can determine on what page the user is
        // based on the sidebar's link's paths
        let route = Object.assign({}, to);
        let pathArray = route.fullPath.split('/');
        pathArray.splice(maxLevelDeep, pathArray.length);
        let sidebar = this.$refs.sideBar || this.$refs.adminBar;
        sidebar.activeIndex = pathArray.join('/');
      }
    },
    mounted() {
      this.setActiveIndex(this.$route);
    }
  };
</script>

<style lang="scss" scoped>
  @import '../../sass/vendors/elementui/vars';
  .content {
    border-right: solid 1px #e6e6e6;
    height: 100%;
    overflow: hidden;
  }

  .sideBar, .adminBar {
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
  .adminBar {
    z-index: 1000;
    border-right: 1px solid;
    background-color: #efefef;
    box-sizing: border-box;
  }
</style>
