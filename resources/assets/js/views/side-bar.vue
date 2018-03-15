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
  import MenuUtils from '../mixins/menu/utils.js';

  export default {
    name: 'side-bar',

    mixins: [ MenuUtils ],

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
          let menu = this.$refs.menu;
          this.setActiveIndex(to, menu);
        });
      }
    },

    mounted() {
      let menu = this.$refs.menu;
      this.setActiveIndex(this.$route, menu);
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
