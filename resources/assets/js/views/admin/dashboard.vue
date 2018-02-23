<template>
  <div class="content">
    <el-row type="flex" justify="center">
      <el-col :span="24"><h2>{{ trans('navigation.admin_dashboard') }}</h2></el-col>
    </el-row>
  </div>
</template>

<script>
  import EventBus from '../../components/event-bus.js';
  export default {
    name: 'admin-dashboard',
    created() {
      EventBus.$emit('App:ready');
    }
  };
</script>

<style lang="scss" scoped>
  h2 {
    text-align: center;
  }
</style>
