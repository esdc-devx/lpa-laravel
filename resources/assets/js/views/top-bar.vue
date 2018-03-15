<template>
  <div>
    <el-row class="top-bar" type="flex">
      <el-col :span="8" class="app-title-col">
        <div><h1><router-link :to="'/' + language">{{ trans('navigation.app_name') }}</router-link></h1></div>
      </el-col>
      <el-col :span="16" class="nav-col">
        <nav>
          <el-menu ref="topMenu" :default-active="$route.fullPath" background-color="#313131" text-color="#fff" active-text-color="#4bd5ff" class="top-menu" mode="horizontal" router>
            <el-submenu index="1">
              <template slot="title">{{ user.name }}</template>
              <el-menu-item :index="'/' + language + '/profile'"><span>{{ trans('navigation.profile') }}</span></el-menu-item>
              <el-menu-item :index="'/' + language + '/logout'" @click="logout()"><span>{{ trans('navigation.logout') }}</span></el-menu-item>
            </el-submenu>
            <el-menu-item :index="'/' + language + '/help'" class="disabled"><span tabindex="-1">{{ trans('navigation.help') }}</span></el-menu-item>
            <el-menu-item index="" @click="setLanguage">
              <span v-if="toggledLang === 'en'">English</span>
              <span v-else>Français</span>
            </el-menu-item>
            <el-menu-item index="" v-if="user.isAdmin" @click="toggleAdminBar">
              <span>
                <i :class="['el-icon-setting', { 'active' : isAdminBarShown} ]"></i>
              </span>
            </el-menu-item>
          </el-menu>
        </nav>
      </el-col>
    </el-row>
    <el-row class="admin-bar">
      <transition name="slide-down">
        <el-menu
          v-if="isAdminBarShown"
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
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import MenuUtils from '../mixins/menu/utils.js';

  export default {
    name: 'top-bar',

    mixins: [ MenuUtils ],

    computed: {
      ...mapGetters({
        language: 'language',
        isAdminBarShown: 'isAdminBarShown',
        user: 'users/current'
      }),

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

    data() {
      let currentLang = this.$store.getters.language;
      let toggledLang = this.getSwitchedLang(currentLang);
      return {
        currentLang,
        toggledLang
      }
    },

    watch: {
      // cannot use arrow functions here
      // as Vuejs will think that 'this' refers to the function
      // instead of Vuejs instance
      '$route': function(to) {
        // since this is a 3rd party library,
        // we do not know when it will update itself
        // so just wait until the DOM is updated
        this.$nextTick(() => {
          let menu = this.isAdminBarShown ? this.$refs.adminMenu : this.$refs.topMenu;
          this.setActiveIndex(to, menu);
        });
      },

      currentLang: function(lang) {
        this.toggledLang = this.getSwitchedLang(lang);
      }
    },

    methods: {
      logout() {
        window.location = '/' + this.$store.getters.language + '/logout';
      },

      getSwitchedLang(lang) {
        return lang === 'en' ? 'fr' : 'en';
      },

      setLanguage() {
        let storeLang = this.$store.getters.language;
        let newLang = this.getSwitchedLang(storeLang);
        let route = Object.assign({}, this.$route);

        // change the locale of the translation plugin
        this.$i18n.locale = newLang;
        // change the language
        // that will be used to determine the dislayed lang
        this.currentLang = newLang;

        // apply lang to route
        // so that we can 'refresh' the current route
        // with the new language
        route.params.lang = newLang;
        this.$router.push(route);
      },

      toggleAdminBar() {
        this.$store.dispatch('toggleAdminBar');
      }
    },

    mounted() {
      let menu = this.isAdminBarShown ? this.$refs.adminMenu : this.$refs.topMenu;
      this.setActiveIndex(this.$route, menu);
    }
  };
</script>

<style lang="scss">
  @import '../../sass/vendors/elementui/vars';
  .top-bar {
    user-select: none;
    padding: 0 20px;
    background-color: #313131;
    position: relative;
    z-index: $--index-top;
    .el-col {
      display: flex;
      align-items: center;
      &.nav-col {
        justify-content: flex-end;
      }
    }

    h1 {
      font-size: 1.3rem;
      margin: 0;
      text-transform: uppercase;
      display: flex;
      a {
        color: #FFF;
        text-decoration: none;
        display: inline-block;
        vertical-align: middle;
      }
    }

    .top-menu {
      border: 0;

      a {
        text-decoration: none;
      }

      .el-icon-setting {
        color: #FFF;
        &.active {
          color: $--color-danger;
        }
      }
    }
  }

  $admin-bar-height: 45px;
  .admin-bar {
    .el-menu {
      padding: 0 20px;
      display: flex;
      justify-content: flex-end;
      &-item:not(.is-active) {
        i, a {
          color: #f3f3f3;
        }
      }

      &-item {
        border: none !important;
        height: $admin-bar-height;
        line-height: $admin-bar-height;
        &.is-active {
          background-color: #c84444 !important;
        }
      }
    }
  }

  // Slide Down-Up
  .slide-down-enter-active, .slide-down-leave-active {
    transition: all 0.3s ease;
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
