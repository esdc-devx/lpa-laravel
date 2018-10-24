<template>
  <div class="content administration">
    <el-row type="flex">
      <el-col :span="24">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <h2>{{ trans('base.navigation.administration') }}</h2>
          </div>
          <el-button class="action" @click="$router.push(`admin/users`)">
            {{ trans('base.navigation.admin_user_list') }}
          </el-button>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import EventBus from '@/event-bus.js';
  export default {
    name: 'administration',

    mounted() {
      EventBus.$emit('App:ready');
    }
  };
</script>

<style lang="scss">
  .administration {
    h2 {
      text-align: center;
    }
  }
</style>
