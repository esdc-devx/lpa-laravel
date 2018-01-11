<template>
  <nav>
    <el-menu :default-active="$route.fullPath" background-color="#212121" text-color="#fff" active-text-color="#4bd5ff" class="top-menu" mode="horizontal" @select="handleSelect" router>
      <el-submenu index="0">
        <template slot="title">Admin</template>
        <el-menu-item :index="'/'+$store.getters.getLanguage+'/users/create'"><a href="#" @click.prevent>Create User</a></el-menu-item>
        <el-menu-item :index="'/'+$store.getters.getLanguage+'/users'"><a href="#" @click.prevent>User List</a></el-menu-item>
      </el-submenu>
      <el-submenu index="1">
        <template slot="title">{{ username }}</template>
        <el-menu-item :index="'/'+$store.getters.getLanguage+'/profile'"><a href="#" @click.prevent>My Profile</a></el-menu-item>
        <!--
          normal list item here as we need to send the user
          directly to the logout page without using the router
        -->
        <li role="menuitem" class="el-menu-item"><a :href="'/'+$store.getters.getLanguage+'/logout'">Logout</a></li>
      </el-submenu>
      <el-submenu index="2" class="disabled">
        <template slot="title">LPA Profile: Some dynamic Organization</template>
        <el-menu-item index="2-1"><a href="#" @click.prevent>CS01</a></el-menu-item>
        <el-menu-item index="2-2"><a href="#" @click.prevent>User List</a></el-menu-item>
      </el-submenu>
      <el-menu-item :index="'/'+$store.getters.getLanguage+'/help'"><a href="#" @click.prevent>Help</a></el-menu-item>
      <li role="menuitem" tabindex="0" class="el-menu-item">
        <a href="#" @click.prevent="switchI18n" class="lang">
          {{ toggledLang }}
        </a>
      </li>
    </el-menu>

    <el-menu :default-active="$route.fullPath" background-color="#e3e3e3" text-color="#222" active-text-color="#222" class="user-menu" mode="horizontal" @select="handleSelect" router>
      <el-menu-item :index="'/'+$store.getters.getLanguage+'/'"><a href="#" @click.prevent>{{ $trans('menu.home') }}</a></el-menu-item>
      <el-menu-item :index="'/'+$store.getters.getLanguage+'/dashboard'" class="disabled"><a href="#" @click.prevent>Dashboard</a></el-menu-item>
      <el-menu-item :index="'/'+$store.getters.getLanguage+'/projects'"><a href="#" @click.prevent>Projects</a></el-menu-item>
      <el-menu-item :index="'/'+$store.getters.getLanguage+'/learning-products'"><a href="#" @click.prevent>Learning Products</a></el-menu-item>
      <el-menu-item :index="'/'+$store.getters.getLanguage+'/standalone'" class="disabled"><a href="#" @click.prevent>Stand-Alone Products</a></el-menu-item>
    </el-menu>
  </nav>
</template>

<script>
  import { mapState } from 'vuex';

  export default {
    name: 'navigation',

    computed: mapState({
      username: state => state.user.info.name
    }),

    data() {
      let currentLang = this.$store.getters.getLanguage;
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
      currentLang: function(lang) {
        this.toggledLang = this.getSwitchedLang(lang);
      }
    },

    methods: {
      handleSelect(key, keyPath) {
        console.log(key, keyPath);
      },
      getSwitchedLang(lang) {
        return lang === 'en' ? 'fr' : 'en';
      },
      switchI18n() {
        let storeLang = this.$store.getters.getLanguage;
        let newLang = this.getSwitchedLang(storeLang);
        const route = Object.assign({}, this.$route);

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
  .top-menu, .user-menu {
    display: flex;
    justify-content: flex-end;
    li {
      padding: 0;
      &.el-submenu li {
        color: #FFF;
        background-color: rgb(33, 33, 33);
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

    .lang {
      text-transform: uppercase;
    }
  }
</style>
