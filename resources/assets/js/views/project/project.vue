<template>
  <div class="project content">
    <el-row :gutter="20" class="equal-height">
      <el-col :span="canActionsBeVisible ? 18 : 24">
        <project-info :project="viewingProject" @onAfterDelete="onAfterDelete"/>
      </el-col>
      <el-col :span="6" v-if="canActionsBeVisible">
        <el-card shadow="never" class="project-actions">
          <div slot="header">
            <h3>{{ trans('entities.process.actions') }}</h3>
          </div>
          <ul class="project-actions-list">
            <template v-if="viewingProject.current_process">
              <li>
                <el-button type="primary" @click="continueToProcess(viewingProject.current_process.id)">
                  {{ trans('entities.process.view_current') }}
                </el-button>
              </li>
            </template>
            <template v-else>
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
    </el-row>
    <el-row>
      <el-col>
        <el-tabs type="border-card">
          <el-tab-pane>
            <span slot="label"><i class="el-icon el-icon-lpa-learning-product tab-icon"></i> {{ trans('base.navigation.learning_products') }}</span>
            <learning-product-data-tables
              :data="projectLearningProducts" />
          </el-tab-pane>
          <el-tab-pane>
            <span slot="label"><i class="el-icon el-icon-lpa-history"></i> {{ trans('entities.process.history') }}</span>
            <data-tables
              ref="table"
              :search-def="{show: false}"
              :data="dataTables.processesHistory.normalizedList"
              :pagination-def="paginationDef"
              :custom-filters="customFilters"
              @filter-change="onFilterChange"
              @row-click="viewProcess"
              :sort-method="$helpers.localeSort">
              <el-table-column
                prop="definition.name"
                sortable="custom"
                :filters="getColumnFilters(dataTables.processesHistory.normalizedList, 'name')"
                :label="trans('entities.general.name')">
              </el-table-column>
              <el-table-column
                prop="created_at"
                sortable="custom"
                :label="trans('entities.process.started')">
                <template slot-scope="scope">
                  <span>{{ scope.row.created_at }}</span>
                  <br>
                  <span>{{ scope.row.created_by.name }}</span>
                </template>
              </el-table-column>
              <el-table-column
                prop="updated_at"
                sortable="custom"
                :label="trans('entities.general.updated')">
                <template slot-scope="scope">
                  <span>{{ scope.row.updated_at }}</span>
                  <br>
                  <span>{{ scope.row.updated_by.name }}</span>
                </template>
              </el-table-column>
              <el-table-column
                prop="state"
                sortable="custom"
                :filters="getColumnFilters(dataTables.processesHistory.normalizedList, 'state')"
                :label="trans('entities.general.status')">
              </el-table-column>
            </data-tables>
          </el-tab-pane>
        </el-tabs>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapState, mapGetters, mapActions } from 'vuex';

  import Page from '@components/page';
  import ProjectInfo from '@components/project-info.vue';
  import LearningProductDataTables from '@components/data-tables/learning-product-data-tables.vue';

  import TableUtils from '@mixins/table/utils.js';

  let namespace = 'projects';

  const loadData = async ({ to }) => {
    const { projectId } = to ? to.params : this;
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch('projects/loadProject', projectId),
      store.dispatch('processes/loadDefinitions', 'project'),
      store.dispatch('processes/loadHistory', {
        entityType: 'project',
        entityId: projectId
      }),
      store.dispatch('learningProducts/loadProjectLearningProducts', projectId),
    );

    // Exception handled by interceptor
    await axios.all(requests).then(async () => {
      // load all the permissions in the store
      // so that we can access them afterwards
      await axios.all(store.state.processes.definitions.map(
        ({name_key}) => store.dispatch('projects/canStartProcess', {
          projectId: to.params.projectId,
          processDefinitionNameKey: name_key
        })
      ));
    });
  };

  export default {
    name: 'project',

    extends: Page,

    mixins: [ TableUtils ],

    components: { ProjectInfo, LearningProductDataTables },

    computed: {
      ...mapState(`${namespace}`, [
        'processPermissions'
      ]),
      ...mapGetters({
        viewingProject: `${namespace}/viewing`,
        viewingHistory: 'processes/viewingHistory',
        processDefinitions: `processes/definitions`,
        projectLearningProducts: 'learningProducts/projectLearningProducts'
      }),

      canActionsBeVisible() {
        return this.hasRole('owner') || this.hasRole('admin');
      }
    },

    data() {
      return {
        dataTables: {
          processesHistory: {
            normalizedList: [],
            normalizedListAttrs: [
              'id', 'entity_id', 'definition.name', 'created_at', 'updated_at', 'created_by', 'updated_by', 'state.name'
            ]
          }
        }
      };
    },

    methods: {
      ...mapActions({
        loadProject: `${namespace}/loadProject`,
        canStartProcess: `${namespace}/canStartProcess`,
        loadProcessDefinitions: `processes/loadDefinitions`,
        startProcess: `processes/start`,
        loadProcessHistory: 'processes/loadHistory',
        loadProjectLearningProducts: 'learningProducts/loadProjectLearningProducts'
      }),

      viewProcess(process) {
        this.$router.push(`${process.entity_id}/process/${process.id}`);
      },

      onHeaderClick(col, e) {
        this.headerClick(col, e);
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
            let response = await this.startProcess({ nameKey: processNameKey, entityId: this.viewingProject.id });
            this.notifySuccess({
              message: this.trans('components.notice.message.process_started')
            });
            let projectId = this.$route.params.projectId;
            this.$router.push(`${projectId}/process/${response.process_instance.id}`);
          } catch (e) {
            if (e.response) {
              // on error, re-fetch the info just to be in-sync
              loadData();
            } else {
              throw e;
            }
          }
        }).catch(() => false);
      },

      continueToProcess(processId) {
        let projectId = this.$route.params.projectId;
        this.$router.push(`${projectId}/process/${processId}`);
      },

      onAfterDelete() {
        this.goToParentPage();
      }
    },

    beforeRouteEnter(to, from, next) {
      // Exception handled by interceptor
      loadData({to}).then(() => {
        next(vm => {
          vm.dataTables.processesHistory.normalizedList = _.map(vm.viewingHistory, process => {
            let normProcess = _.pick(process, vm.dataTables.processesHistory.normalizedListAttrs);
            normProcess.state = normProcess.state.name;
            normProcess.name = normProcess.definition.name;
            return normProcess;
          });
        });
      });
    },

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      loadData({to}).then(next);
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .project {
    margin: 0 auto;

    .project-actions {
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
  }

  .el-icon-lpa-learning-product.tab-icon {
    @include svg(learning-product, $--color-primary);
  }
</style>
