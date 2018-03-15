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
  $side-bar-width: 300px;
  $side-bar-toggle-border-width: 1px * 2; // left right
  $side-bar-toggle-border-padding-y: 4px * 2; // up down
  $side-bar-toggle-font-size: 18px;
  // -0px gives us a negative value,
  // then we add the values needed to determine the half width of the button
  $side-bar-toggle-right: calc(-0px -
    (#{$side-bar-toggle-font-size} + #{$side-bar-toggle-border-padding-y} + #{$side-bar-toggle-border-width}) / 2
  );
  .side-bar {
    width: 100%;
    height: 100%;
    &-toggle {
      position: absolute;
      top: 50%;
      right: $side-bar-toggle-right;
      z-index: $--index-top;
      padding: 10px #{$side-bar-toggle-border-padding-y / 2} !important;
      border-radius: 20% !important;
      i {
        font-size: $side-bar-toggle-font-size;
        font-weight: bold;
      }
    }
  }

  .menu {
    user-select: none;
    height: 100%;
    &:not(.el-menu--collapse) {
      width: $side-bar-width;
    }
    a {
      text-decoration: none;
      width: 100%;
      height: 100%;
      display: inline-block;
      color: initial;
    }
    li.el-menu-item.is-active a {
      color: $--color-primary;
    }
  }
</style>
