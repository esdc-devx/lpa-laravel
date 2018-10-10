<template>
  <div class="project content">
    <el-row>
      <el-col>
        <process-current-bar :data="viewingProject" type="project"/>
      </el-col>
    </el-row>
    <el-row :gutter="20" class="equal-height">
      <el-col :span="canBeVisible ? 18 : 24">
        <project-info :project="viewingProject"/>
      </el-col>
      <el-col :span="6" v-if="canBeVisible">
        <el-card shadow="never" class="project-actions">
          <div slot="header">
            <h3>{{ trans('entities.process.start') }}</h3>
          </div>
          <ul class="project-actions-list">
            <li>
              <el-button
                v-for="(process, index) in definitions"
                :key="index"
                :disabled="!processDefinitionPermissions[process.name_key]"
                @click="triggerStartProcess(process.name, process.name_key)" plain>
                  {{ process.name }}
              </el-button>
            </li>
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
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '@/event-bus.js';
  import PageUtils from '@mixins/page/utils.js';
  import TableUtils from '@mixins/table/utils.js';
  import ProcessCurrentBar from '@components/process-current-bar.vue';
  import ProjectInfo from '@components/project-info.vue';
  import LearningProductDataTables from '@components/data-tables/learning-product-data-tables.vue';

  let namespace = 'projects';

  export default {
    name: 'project',

    mixins: [ PageUtils, TableUtils ],

    components: { ProcessCurrentBar, ProjectInfo, LearningProductDataTables },

    computed: {
      ...mapGetters({
        language: 'language',
        hasRole: 'users/hasRole',
        viewingProject: `${namespace}/viewing`,
        definitions: `processes/definitions`,
        viewingHistory: 'processes/viewingHistory',
        projectLearningProducts: 'learningProducts/projectLearningProducts'
      }),

      canBeVisible() {
        return this.hasRole('owner') || this.hasRole('admin');
      }
    },

    data() {
      return {
        processDefinitionPermissions: {},
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
              this.fetch();
            } else {
              throw e;
            }
          }
        }).catch(() => false);
      },

      async getProcessDefinitionPermissions() {
        let definition;
        for (let i = 0; i < this.definitions.length; i++) {
          definition = this.definitions[i];
          // add reactive properties
          this.$set(
            this.processDefinitionPermissions,
            definition.name_key,
            await this.canStartProcess({ projectId: this.$route.params.projectId, processDefinitionNameKey: definition.name_key })
          );
        }
      },

      async fetch(isInitialLoad = true) {
        try {
          let projectId = this.$route.params.projectId;
          // @note: project info is loaded in the router's beforeEnter
          // do not reload the project info on page load
          if (!isInitialLoad) {
            await this.loadProject(projectId);
          }
          await this.loadProcessDefinitions('project');
          await this.loadProcessHistory({ entityType: 'project', entityId: projectId });

          this.dataTables.processesHistory.normalizedList = _.map(this.viewingHistory, process => {
            let normProcess = _.pick(process, this.dataTables.processesHistory.normalizedListAttrs);
            normProcess.state = normProcess.state.name;
            normProcess.name = normProcess.definition.name;
            return normProcess;
          });

          await this.loadProjectLearningProducts(projectId);
          this.getProcessDefinitionPermissions();
        } catch (e) {
          // Exception handled by interceptor
          if (!e.response) {
            throw e;
          }
        }
      },

      async onLanguageUpdate() {
        this.fetch(false);
      }
    },

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      this.onLanguageUpdate();
      next();
    },

    beforeRouteEnter(to, from, next) {
      next(vm => {
        vm.fetch();
      });
    },

    mounted() {
      EventBus.$emit('App:ready');
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
        background-color: #8cc63f;
        h3 {
          margin: 0;
          padding: 3px 0px;
          text-align: center;
          display: block;
          color: $--color-white;
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
