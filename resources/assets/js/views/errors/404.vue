<template>
  <div class="content not-found">
    <el-row type="flex" justify="center" :gutter="18">
      <el-col :span="18">
        <h2 v-html="trans('errors.not_found')"></h2>
      </el-col>
    </el-row>
    <el-row type="flex" justify="center" :gutter="18">
      <el-col class="owl-wrap" :span="18">
        <i class="el-icon el-icon-lpa-owl-403"></i>
      </el-col>
    </el-row>
    <el-row type="flex" justify="center" :gutter="14">
      <el-col :span="14">
        <p v-html="trans('pages.404.instruction')"></p>
        <el-button @click="goToPage('home')" round><i class="el-icon-back"></i>{{ trans('base.navigation.back_to_home') }}</el-button>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '@/event-bus.js';
  import PageUtils from '@mixins/page/utils.js';

  export default {
    name: 'not-found',

    mixins: [ PageUtils ],

    computed: {
      ...mapGetters([
        'language'
      ])
    },

    mounted() {
      EventBus.$emit('App:ready');
    }
  };
</script>

<style lang="scss">
@import '~@sass/base/error';
</style>
