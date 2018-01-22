<template>
  <el-menu ref="sideBar" class="sideBar" :default-active="$route.fullPath" router>
    <el-menu-item :index="'/' + $store.getters.getLanguage">
      <i class="el-icon-menu"></i>
      <a href="#" @click.prevent>{{ trans('navigation.home') }}</a>
    </el-menu-item>
    <el-menu-item :index="'/' + $store.getters.getLanguage + '/dashboard'" class="disabled">
      <i class="el-icon-menu"></i>
      <span>{{ trans('navigation.dashboard') }}</span>
    </el-menu-item>
    <el-menu-item :index="'/' + $store.getters.getLanguage + '/projects'">
      <i class="el-icon-menu"></i>
      <a href="#" @click.prevent>{{ trans('navigation.projects') }}</a>
    </el-menu-item>
    <el-menu-item :index="'/' + $store.getters.getLanguage + '/learning-products'" class="disabled">
      <i class="el-icon-menu"></i>
      <a href="#" @click.prevent>{{ trans('navigation.learning_products') }}</a>
    </el-menu-item>
    <el-menu-item :index="'/' + $store.getters.getLanguage + '/non-learning-products'" class="disabled">
      <i class="el-icon-menu"></i>
      <a href="#" @click.prevent>{{ trans('navigation.non_learning_products') }}</a>
    </el-menu-item>
  </el-menu>
</template>

<script>
  export default {
    name: 'side-bar',
    watch: {
      '$route': function(to) {
        // since we do not know when ElementUI will update itself
        // we just wait until the DOM is updated
        this.$nextTick(() => {
          this.$refs.sideBar.activeIndex = to.fullPath;
        });
      }
    }
  };
</script>

<style lang="scss" scoped>
  @import '../../sass/vendors/element-variables';
  .sideBar {
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
