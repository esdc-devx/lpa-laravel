<template>
  <el-row class="top-bar" type="flex">
    <el-col :span="8" class="app-title-col">
      <div>
        <h1>
          <router-link
            :to="'/' + language"
            :class="{ 'disabled': isMainLoading }"
          >
            {{ trans('base.navigation.app_name') }}
          </router-link>
        </h1>
      </div>
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
          router
        >
          <el-submenu
            index="user-menu"
            popper-class="sub-menu"
            :class="{ 'disabled': isMainLoading }"
          >
            <template slot="title">{{ user.name }}</template>
            <el-menu-item index="" @click="onLogout()">
              {{ trans('base.navigation.logout') }}
            </el-menu-item>
          </el-submenu>
          <el-submenu
            index="help-menu"
            popper-class="sub-menu"
            :class="{ 'disabled': isMainLoading }"
          >
            <template slot="title">{{ trans('base.navigation.help') }}</template>
            <el-menu-item index="">
              <a :href="trans('base.navigation.help_support_centre_url')" target="_blank">
                {{ trans('base.navigation.help_support_centre') }}
              </a>
            </el-menu-item>
            <el-menu-item index="">
              <a :href="trans('base.navigation.help_getting_started_url')" target="_blank">
                {{ trans('base.navigation.help_getting_started') }}
              </a>
            </el-menu-item>
            <el-menu-item index="">
              <a :href="trans('base.navigation.help_projects_url')" target="_blank">
                {{ trans('base.navigation.help_projects') }}
              </a>
            </el-menu-item>
            <el-menu-item index="">
              <a :href="trans('base.navigation.help_learning_products_url')" target="_blank">
                {{ trans('base.navigation.help_learning_products') }}
              </a>
            </el-menu-item>
          </el-submenu>
          <el-menu-item
            index=""
            @click="setLanguage"
            :class="{ 'disabled': isMainLoading }"
          >
            {{ trans('base.navigation.language_toggle') }}
          </el-menu-item>
        </el-menu>
      </nav>
    </el-col>
  </el-row>
</template>

<script>
  import { mapState, mapActions, mapMutations } from 'vuex';

  import Config from '@/config.js';
  import MenuUtils from '@mixins/menu/utils.js';

  import HttpStatusCodes from '@axios/http-status-codes';

  export default {
    name: 'top-bar',

    mixins: [ MenuUtils ],

    data() {
      return {
        isTryingToLogOut: false
      };
    },

    computed: {
      ...mapState([
        'language',
        'isMainLoading',
        'shouldConfirmBeforeLeaving',
        'waitForLogout',
        'filteredDataTableList'
      ]),
      ...mapState('entities/users', {
        user: 'current'
      })
    },

    watch: {
      $route: function (to) {
        // since this is a 3rd party library,
        // we do not know when it will update itself
        // so just wait until the DOM is updated
        this.$nextTick(() => {
          let menu = this.$refs.topMenu;
          this.setActiveIndex(to, menu);
        });
      },
      waitForLogout: function (val) {
        if (!val) {
          this.doLogout();
        }
      }
    },

    methods: {
      ...mapActions({
        logout: 'entities/users/logout'
      }),
      ...mapMutations([
        'showAppLoading',
        'setWaitForLogout'
      ]),

      onLogout() {
        this.setWaitForLogout(true);
      },

      async doLogout() {
        this.showAppLoading();
        // try-catch here since the logout uses another instance of axios
        // which doesn't have the interceptors
        try {
          await this.logout();
          window.location = `/${this.language}/login`;
        } catch (e) {
          if (e.response) {
            // redirect to login page even if we have an error,
            // chances are that the user cleared its cache before the action
            window.location = `/${this.language}/login`;
          } else {
            throw e;
          }
        }
      },

      getSwitchedLang(lang) {
        return lang === 'en' ? 'fr' : 'en';
      },

      setLanguage() {
        if (this.filteredDataTableList.length !== 0) {
          this.$confirm(
            this.trans('components.notice.message.language_toggle'),
            this.trans('components.notice.type.warning'),
            {
              type: 'warning',
              confirmButtonText: this.trans('base.actions.continue'),
              confirmButtonClass: 'el-button--warning',
              cancelButtonText: this.trans('base.actions.cancel'),
              dangerouslyUseHTMLString: true
            }
          )
          .then(() => {
            this.doSetLanguage();
          })
          .catch(() => false);
        } else {
          this.doSetLanguage();
        }
      },

      async doSetLanguage() {
        // @todo: we probably should hide the app entirely
        // to avoid seeing bouncing texts
        this.$helpers.debounceAction(() => {
          let oldLang = this.language;
          let newLang = this.getSwitchedLang(oldLang);
          let route = Object.assign({}, this.$route);

          // change the locale of the translation plugin
          this.$i18n.locale = newLang;

          // apply lang to route
          // so that we can 'refresh' the current route
          // with the new language
          route.params.lang = newLang;

          /*
           * Also we need to set the fullPath to be able to forward to routes that have wildcards
           * See: https://github.com/vuejs/vue-router/issues/1994
           * And make sure that the params are reflecting the new language
           * We also need to decode the URI before assigning it
           * to avoid double encoding. Ex: "%20" -> "%2520".
           */
          let newPath = decodeURIComponent(route.path).replace(new RegExp(`^\/${oldLang}\/`), `/${newLang}/`);
          route.params.fullPath = newPath;
          route.params.lang = newLang;
          this.$router.push(route);
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
  @import '~@sass/abstracts/vars';

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
    @at-root .sub-menu .el-menu-item a {
      display: block;
      width: 100%;
      padding: 0 10px;
      margin: 0 -10px;
      color: $--color-black !important;
      background-color: transparent !important;
      text-decoration: none;
      font-weight: normal;
      &:hover {
        color: $--color-white !important;
      }
    }
  }
</style>
