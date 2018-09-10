<template>
  <div class="side-bar">
    <el-menu
      class="menu"
      ref="menu"
      :default-active="$route.fullPath"
      :collapse="isCollapsed"
      router>
      <el-menu-item v-for="(item, index) in menu" :index="'/' + language + item.index" :class="item.classes" :key="index">
        <i :class="item.icon"></i>
        <span slot="title">{{ item.text }}</span>
      </el-menu-item>
    </el-menu>
    <div class="build-info" v-if="!isCollapsed" :title="date">{{ version }} ({{ build }})</div>
    <div class="side-bar-toggle" @click="toggleSideBar">
      <div :class="['side-bar-toggle-inner', { 'collapsed': isCollapsed }]">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import Config from '@/config.js';
  import BuildInfo from '@/version';
  import MenuUtils from '@mixins/menu/utils.js';

  export default {
    name: 'side-bar',

    mixins: [ MenuUtils ],

    computed: {
      ...mapGetters([
        'language'
      ]),

      menu() {
        return [
          {
            text: this.trans('base.navigation.home'),
            icon: 'el-icon-lpa-home',
            classes: '',
            index: ''
          },
          {
            text: this.trans('base.navigation.projects'),
            icon: 'el-icon-lpa-projects',
            classes: '',
            index: '/projects'
          },
          {
            text: this.trans('base.navigation.learning_products'),
            icon: 'el-icon-lpa-learning-product',
            classes: '',
            index: '/learning-products'
          }
        ];
      }
    },

    data() {
      return {
        isCollapsed: false,
        date: BuildInfo.date,
        version: BuildInfo.version,
        build: BuildInfo.build
      };
    },

    watch: {
      $route: function (to) {
        // since we do not know when ElementUI will update itself
        // we just wait until the DOM is updated
        this.$nextTick(() => {
          let menu = this.$refs.menu;
          this.setActiveIndex(to, menu);
        });
      }
    },

    methods: {
      toggleSideBar() {
        this.isCollapsed = !this.isCollapsed;
        this.$nextTick(() => {
          // When the sidebar collapses-expands, ElementUI re-render the menu
          // because of that, we need to wait a bit so that it finishes rendering
          // This only applies to child pages that we want the sidebar to reflect its activeState
          // e.g.: current page: fr/projects/create, sidebar activeIndex: fr/projects
          let menu = this.$refs.menu;
          this.setActiveIndex(this.$route, menu);
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
  @import '~@sass/abstracts/vars';

  $side-bar-width: 300px;
  $side-bar-collapsed-width: 64px;
  $side-bar-fill: #322f43;
  $side-bar-active-item-border: #e80a24;

  .side-bar {
    width: 100%;
    height: 100%;
    &-toggle {
      position: absolute;
      top: -50px;
      width: $side-bar-collapsed-width;
      height: 50px;
      cursor: pointer;
      transition: $bg-color-transition-base;
      &:hover {
        transition: $bg-color-transition-base;
        background-color: #322f43;
      }
      &-inner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: $--index-top;
        width: 18px;
        height: 14px;
        span {
          position: absolute;
          left: 0;
          width: 100%;
          height: 2px;
          background-color: $--color-white;
          border-radius: 4px;
          &:nth-of-type(1) {
            top: 0;
          }
          &:nth-of-type(2) {
            top: 50%;
            // when menu is expanded, translate 2nd bar on the right
            left: 5px;
            transform: translateY(-50%);
          }
          &:nth-of-type(3) {
            bottom: 0;
          }
        }
        &.collapsed span:nth-of-type(2) {
          // translate the 2nd bar to the left when the menu is collapsed
          left: 0px;
        }
      }

      &-inner, &-inner span {
        display: inline-block;
        transition: all .4s;
        box-sizing: border-box;
      }
    }
  }

  .menu {
    user-select: none;
    height: 100%;
    border: none;
    background-color: $side-bar-fill;
    &:not(.el-menu--collapse) {
      width: $side-bar-width;
    }
    span {
      text-decoration: none;
      height: 100%;
      display: inline-block;
    }
    span, i {
      transition: $--all-transition;
      vertical-align: middle !important;
      color: $--color-white;
      opacity: 0.8;
    }
    i {
      width: $svg-icons-size-small !important;
    }
    .el-menu-item {
      transition: $--all-transition;
      &:hover, &:focus {
        transition: $--all-transition;
        background-color: mix($--color-black, $side-bar-fill, 25%);
        span, i {
          transition: $--all-transition;
          color: $--color-white;
          opacity: 1;
        }
      }
      &.is-active {
        background-color: mix($--color-black, $side-bar-fill, 25%) !important;
        &:after {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          width: 4px;
          height: 100%;
          background-color: $side-bar-active-item-border !important;
          transition: $--all-transition;
        }
        span, i {
          color: $--color-white;
          opacity: 1;
        }
      }
    }
  }

  .build-info {
    position: absolute;
    bottom: 10px;
    width: 100%;
    text-align: center;
    color: #7d7990;
    cursor: default;
  }
</style>
