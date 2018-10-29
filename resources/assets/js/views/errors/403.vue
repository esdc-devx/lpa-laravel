<template>
  <div class="content forbidden">
    <el-row type="flex" justify="center" :gutter="18">
      <el-col :span="18">
        <h2 v-html="trans('pages.403.title')"></h2>
      </el-col>
    </el-row>
    <el-row type="flex" justify="center" :gutter="18">
      <el-col class="icon-divider" :span="18">
        <i class="el-icon el-icon-lpa-owl-403"></i>
      </el-col>
    </el-row>
    <el-row type="flex" justify="center" :gutter="14">
      <el-col :span="14">
        <p v-html="trans('pages.403.instruction')"></p>
        <el-button @click="goToPage('home')"><i class="el-icon-back"></i>{{ trans('base.navigation.back_to_home') }}</el-button>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import Page from '@components/page';

  import PageUtils from '@mixins/page/utils.js';

  export default {
    name: 'forbidden',

    extends: Page,

    mixins: [ PageUtils ]
  };
</script>

<style lang="scss">

</style>
