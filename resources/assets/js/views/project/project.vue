<template>
  <div class="project content">
    <el-row :gutter="20" class="equal-height">
      <el-col :span="canBeVisible ? 18 : 24">
        <project-info :project="viewingProject" @onAfterDelete="onAfterDelete"/>
      </el-col>
      <el-col :span="6" v-if="canBeVisible">
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
              <li v-for="(process, index) in definitions" :key="index">
                <el-button
                  type="primary"
                  :disabled="!processDefinitionPermissions[process.name_key]"
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
            <entity-data-tables
              entityType="learning-product"
              :data="projectLearningProducts"
              :attributes="dataTableAttributes.learningProducts"
            />
          </el-tab-pane>
          <el-tab-pane>
            <span slot="label"><i class="el-icon el-icon-lpa-history"></i> {{ trans('entities.process.history') }}</span>
            <entity-data-tables
              entityType="process"
              :data="viewingHistory"
              :attributes="dataTableAttributes.processHistory"
            />
          </el-tab-pane>
        </el-tabs>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';

  import Page from '@components/page';
  import ProjectInfo from '@components/project-info.vue';
  import EntityDataTables from '@components/data-tables/entity-data-tables.vue';

  import TableUtils from '@mixins/table/utils.js';

  let namespace = 'projects';

  export default {
    name: 'project',

    extends: Page,

    components: { ProjectInfo, EntityDataTables },

    computed: {
      ...mapGetters({
        viewingProject: `${namespace}/viewing`,
        definitions: `processes/definitions`,
        viewingHistory: 'processes/viewingHistory',
        projectLearningProducts: 'learningProducts/projectLearningProducts'
      }),

      dataTableAttributes: {
        get() {
          return {
            learningProducts: {
              id: {
                label: this.trans('entities.general.lpa_num'),
                minWidth: 12
              },
              name: {
                label: this.trans('entities.general.name'),
                minWidth: 36
              },
              type: {
                label: this.trans('entities.learning_product.type'),
                minWidth: 13
              },
              organizational_unit: {
                label: this.$tc('entities.general.organizational_units'),
                minWidth: 25
              },
              updated_at: {
                label: this.trans('entities.general.updated'),
                minWidth: 20
              },
              state: {
                label: this.trans('entities.general.status'),
                minWidth: 14
              },
              'current_process.definition': {
                label: this.trans('entities.process.current'),
                minWidth: 21
              }
            },

            processHistory: {
              definition: {
                label: this.trans('entities.general.name')
              },
              created_at: {
                label: this.trans('entities.process.started')
              },
              updated_at: {
                label: this.trans('entities.general.updated')
              },
              state: {
                label: this.trans('entities.general.status')
              }
            }
          }
        }
      },

      canBeVisible() {
        return this.hasRole('owner') || this.hasRole('admin');
      }
    },

    data() {
      return {
        processDefinitionPermissions: {},
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
              this.loadData();
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

      async loadData() {
        let projectId = this.$route.params.projectId;
        let requests = [];

        requests.push(
          this.loadProject(projectId),
          this.loadProcessDefinitions('project'),
          this.loadProcessHistory({ entityType: 'project', entityId: projectId }),
          this.loadProjectLearningProducts(projectId)
        );

        await axios.all(requests).then(() => {
          this.loadPermissions();
        });
      },

      async loadPermissions() {
        // @fixme: axios.all
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

      onAfterDelete() {
        this.goToParentPage();
      }
    },

    beforeRouteEnter(to, from, next) {
      store.dispatch('projects/loadProject', to.params.projectId)
        .then(() => {
          next(vm => {
            vm.loadData();
          });
        });
    },

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      this.loadData().then(next);
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
