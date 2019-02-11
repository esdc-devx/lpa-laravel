<template>
  <div class="administration">
    <el-row>
      <el-col>
        <el-card-wrap
          icon="el-icon-lpa-settings"
          :headerTitle="trans('base.navigation.administration')"
        >
        </el-card-wrap>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="24">
        <el-button @click="$router.push('admin/users')" icon="el-icon-lpa-user">
          {{ trans('base.navigation.admin_user_list') }}
        </el-button>
        <el-button @click="$router.push('admin/organizational-units')" icon="el-icon-lpa-organizational-unit">
          {{ trans('base.navigation.admin_organizational_unit_list') }}
        </el-button>
        <el-button @click="$router.push('admin/process-notifications')" icon="el-icon-lpa-process-notification">
          {{ trans('base.navigation.admin_process_notification_list') }}
        </el-button>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import Page from '@components/page';
  import ElCardWrap from '@components/el-card-wrap';

  export default {
    name: 'administration',

    extends: Page,

    components: { ElCardWrap },
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .administration {
    h2 i {
      @include svg(settings, $--color-primary);
    }
    .el-button {
      min-width: 200px;
      height: 100px;
      i {
        width: $svg-icons-size-medium;
      }
    }
  }
</style>
