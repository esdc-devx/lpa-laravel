<template>
  <el-row class="admin-bar">
    <transition name="slide-down">
      <el-menu
        v-show="isAdminBarShown"
        ref="adminMenu"
        background-color="#fa5555"
        text-color="#e3e3e3"
        active-text-color="#ffffff"
        :default-active="$route.fullPath"
        mode="horizontal" router>
        <el-menu-item v-for="(item, index) in admin" :index="'/' + language + item.index" :class="item.classes" :key="index">
          <i :class="item.icon"></i>
          <a href="#" @click.prevent>{{ item.text }}</a>
        </el-menu-item>
      </el-menu>
    </transition>
  </el-row>
</template>

<script>
  import { mapGetters } from 'vuex';
  import MenuUtils from '../../mixins/menu/utils.js';

  export default {
    name: 'admin-bar',

    mixins: [ MenuUtils ],

    computed: {
      ...mapGetters({
        language: 'language',
        isAdminBarShown: 'isAdminBarShown'
      }),

      admin() {
        return [
          {
            text: this.trans('base.navigation.admin_dashboard'),
            icon: 'el-icon-menu',
            classes: '',
            index: '/admin'
          },
          {
            text: this.trans('base.navigation.admin_user_list'),
            icon: 'el-icon-menu',
            classes: '',
            index: '/admin/users'
          }
        ];
      }
    },

    watch: {
      // cannot use arrow functions here
      // as Vuejs will think that 'this' refers to the function
      // instead of Vuejs instance
      $route: function (to) {
        // since this is a 3rd party library,
        // we do not know when it will update itself
        // so just wait until the DOM is updated
        this.$nextTick(() => {
          let menu = this.$refs.adminMenu;
          this.setActiveIndex(to, menu);
        });
      }
    },

    methods: {
      toggleAdminBar() {
        this.$store.dispatch('toggleAdminBar');
      }
    },

    mounted() {
      let menu = this.$refs.adminMenu;
      this.setActiveIndex(this.$route, menu);
    }
  };
</script>

<style lang="scss">
  @import '../../../sass/abstracts/vars';

  $admin-bar-height: 45px;
  $admin-bar-fill: $--color-danger;

  .admin-bar {
    .el-menu {
      padding: 0 20px;
      display: flex;
      justify-content: flex-end;
      border-bottom: 0;
      &-item:not(.is-active) {
        i, a {
          color: #f3f3f3;
        }
      }

      &-item {
        border: none !important;
        height: $admin-bar-height;
        display: flex;
        align-items: center;
        &.is-active {
          background-color: mix($--color-black, darken($admin-bar-fill, 5%), 20%) !important;
        }
        a {
          text-decoration: none;
        }
      }
    }
  }

  // Slide Down-Up
  .slide-down-enter-active, .slide-down-leave-active {
    transition: $--all-transition;
  }
  .slide-down-enter-to, .slide-down-leave {
    height: $admin-bar-height;
    overflow: hidden;
  }
  .slide-down-enter, .slide-down-leave-to {
    height: 0;
    opacity: 0;
  }
</style>
