<template>
  <el-col
    :span="8"
    v-if="entity.current_process || canActionsBeVisible"
  >
    <el-card
      shadow="never"
      class="process-actions-col"
    >
      <div slot="header">
        <h3>{{ trans('entities.process.actions') }}</h3>
      </div>
      <ul class="process-actions-col-list">
        <template v-if="entity.current_process">
          <li>
            <el-button type="primary" @click="continueToProcess(entity.current_process.id)">
              {{ trans('entities.process.view_current') }}
            </el-button>
          </li>
        </template>
        <template v-else-if="canActionsBeVisible">
          <li v-for="(process, index) in processDefinitions" :key="index">
            <el-button
              type="primary"
              :disabled="!processPermissions[process.name_key]"
              @click="triggerStartProcess(process.name, process.name_key)">
                {{ process.name }}
            </el-button>
          </li>
        </template>
      </ul>
    </el-card>
  </el-col>
</template>

<script>
  import { mapState, mapGetters, mapActions } from 'vuex';
  export default {
    name: 'process-actions-col',

    inheritAttrs: false,

    props: {
      entity: {
        type: Object,
        required: true
      },

      entityType: {
        type: String,
        required: true
      },

      processPermissions: {
        type: Object,
        required: true
      }
    },

    computed: {
      ...mapState('entities/processes', {
        processDefinitions: 'definitions'
      }),
      ...mapGetters({
        hasRole: 'entities/users/hasRole'
      })
    },

    methods: {
      ...mapActions({
        startProcess: 'entities/processes/start'
      }),

      canActionsBeVisible() {
        return this.hasRole('owner') || this.hasRole('admin');
      },

      triggerStartProcess(processName, processNameKey) {
        // confirm the intention to start a process first
        this.confirmStart({
          title: this.trans('entities.process.start'),
          message: this.trans('components.notice.message.start_process', {
            process_name: processName
          })
        }).then(async () => {
          try {
            const entityId = this.$route.params.entityId;
            const entityType = this.entityType;
            let process = await this.startProcess({ nameKey: processNameKey, entityId, entityType });
            this.notifySuccess({
              message: this.trans('components.notice.message.process_started')
            });
            this.$router.push(`${entityId}/process/${process.id}`);
          } catch (e) {
            if (!e.response) {
              throw e;
            } else if (e.response.status === HttpStatusCodes.FORBIDDEN) {
              // on error, re-fetch the info just to be in-sync
              this.$emit('error');
            }
          }
        }).catch(() => false);
      },

      continueToProcess(processId) {
        const entityId = this.$route.params.entityId;
        this.$router.push(`${entityId}/process/${processId}`);
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .process-actions-col {
    user-select: none;
    .el-card__header {
      background-color: #f5f7fa;
      h3 {
        margin: 0;
        padding: 3px 0px;
        text-align: center;
        display: block;
        color: #524f74;
      }
    }
    &-list {
      list-style: none;
      margin: 0;
      padding: 0;
      li {
        text-align: center;
        &:first-child button {
          // reset all before applying to specific
          border-radius: 0;
          border-top-left-radius: $--border-radius-base;
          border-top-right-radius: $--border-radius-base;
        }
        &:last-child button {
          // reset all before applying to specific
          border-radius: 0;
          border-bottom-left-radius: $--border-radius-base;
          border-bottom-right-radius: $--border-radius-base;
        }
        button {
          width: 100%;
          border-radius: 0;
        }
        @include quantity-query(to 1) {
          button {
            border-radius: $--border-radius-base;
          }
        }
      }
    }
  }
</style>
