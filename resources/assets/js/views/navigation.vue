<template>
  <nav>
    <el-menu :default-active="$route.fullPath" background-color="#212121" text-color="#fff" active-text-color="#4bd5ff" class="top-menu" mode="horizontal" @select="handleSelect" router>
      <el-submenu index="0">
        <template slot="title">Admin</template>
        <el-menu-item index="/users/create"><a href="#" @click.prevent>Create User</a></el-menu-item>
        <el-menu-item index="/users"><a href="#" @click.prevent>User List</a></el-menu-item>
      </el-submenu>
      <el-submenu index="1">
        <template slot="title">User Name</template>
        <el-menu-item index="/profile"><a href="#" @click.prevent>My Profile</a></el-menu-item>
        <el-menu-item index="/logout"><a href="/logout">Logout</a></el-menu-item>
      </el-submenu>
      <el-submenu index="2" class="disabled">
        <template slot="title">LPA Profile: Some dynamic Organization</template>
        <el-menu-item index="2-1"><a href="#" @click.prevent>CS01</a></el-menu-item>
        <el-menu-item index="2-2"><a href="#" @click.prevent>User List</a></el-menu-item>
      </el-submenu>
      <el-menu-item index="/help"><a href="#" @click.prevent>Help</a></el-menu-item>
      <el-menu-item index="/fr"><a href="#" @click.prevent>Français</a></el-menu-item>
    </el-menu>

    <el-menu :default-active="$route.fullPath" background-color="#e3e3e3" text-color="#222" active-text-color="#222" class="user-menu" mode="horizontal" @select="handleSelect" router>
      <el-menu-item index="/"><a href="#" @click.prevent>Home</a></el-menu-item>
      <el-menu-item index="/dashboard" class="disabled"><a href="#" @click.prevent>Dashboard</a></el-menu-item>
      <el-menu-item index="/projects"><a href="#" @click.prevent>Projects</a></el-menu-item>
      <el-menu-item index="/learning-products"><a href="#" @click.prevent>Learning Products</a></el-menu-item>
      <el-menu-item index="/standalone" class="disabled"><a href="#" @click.prevent>Stand-Alone Products</a></el-menu-item>
    </el-menu>
  </nav>
</template>

<script>
  import { EventBus } from '../components/event-bus.js';
  export default {
    name: 'navigation',

    data() {
      return {

      };
    },

    methods: {
      handleSelect(key, keyPath) {
        console.log(key, keyPath);
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
      > a {
        padding: 0 20px;
        height: 100%;
        display: block;
      }
    }
    a {
      text-decoration: none;
    }
  }
</style>
