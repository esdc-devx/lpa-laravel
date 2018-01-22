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
