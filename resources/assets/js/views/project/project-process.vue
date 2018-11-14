<template>
  <div class="project-process content">
    <el-row>
      <el-col>
        <info-box>
          <dl>
            <dt>{{ trans('entities.general.status') }}</dt>
            <dd><span :class="'state ' + viewingProcess.state.name_key">{{ viewingProcess.state.name }}</span></dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.process.started') }}</dt>
            <dd>{{ viewingProcess.created_by.name }}</dd>
            <dd>{{ viewingProcess.created_at }}</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.general.updated') }}</dt>
            <dd>{{ viewingProcess.updated_by.name }}</dd>
            <dd>{{ viewingProcess.updated_at }}</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.process.id') }}</dt>
            <dd>{{ viewingProcess.engine_process_instance_id }}</dd>
          </dl>
          <div class="controls">
            <el-button
              size="small"
              type="danger"
              v-if="hasRole('admin')"
              :disabled="!permissions.canCancel"
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
            <h3>{{ viewingProcess.definition.name }}</h3>
          </div>
          <el-steps :active="activeStep" finish-status="finish" align-center>
            <el-step
              v-for="(step, index) in steps"
              :class="{ 'is-selected': selectedIndex === index }"
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
              :label="$tc('entities.general.assigned_organizational_units')"
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
                <span>{{ scope.row.updated_by ? scope.row.updated_by.name : trans('entities.general.none') }}</span>
                <br>
                <span>{{ scope.row.updated_at }}</span>
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
  import { mapState, mapGetters, mapActions } from 'vuex';

  import Page from '@components/page';
  import InfoBox from '@components/info-box.vue';

  let namespace = 'processes';

  const loadData = async function ({ to } = {}) {
    const { projectId, processId } = to ? to.params : this;
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch('projects/loadProject', projectId),
      store.dispatch(`${namespace}/loadInstance`, processId),
      store.dispatch(`${namespace}/loadCanCancel`, processId)
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
    name: 'project-process',

    extends: Page,

    components: { InfoBox },

    data() {
      return {
        projectId: null,
        processId: null,
        activeStep: 0
      }
    },

    computed: {
      ...mapState(`${namespace}`, [
        'permissions'
      ]),
      ...mapGetters({
        viewingProcess: `${namespace}/viewing`
      }),

      selectedIndex: {
        get() {
          return this.activeStep;
        },
        set(val) {
          this.activeStep = val;
        }
      },

      steps() {
        return _.sortBy(this.viewingProcess.steps, 'definition.display_sequence');
      },

      forms() {
        return _.sortBy(this.viewingProcess.steps[this.selectedIndex].forms, 'definition.display_sequence');
      }
    },

    methods: {
      ...mapActions({
        loadProject: 'projects/loadProject',
        loadProcessInstance: `${namespace}/loadInstance`,
        cancelProcessInstance: `${namespace}/cancelInstance`
      }),

      viewForm(form) {
        this.$router.push(`${this.processId}/form/${form.id}`);
      },

      getFormRowClassName({row, rowIndex}) {
        return row.state.name_key;
      },

      getActiveStep() {
        // when process is completed, just use the last step as the active one
        if (this.viewingProcess.state.name_key === 'completed') {
          return this.viewingProcess.steps.length - 1;
        }

        let active = 0;
        // grab the last step that is not locked
        _.forEach(this.viewingProcess.steps, (step, i) => {
          if (step.state.name_key !== 'locked') {
            active = i;
          }
        });
        return active;
      },

      onStepChange(index) {
        this.selectedIndex = index;
      },

      onCancel() {
        this.confirmCancelProcess().then(async () => {
          try {
            let response = await this.cancelProcessInstance(this.processId);
            this.notifySuccess({
              message: this.trans('components.notice.message.process_cancelled')
            });
            await loadData.apply(this);
            this.$store.commit(`${namespace}/setPermission`, {
              name: 'canCancel',
              isAllowed: false
            });
          } catch (e) {
            // Exception handled by interceptor
            if (!e.response) {
              throw e;
            }
          }
        }).catch(() => false);
      }
    },

    // This makes sure that before entering the route, that we set the active step
    // so that when the page is rendered, the correct step is selected.
    async beforeRouteEnter(to, from, next) {
      await loadData({to});
      next(vm => {
        vm.selectedIndex = vm.getActiveStep();
      });
    },

    // called when url params change, e.g: language
    async beforeRouteUpdate(to, from, next) {
      await loadData.apply(this);
      this.selectedIndex = this.getActiveStep();
      next();
    },

    created() {
      this.projectId = this.$route.params.projectId;
      this.processId = this.$route.params.processId;
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';
  @import '~@sass/base/helpers';

  .project-process {
    margin: 0 auto;

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

    .process-steps {
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

    .process-forms-table {
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
  }
</style>
