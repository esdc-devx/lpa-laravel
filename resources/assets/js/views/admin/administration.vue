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
          <el-button class="action" @click="$router.push(`admin/organizational-units`)">
            {{ trans('base.navigation.admin_organizational_unit_list') }}
          </el-button>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import Page from '@components/page';

  export default {
    name: 'administration',

    extends: Page
  };
</script>

<style lang="scss">
  .administration {
    h2 {
      text-align: center;
    }
  }
</style>
