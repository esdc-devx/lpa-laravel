<template>
  <div class="content">
    <el-row type="flex" justify="center">
      <el-col :span="24"><h2>{{ trans('navigation.admin_dashboard') }}</h2></el-col>
    </el-row>
  </div>
</template>

<script>
  export default {
    name: 'admin-dashboard'
  };
</script>

<style lang="scss" scoped>
  h2 {
    text-align: center;
    font-size: 3rem;
    border: none;
    text-transform: inherit;
  }
</style>
