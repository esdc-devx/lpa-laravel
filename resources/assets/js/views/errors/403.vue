<template>
  <div class="content forbidden">
    <el-row type="flex" justify="center" :gutter="18">
      <el-col :span="18">
        <h2 v-html="trans('errors.forbidden')"></h2>
      </el-col>
    </el-row>
    <el-row type="flex" justify="center" :gutter="18">
      <el-col class="owl-wrap" :span="18">
        <i class="el-icon el-icon-lpa-owl-403"></i>
      </el-col>
    </el-row>
    <el-row type="flex" justify="center" :gutter="14">
      <el-col :span="14">
        <p v-html="trans('pages.403.instruction')"></p>
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
    name: 'forbidden',

    mixins: [ PageUtils ],

    computed: {
      ...mapGetters([
        'language'
      ])
    },

    methods: {
      ...mapActions({
        hideMainLoading: 'hideMainLoading'
      })
    },

    mounted() {
      EventBus.$emit('App:ready');
      // @note: hide the loading that was shown
      // in the router's beforeEnter
      this.$nextTick(async () => {
        await this.hideMainLoading();
      });
    }
  };
</script>

<style lang="scss">
@import '~@sass/base/error';
</style>
