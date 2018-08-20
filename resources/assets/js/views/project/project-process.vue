<template>
  <div class="project-process content">
    <el-row>
      <el-col>
        <info-box>
          <dl>
            <dt>{{ trans('entities.process.id') }}</dt>
            <dd>{{ viewingProcess.engine_process_instance_id }}</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.process.status') }}</dt>
            <dd>{{ viewingProcess.state.name }}</dd>
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
          <div class="controls">
            <el-button
              size="small"
              type="danger"
              v-if="hasRole('admin')"
              :disabled="!rights.canCancelProcess"
              @click="onCancelProcess"
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
                step.state.name_key === 'done' ? 'finish' :
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
            <el-table-column
              prop="definition.name"
              :label="trans('entities.general.name')"
              class-name="name">
            </el-table-column>
            <el-table-column
              :label="trans('entities.general.status')"
              width="180px"
              class-name="status">
              <template slot-scope="scope">
                <span>{{ scope.row.state.name }}</span>
                <i :class="'el-icon-lpa-' + scope.row.state.name_key"></i>
              </template>
            </el-table-column>
            <el-table-column
              :label="trans('entities.general.updated')">
              <template slot-scope="scope">
                <span>{{ scope.row.updated_by ? scope.row.updated_by.name : trans('entities.general.na') }}</span>
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
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '@/event-bus.js';

  import HttpStatusCodes from '@axios/http-status-codes';

  import InfoBox from '@components/info-box.vue';

  let namespace = 'processes';

  export default {
    name: 'project-process',

    components: { InfoBox },

    data() {
      return {
        processId: null,
        activeStep: 0,
        selectedIndex: null,
        rights: {
          canCancelProcess: false
        }
      }
    },

    computed: {
      ...mapGetters({
        language: 'language',
        viewingProcess: `${namespace}/viewing`,
        hasRole: 'users/hasRole'
      }),

      steps() {
        return _.sortBy(this.viewingProcess.steps, 'definition.display_sequence');
      },

      forms() {
        // steps might not be loaded yet
        if (!this.steps[this.selectedIndex]) {
          return [];
        }

        return _.sortBy(this.steps[this.selectedIndex].forms, 'definition.display_sequence');
      }
    },

    methods: {
      ...mapActions({
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        loadProject: 'projects/loadProject',
        loadProcessInstance: `${namespace}/loadInstance`,
        cancelProcessInstance: `${namespace}/cancelInstance`,
        canCancelProcess: `${namespace}/canCancelProcess`
      }),

      viewForm(form) {
        let processId = this.$route.params.processId;
        this.$router.push(`${processId}/form/${form.id}`);
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

      onCancelProcess() {
        this.confirmCancelProcess().then(async () => {
          await this.showMainLoading();
          try {
            let response = await this.cancelProcessInstance(this.processId);
            this.notifySuccess({
              message: this.trans('components.notice.message.process_cancelled')
            });
            // Reload process intance info.
            await this.triggerLoadProcessInstance();
            this.rights.canCancelProcess = false;
          } catch ({ response }) {
            //@todo: Add logic to handle exceptions.
          }
          await this.hideMainLoading();
        }).catch(() => false);
      },

      async triggerLoadProject() {
        let projectId = this.$route.params.projectId;
        await this.loadProject(projectId);
      },

      async triggerLoadProcessInstance() {
        let processId = this.$route.params.processId;
        await this.loadProcessInstance(processId);

        this.activeStep = this.getActiveStep();
        // make sure that the selectedIndex is updated
        this.selectedIndex = this.activeStep;
      },

      async fetch() {
        await this.showMainLoading();
        try {
          await this.triggerLoadProject();
          await this.triggerLoadProcessInstance();
        } catch(e) {
          this.$router.replace(`/${this.language}/${HttpStatusCodes.NOT_FOUND}`);
        }
        await this.hideMainLoading();
      }
    },

    beforeRouteLeave(to, from, next) {
      // Destroy any events we might be listening
      // so that they do not get called while being on another page
      EventBus.$off('Store:languageUpdate', this.fetch);
      next();
    },

    beforeRouteEnter(to, from, next) {
      next(vm => {
        vm.fetch();
      });
    },

    async created() {
      this.processId = this.$route.params.processId;
      this.rights.canCancelProcess = await this.canCancelProcess(this.processId);
    },

    mounted() {
      EventBus.$emit('App:ready');
      EventBus.$on('Store:languageUpdate', this.fetch);
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
            @include svg(locked, map-get($color-form-states, locked));
          }
          .el-step__title.is-wait, .el-step__description.is-wait {
            color: map-get($color-form-states, locked) !important;
          }
          // Cancelled
          .el-step__head.is-error .el-step__icon-inner {
            @include svg(cancelled, map-get($color-form-states, cancelled));
          }
          .el-step__title.is-error, .el-step__description.is-error {
            color: map-get($color-form-states, cancelled) !important;
          }
        }

        // Unlocked
        .el-step__title.is-process, .el-step__description.is-process {
          color: map-get($color-form-states, unlocked) !important;
        }
        // Locked
        .el-step__title.is-wait, .el-step__description.is-wait {
          color: lighten(map-get($color-form-states, locked), 25%) !important;
        }
        .el-step__head.is-wait .el-step__icon-inner {
          @include svg(locked, lighten(map-get($color-form-states, locked), 25%));
        }
        // Completed
        .el-step__title.is-finish, .el-step__description.is-finish {
          color: map-get($color-form-states, done) !important;
        }
        // Cancelled
        .el-step__title.is-error, .el-step__description.is-error {
          color: lighten(map-get($color-form-states, cancelled), 25%) !important;
        }
        .el-step__head.is-error .el-step__icon-inner {
          @include svg(cancelled, lighten(map-get($color-form-states, cancelled), 25%));
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
      @each $state, $color in $color-form-states {
        .#{$state} .name, .#{$state} .status {
          color: $color;
        }
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
