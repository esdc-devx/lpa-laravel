<template>
  <el-row class="top-bar" type="flex">
    <el-col :span="8" class="app-title-col">
      <div><h1><router-link :to="'/' + language">{{ trans('base.navigation.app_name') }}</router-link></h1></div>
    </el-col>
    <el-col :span="16" class="nav-col">
      <nav>
        <el-menu
          ref="topMenu"
          :default-active="$route.fullPath"
          background-color="#201e2c"
          text-color="#fff"
          active-text-color="#fff"
          class="top-menu"
          mode="horizontal"
          router>
          <el-submenu index="1" popper-class="sub-menu">
            <template slot="title">{{ user.name }}</template>
            <el-menu-item index="" @click="onLogout()"><span>{{ trans('base.navigation.logout') }}</span></el-menu-item>
          </el-submenu>
          <el-menu-item index="" @click="setLanguage" :class="{ 'disabled': isMainLoading }">
            <span>{{ trans('base.navigation.language_toggle') }}</span>
          </el-menu-item>
          <el-menu-item index="" v-if="hasRole('admin')" @click="toggleAdminBar">
            <span>
              <i :class="['el-icon-lpa-settings', { 'active' : isAdminBarShown} ]"></i>
            </span>
          </el-menu-item>
        </el-menu>
      </nav>
    </el-col>
  </el-row>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';

  import EventBus from '../event-bus';
  import Config from '../config.js';
  import MenuUtils from '../mixins/menu/utils.js';

  import HttpStatusCodes from '../axios/http-status-codes';

  export default {
    name: 'top-bar',

    mixins: [ MenuUtils ],

    computed: {
      ...mapGetters({
        isMainLoading: 'isMainLoading',
        language: 'language',
        isAdminBarShown: 'isAdminBarShown',
        user: 'users/current',
        hasRole: 'users/hasRole',
        shouldConfirmBeforeLanguageChange: 'shouldConfirmBeforeLanguageChange'
      })
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
      $route: function (to) {
        // since this is a 3rd party library,
        // we do not know when it will update itself
        // so just wait until the DOM is updated
        this.$nextTick(() => {
          let menu = this.$refs.topMenu;
          this.setActiveIndex(to, menu);
        });
      },

      currentLang: function (lang) {
        this.toggledLang = this.getSwitchedLang(lang);
      }
    },

    methods: {
      ...mapActions({
        showAppLoading: 'showAppLoading',
        showMainLoading: 'showMainLoading',
        logout: 'users/logout'
      }),

      async onLogout() {
        this.showAppLoading();
        // try-catch here since the logout uses another instance of axios
        // which doesn't have the interceptors
        try {
          await this.logout();
          window.location = `/${this.language}/login`;
        } catch({ response }) {
          // redirect to login page even if we have an error,
          // chances are that the user cleared its cache before the action
          window.location = `/${this.language}/login`;
        }
      },

      getSwitchedLang(lang) {
        return lang === 'en' ? 'fr' : 'en';
      },

      setLanguage() {
        if (this.shouldConfirmBeforeLanguageChange) {
          EventBus.$emit('TopBar:beforeLanguageUpdate', this.doSetLanguage);
        } else {
          this.doSetLanguage();
        }
      },

      async doSetLanguage() {
        // @todo: we probably should hide the app entirely
        // to avoid seeing bouncing texts
        // @note: hideMainLoading is called when the language is set in router
        await this.showMainLoading();
        this.$helpers.throttleAction(() => {
          let storeLang = this.$store.getters.language;
          let newLang = this.getSwitchedLang(storeLang);
          EventBus.$emit('Store:languageUpdate', newLang);
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
        });
      },

      toggleAdminBar() {
        this.$helpers.throttleAction(() => {
          this.$store.dispatch('toggleAdminBar');
        });
      }
    },

    mounted() {
      let menu = this.$refs.topMenu;
      this.setActiveIndex(this.$route, menu);
    }
  };
</script>

<style lang="scss">
  @import '../../sass/abstracts/vars';

  $top-bar-height: 50px;
  $top-bar-fill: #201e2c;

  .top-bar {
    user-select: none;
    padding: 0 20px;
    background-color: $top-bar-fill;
    position: relative;
    z-index: $--index-top;
    // make space for the side-bar-toggle button
    padding-left: 64px + 15px;
    .el-menu-item,
    .el-submenu .el-submenu__title {
      height: $top-bar-height;
      line-height: $top-bar-height;
    }
    .el-col {
      display: flex;
      align-items: center;
      flex: 1;
      &.nav-col {
        justify-content: flex-end;
      }
    }

    h1 {
      font-size: 1.3rem;
      margin: 0;
      display: flex;
      a {
        color: $--color-white;
        text-decoration: none;
        display: inline-block;
        vertical-align: middle;
        font-weight: 600;
      }
    }

    .top-menu {
      border: 0;
      a {
        text-decoration: none;
      }

      li:hover,
      li:hover .el-submenu__title,
      li.is-active .el-submenu__title {
        background-color: #322f43 !important;
      }
      .el-submenu__title {
        border: none !important;
      }

      // ElementUI override
      .el-menu-item span {
        vertical-align: baseline;
      }

      .el-icon-setting {
        color: $--color-white;
        vertical-align: sub;
        &.active {
          color: $--color-danger;
        }
      }
    }
  }

  .sub-menu .el-menu,
  .sub-menu .el-menu-item,
  .sub-menu .el-submenu .el-submenu__title {
    color: $--color-black !important;
    background-color: $--color-white !important;
    @at-root .sub-menu .el-menu {
      border-radius: 0;
      border: 1px solid #cdcdcd;
      padding: 0;
      margin-top: 2px;
      &-item:hover,
      &-item.is-active {
        color: $--color-white !important;
        background-color: #322f43 !important;
      }
    }
  }
</style>
