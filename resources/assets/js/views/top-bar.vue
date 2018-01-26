<template>
  <div>
    <el-row type="flex">
      <el-col :span="8" class="app-title-col">
        <div><h1><router-link :to="'/' + lang">{{ trans('navigation.app_name') }}</router-link></h1></div>
      </el-col>
      <el-col :span="16" class="nav-col">
        <nav>
          <el-menu ref="topMenu" :default-active="$route.fullPath" background-color="#313131" text-color="#fff" active-text-color="#4bd5ff" class="top-menu" mode="horizontal" router>
            <el-submenu index="0">
              <template slot="title">Admin</template>
              <el-menu-item :index="'/' + lang + '/users/create'"><a href="#" @click.prevent>{{ trans('navigation.user_create') }}</a></el-menu-item>
              <el-menu-item :index="'/' + lang + '/users'"><a href="#" @click.prevent>{{ trans('navigation.user_list') }}</a></el-menu-item>
            </el-submenu>
            <el-submenu index="1">
              <template slot="title">{{ username }}</template>
              <el-menu-item :index="'/' + lang + '/profile'"><a href="#" @click.prevent>{{ trans('navigation.profile') }}</a></el-menu-item>
              <el-menu-item :index="'/' + lang + '/logout'"><a href="#" @click.prevent="logout()">{{ trans('navigation.logout') }}</a></el-menu-item>
            </el-submenu>
            <el-submenu index="2" class="disabled">
              <template slot="title">LPA Profile: Some dynamic Organization</template>
              <el-menu-item index="2-1"><a href="#" @click.prevent>CS01</a></el-menu-item>
              <el-menu-item index="2-2"><a href="#" @click.prevent>EX01</a></el-menu-item>
            </el-submenu>
            <el-menu-item :index="'/' + lang + '/help'" class="disabled"><a href="#" @click.prevent>Help</a></el-menu-item>

            <li role="menuitem" class="el-menu-item lang">
              <a v-if="toggledLang === 'en'" href="#" @click.prevent="setLanguage">English</a>
              <a v-else href="#" @click.prevent="setLanguage">Français</a>
            </li>
          </el-menu>
        </nav>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import { mapState } from 'vuex';

  export default {
    name: 'top-bar',

    computed: {
      lang() {
        return this.$store.getters.language;
      },
      ...mapState({
        username: state => state.user.info.name
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
      '$route': function(to) {
        // since this is a 3rd party library,
        // we do not know when it will update itself
        // so just wait until the DOM is updated
        this.$nextTick(() => {
          this.$refs.topMenu.activeIndex = to.fullPath;
        })
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
      }
    }
  };
</script>

<style lang="scss" scoped>
  @import '../../sass/vendors/element-variables';
  h1 {
    font-size: 0.8rem;
    margin: 0;
    text-transform: uppercase;
    a {
      color: #FFF;
      text-decoration: none;
      display: inline-block;
      vertical-align: middle;
    }
  }
  .el-row {
    padding: 0 20px;
    background-color: #313131;
    .el-col {
      display: flex;
      align-items: center;
      &.nav-col {
        justify-content: flex-end;
      }
    }
  }

  .top-menu {
    border: 0;
    li {
      padding: 0;
      &.el-submenu li {
        color: #FFF;
        background-color: #313131;
      }
      > a {
        padding: 0 20px;
        height: 100%;
        display: block;
      }
    }
    a {
      text-decoration: none;
    }
    .lang, .lang:hover {
      color: #fff;
    }
    .lang:hover {
      background-color: #272727;
    }
  }
</style>
