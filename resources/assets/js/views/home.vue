<template>
  <div class="home content">
    <el-row type="flex" justify="center">
      <el-col :span="24"><h2>{{ trans('pages.home.welcome', { name: user.first_name }) }}</h2></el-col>
    </el-row>
    <el-row type="flex" justify="center">
      <el-col :span="24">
        <p v-html="trans('pages.home.instruction', { href: `${language}/getting-started` })"></p>
      </el-col>
    </el-row>
    <!-- Actions -->
    <el-row type="flex" class="row-bg actions" justify="center" :gutter="24">
      <el-col :span="3">
        <el-button @click="$router.push(`${language}/projects/create`)">
          <i class="el-icon-edit-outline"></i>
          <p>{{ trans('pages.project_list.create_project') }}</p>
        </el-button>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import EventBus from '@/event-bus.js';

  export default {
    name: 'home',

    computed: {
      ...mapGetters({
        language: 'language',
        user: 'users/current'
      })
    },

    mounted() {
      EventBus.$emit('App:ready');
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  .home {
    // since there is no breadcrumbs bar on the home page,
    // make sure the content is at the top
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    box-sizing: border-box;
    background-color: $--color-white;
    h2, p {
      text-align: center;
    }

    h2 {
      font-size: 3rem;
    }

    .actions {
      button {
        white-space: initial;
        width: 100%;
        height: 100%;
        p {
          margin-bottom: 0;
        }
      }
      i {
        font-size: 3rem;
        text-align: center;
        display: block;
      }
    }
  }
</style>
