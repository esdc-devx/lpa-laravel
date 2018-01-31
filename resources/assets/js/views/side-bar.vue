<template>
  <el-menu ref="sideBar" class="sideBar" :default-active="$route.fullPath" router>
    <el-menu-item :index="'/' + lang">
      <i class="el-icon-menu"></i>
      <a href="#" @click.prevent>{{ trans('navigation.home') }}</a>
    </el-menu-item>
    <el-menu-item :index="'/' + lang + '/dashboard'" class="disabled">
      <i class="el-icon-menu"></i>
      <span>{{ trans('navigation.dashboard') }}</span>
    </el-menu-item>
    <el-menu-item :index="'/' + lang + '/projects'">
      <i class="el-icon-menu"></i>
      <a href="#" @click.prevent>{{ trans('navigation.projects') }}</a>
    </el-menu-item>
    <el-menu-item :index="'/' + lang + '/learning-products'" class="disabled">
      <i class="el-icon-menu"></i>
      <a href="#" @click.prevent>{{ trans('navigation.learning_products') }}</a>
    </el-menu-item>
    <el-menu-item :index="'/' + lang + '/non-learning-products'" class="disabled">
      <i class="el-icon-menu"></i>
      <a href="#" @click.prevent>{{ trans('navigation.non_learning_products') }}</a>
    </el-menu-item>
  </el-menu>
</template>

<script>
  export default {
    name: 'side-bar',
    computed: {
      lang() {
        return this.$store.getters.language;
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
        // this is the max level number the sidebar understands
        let maxLevelDeep = 3;
        // example url: /:lang/projects/:id
        // by splicing the path, we can determine on what page the user is
        // based on the sidebar's link's paths
        let route = Object.assign({}, to);
        let pathArray = route.fullPath.split('/');
        pathArray.splice(maxLevelDeep, pathArray.length);
        this.$refs.sideBar.activeIndex = pathArray.join('/');
      }
    },
    mounted() {
      this.setActiveIndex(this.$route);
    }
  };
</script>

<style lang="scss" scoped>
  @import '../../sass/vendors/element-variables';
  .sideBar {
    height: 100%;
    user-select: none;
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
</style>
