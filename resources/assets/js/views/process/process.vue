<template>
  <div class="process content">
    <el-row>
      <el-col>
        <info-box>
          <dl>
            <dt>{{ trans('entities.general.status') }}</dt>
            <dd><span :class="'state ' + viewing.state.name_key">{{ viewing.state.name }}</span></dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.process.started') }}</dt>
            <dd v-audit:created="viewing"></dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.general.updated') }}</dt>
            <dd v-audit:updated="viewing"></dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.process.id') }}</dt>
            <dd>{{ viewing.engine_process_instance_id }}</dd>
          </dl>
          <div class="controls">
            <el-button
              size="small"
              type="danger"
              v-if="hasRole('admin')"
              :disabled="!canCancel"
              @click="onCancel"
              plain>
              {{ trans('components.notice.title.cancel_process') }} <i class="el-icon-close"></i>
            </el-button>
          </div>
        </info-box>
      </el-col>
    </el-row>

    <el-row>
      <el-col>
        <el-card shadow="never" class="process-steps">
          <div slot="header">
            <h3>{{ viewing.definition.name }}</h3>
          </div>
          <el-steps :active="activeStep" finish-status="finish" align-center>
            <el-step
              v-for="(step, index) in steps"
              :class="{ 'is-selected': activeStep === index }"
              :title="trans('entities.process.step', { num: index + 1 })"
              :description="step.definition.name"
              :key="index"
              @click.native="onStepChange(index)"
              :status="
                step.state.name_key === 'locked' ? 'wait' :
                step.state.name_key === 'unlocked' ? 'process' :
                step.state.name_key === 'completed' ? 'finish' :
                step.state.name_key === 'cancelled' ? 'error' : ''
              "
              :icon="'el-icon-lpa-' + step.state.name_key">
            </el-step>
          </el-steps>
        </el-card>
      </el-col>
    </el-row>

    <el-row>
      <el-tabs type="border-card" class="no-shadow">
        <el-tab-pane>
          <span slot="label"><i class="el-icon-lpa-form"></i> <b>{{ trans('pages.process.forms') }}</b></span>
          <el-table
            class="process-forms-table"
            :data="forms"
            :row-class-name="getFormRowClassName"
            @row-click="viewForm"
            stripe>
            <el-table-column width="50px">
              <template slot-scope="scope">
                <i :class="'el-icon-lpa-' + scope.row.state.name_key"></i>
              </template>
            </el-table-column>
            <el-table-column
              prop="definition.name"
              :label="trans('entities.general.name')"
              class-name="name">
            </el-table-column>
            <el-table-column
              :label="trans('entities.general.status')"
              width="180px"
              >
              <template slot-scope="scope">
                <span :class="'state ' +  scope.row.state.name_key">{{ scope.row.state.name }}</span>
              </template>
            </el-table-column>
            <el-table-column
              :label="$tc('entities.form.assigned_organizational_units')"
              >
              <template slot-scope="scope">
                <span>{{ scope.row.organizational_unit ? scope.row.organizational_unit.name : trans('entities.general.none') }}</span>
              </template>
            </el-table-column>
            <el-table-column
              :label="trans('entities.form.current_editor')">
              <template slot-scope="scope">
                <span>{{ scope.row.current_editor ? scope.row.current_editor.name : trans('entities.general.none') }}</span>
              </template>
            </el-table-column>
            <el-table-column
              :label="trans('entities.general.updated')">
              <template slot-scope="scope">
                <span v-audit:updated="scope.row"></span>
              </template>
            </el-table-column>
          </el-table>
        </el-tab-pane>

        <el-tab-pane>
          <span slot="label"><i class="el-icon-lpa-info-sheet"></i> <b>{{ $tc('entities.general.information_sheet', 2) }}</b></span>
           <el-table
            class="information-sheets-table"
            :data="informationSheets"
            stripe
          >
            <el-table-column
              :label="trans('entities.general.name')"
            >
              <template slot-scope="scope">
                <a :href="'/' + language + '/information-sheets/' + scope.row.id" target="_blank">{{ scope.row.definition.name }}</a>
              </template>
            </el-table-column>
          </el-table>
        </el-tab-pane>
      </el-tabs>
    </el-row>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions, mapMutations } from 'vuex';

  import HttpStatusCodes from '@axios/http-status-codes';

  import Page from '@components/page';
  import InfoBox from '@components/info-box.vue';

  import Process from '@/store/models/Process';
  import InformationSheet from '@/store/models/Information-Sheet';

  const loadData = async function ({ to } = {}) {
    const { entityName, entityId, processId } = to ? to.params : this;
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch(`entities/${entityName}/load`, entityId),
      store.dispatch('entities/processes/loadInstance', processId),
      store.dispatch('entities/informationSheets/loadAll', { entityType: 'process-instance', entityId: processId }),
      store.dispatch('authorizations/processes/loadCanCancel', processId)
    );

    // Exception handled by interceptor
    try {
      await axios.all(requests);
    } catch (e) {
      if (!e.response) {
        throw e;
      }
    }
  };

  export default {
    name: 'process',

    extends: Page,

    components: { InfoBox },

    props: {
      entityName: {
        type: String,
        required: true
      },
      entityId: {
        type: String,
        required: true
      },
      processId: {
        type: String,
        required: true
      }
    },

    computed: {
      ...mapGetters('entities/processes', {
        activeStepStore: 'activeStep'
      }),

      viewing() {
        return Process.find(this.processId);
      },

      canCancel() {
        return this.viewing.permissions.canCancel;
      },

      steps() {
        return _.sortBy(this.viewing.steps, 'definition.display_sequence');
      },

      forms() {
        return _.sortBy(this.viewing.steps[this.activeStep].forms, 'definition.display_sequence');
      },

      activeStep: {
        get() {
          return this.activeStepStore(this.processId);
        },
        set(val) {
          this.setActiveStep(val);
        }
      },

      informationSheets() {
        return InformationSheet.query()
          .where('entity_id', _.parseInt(this.processId))
          .where('entity_type', 'process-instance')
          .get();
      }
    },

    methods: {
      ...mapActions('entities/processes', [
        'cancelInstance'
      ]),

      ...mapMutations('entities/processes', [
        'setActiveStep'
      ]),

      viewForm(form) {
        this.$router.push(`${this.processId}/form/${form.id}`);
      },

      getFormRowClassName({row, rowIndex}) {
        return row.state.name_key;
      },

      onStepChange(index) {
        this.activeStep = index;
      },

      onCancel() {
        this.confirmCancelProcess().then(async () => {
          try {
            await this.cancelInstance(this.processId);
            this.notifySuccess({
              message: this.trans('components.notice.message.process_cancelled')
            });
            await loadData.apply(this);
          } catch (e) {
            // Exception handled by interceptor
            if (!e.response) {
              throw e;
            } else if (e.response.status === HttpStatusCodes.FORBIDDEN) {
              await loadData.apply(this);
            }
          }
        }).catch(() => false);
      }
    },

    // This makes sure that before entering the route, that we set the active step
    // so that when the page is rendered, the correct step is selected.
    async beforeRouteEnter(to, from, next) {
      await loadData({ to });
      next();
    },

    // called when url params change, e.g: language
    async beforeRouteUpdate(to, from, next) {
      await loadData.apply(this);
      next();
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';
  @import '~@sass/base/helpers';

  .process {
    margin: 0 auto;

    &-steps {
      .el-card__header h3 {
        text-align: center;
        margin: 0;
      }
      .el-step {
        .el-step__title, .el-step__description {
          transition: all 0.3s ease;
        }
        &:hover {
          .el-step__head .el-step__icon {
            transition: all 0.3s ease;
            transform: scale(1.1);
            transform-origin: center;
          }
          .el-step__title {
            font-size: 130%;
          }
          .el-step__description {
            font-size: 100%;
          }
        }

        .el-step__head .el-step__icon, .el-step__main {
          transition: transform 0.3s ease;
          cursor: pointer;
        }

        &.is-selected {
          // Default
          .el-step__head .el-step__icon {
            transition: all 0.3s ease;
            transform: scale(1.2);
            transform-origin: center;
          }
          .el-step__title {
            font-size: 140%;
          }
          .el-step__description {
            font-size: 110%;
          }

          // Locked
          .el-step__head.is-wait .el-step__icon-inner {
            @include svg(locked, map-get($color-states, locked));
          }
          .el-step__title.is-wait, .el-step__description.is-wait {
            color: map-get($color-states, locked) !important;
          }
          // Cancelled
          .el-step__head.is-error .el-step__icon-inner {
            @include svg(cancelled, map-get($color-states, cancelled));
          }
          .el-step__title.is-error, .el-step__description.is-error {
            color: map-get($color-states, cancelled) !important;
          }
        }

        // Unlocked
        .el-step__title.is-process, .el-step__description.is-process {
          color: map-get($color-states, unlocked) !important;
        }
        // Locked
        .el-step__title.is-wait, .el-step__description.is-wait {
          color: lighten(map-get($color-states, locked), 25%) !important;
        }
        .el-step__head.is-wait .el-step__icon-inner {
          @include svg(locked, lighten(map-get($color-states, locked), 25%));
        }
        // Completed
        .el-step__title.is-finish, .el-step__description.is-finish {
          color: map-get($color-states, completed) !important;
        }
        // Cancelled
        .el-step__title.is-error, .el-step__description.is-error {
          color: lighten(map-get($color-states, cancelled), 25%) !important;
        }
        .el-step__head.is-error .el-step__icon-inner {
          @include svg(cancelled, lighten(map-get($color-states, cancelled), 25%));
          &:before {
            // remove the elementui default icon
            content: '';
          }
        }
      }

      .el-step__icon-inner {
        @include size(100%);
        padding: 5px;
      }
      .el-step__main {
        margin-top: 10px;
        .el-step__title {
          font-weight: bold;
        }
        .el-step__description {
          font-weight: 500;
        }
      }
    }

    &-forms-table {
      .el-table__row {
        cursor: pointer;
      }

      .state {
        line-height: 18px;
      }

      .status .cell {
        display: flex;
        align-items: center;
        span {
          flex: 1;
        }
        i {
          @include size(20px);
        }
      }
    }

    .information-sheets-table {
      .el-table__row {
        cursor: default;
      }
    }

    .info-box {
      h2 {
        margin: 0;
        display: inline-block;
      }
      dl, .controls {
        flex-basis: 20%;
        max-width: 20%; // Patch for IE11. See https://github.com/philipwalton/flexbugs/issues/3#issuecomment-69036362
      }
      .controls {
        margin-bottom: 0;
        align-items: flex-end;
        justify-content: center;
        .el-button {
          font-weight: bold;
          i {
            font-weight: bold;
            margin-left: 10px;
          }
        }
        .el-button + .el-button {
          margin-left: 5px;
        }
      }
    }
  }
</style>
