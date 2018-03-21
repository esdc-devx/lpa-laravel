<template>
  <div class="side-bar">
    <div class="side-bar-toggle" @click="isCollapsed = !isCollapsed">
      <div class="side-bar-toggle-inner">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
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
  @import '../../sass/abstracts/vars';

  $side-bar-width: 300px;
  $side-bar-fill: #322f43;
  $side-bar-active-item-border: #e80a24;

  .side-bar {
    width: 100%;
    height: 100%;
    &-toggle {
      position: absolute;
      top: -50px;
      width: 60px;
      height: 50px;
      cursor: pointer;
      transition: $--bg-color-transition-base;
      &:hover {
        transition: $--bg-color-transition-base;
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
            transform: translateY(-50%);
          }
          &:nth-of-type(3) {
            bottom: 0;
          }
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
    a {
      text-decoration: none;
      height: 100%;
      display: inline-block;
    }
    a, i {
      transition: $--all-transition;
      color: mix($--color-white, $side-bar-fill, 75%);
      vertical-align: baseline;
    }
    .el-menu-item {
      transition: $--all-transition;
      &:hover, &:focus {
        transition: $--all-transition;
        background-color: mix($--color-black, $side-bar-fill, 25%);
        a, i {
          transition: $--all-transition;
          color: mix($--color-white, $side-bar-fill, 95%);
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
        a, i {
          color: mix($--color-white, $side-bar-fill, 95%);
        }
      }
    }
  }
</style>
