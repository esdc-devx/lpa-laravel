<template>
  <div class="infobox-process">
    <el-card-wrap
      icon="el-icon-lpa-process"
      :headerTitle="process.definition.name"
    >
      <template slot="controls" v-if="hasRole('admin')">
        <el-control-wrap
          componentName="el-button"
          :disabled="!canCancel"
          :tooltip="trans('components.tooltip.cancel_process')"
          type="danger"
          size="mini"
          icon="el-icon-lpa-cancel-process"
          plain
          @click.native="onCancelConfirm"
        />
      </template>
      <dl>
        <dt>{{ trans('entities.general.status') }}</dt>
        <dd><span :class="'state ' + process.state.name_key">{{ process.state.name }}</span></dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.process.started') }}</dt>
        <dd v-audit:created="process"></dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.general.updated') }}</dt>
        <dd v-audit:updated="process"></dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.process.id') }}</dt>
        <dd>{{ process.engine_process_instance_id }}</dd>
      </dl>
    </el-card-wrap>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import ElCardWrap from '@components/el-card-wrap';
  import ElControlWrap from '@components/el-control-wrap';

  export default {
    name: 'infobox-process',

    components: { ElCardWrap, ElControlWrap },

    props: {
      process: {
        type: Object,
        required: true
      }
    },

    computed: {
      ...mapGetters({
        hasRole: 'entities/users/hasRole'
      }),

      canCancel() {
        return this.process.permissions.canCancel;
      }
    },

    methods: {
      onCancelConfirm() {
        this.confirmCancelProcess().then(async () => {
          this.$emit('cancel');
        }).catch(() => false);
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .infobox-process {
    .el-card__header > div {
      .controls button {
        &:hover, &:focus {
          i.el-icon-lpa-cancel-process {
            @include svg(cancel-process, $--color-white);
          }
        }
        &:disabled {
          &.el-button--danger {
            background-color: $--color-danger-lighter;
            i.el-icon-lpa-cancel-process {
              @include svg(cancel-process, $--color-danger-light);
            }
          }
        }
      }
    }
  }
</style>
