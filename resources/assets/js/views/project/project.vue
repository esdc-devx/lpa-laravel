<template>
  <div class="project content">
    <el-row :gutter="20" class="equal-height">
      <el-col :span="canActionsBeVisible ? 18 : 24">
        <project-info
          :project="viewing"
          @delete="onDelete"
        />
      </el-col>
      <el-col :span="6" v-if="canActionsBeVisible">
        <el-card shadow="never" class="project-actions">
          <div slot="header">
            <h3>{{ trans('entities.process.actions') }}</h3>
          </div>
          <ul class="project-actions-list">
            <template v-if="viewing.current_process">
              <li>
                <el-button type="primary" @click="continueToProcess(viewing.current_process.id)">
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
            <entity-data-table
              :data="projectLearningProducts"
              :attributes="dataTableAttributes.learningProducts"
              @rowClick="onLearningProductRowClick"
              @formatData="onFormatData"
            />
          </el-tab-pane>
          <el-tab-pane>
            <span slot="label"><i class="el-icon el-icon-lpa-history"></i> {{ trans('entities.process.history') }}</span>
            <entity-data-table
              :data="viewingHistory"
              :attributes="dataTableAttributes.processHistory"
              @rowClick="onProcessRowClick"
            />
          </el-tab-pane>
        </el-tabs>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapState, mapActions } from 'vuex';

  import Page from '@components/page';
  import ProjectInfo from '@components/project-info.vue';
  import EntityDataTable from '@components/entity-data-table.vue';

  import HttpStatusCodes from '@axios/http-status-codes';

  import Project from '@/store/models/Project';

  const loadData = async function ({ to } = {}) {
    const { entityId } = to ? to.params : this;
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch('entities/projects/load', entityId),
      store.dispatch('entities/processes/loadDefinitions', 'project'),
      store.dispatch('entities/processes/loadHistory', {
        entityType: 'project',
        entityId
      }),
      store.dispatch('learningProducts/loadProjectLearningProducts', entityId),
      store.dispatch('authorizations/projects/loadCanEdit', entityId),
      store.dispatch('authorizations/projects/loadCanDelete', entityId)
    );

    // Exception handled by interceptor
    try {
      await axios.all(requests).then(async () => {
        // load all the permissions in the store
        // so that we can access them afterwards
        try {
          await axios.all(store.state.entities.processes.definitions.map(
            ({name_key}) => store.dispatch('authorizations/projects/loadCanStartProcess', {
              projectId: entityId,
              processDefinitionNameKey: name_key
            })
          ));
        } catch (e) {
          if (!e.response) {
            throw e;
          }
        }
      });
    } catch (e) {
      if (!e.response) {
        throw e;
      }
    }
  };

  export default {
    name: 'project',

    extends: Page,

    components: { ProjectInfo, EntityDataTable },

    props: {
      entityId: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        processDefinitionPermissions: {},
        localViewing: null
      };
    },

    computed: {
      ...mapState('authorizations/projects', [
        'processPermissions'
      ]),
      ...mapState('entities/processes', {
        viewingHistory: 'viewingHistory',
        processDefinitions: 'definitions'
      }),
      ...mapState('learningProducts', [
        'projectLearningProducts'
      ]),

      viewing() {
        return this.localViewing || Project.find(this.entityId);
      },

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
                minWidth: 13,
                areFiltersSorted: true,
                isFilterable: true
              },
              sub_type: {
                isColumn: false
              },
              organizational_unit: {
                label: this.$tc('entities.general.organizational_units'),
                minWidth: 25,
                areFiltersSorted: true,
                isFilterable: true
              },
              updated_at: {
                label: this.trans('entities.general.updated'),
                minWidth: 20
              },
              updated_by: {
                isColumn: false
              },
              state: {
                label: this.trans('entities.general.status'),
                minWidth: 14,
                areFiltersSorted: true,
                isFilterable: true
              },
              'current_process.definition': {
                label: this.trans('entities.process.current'),
                minWidth: 21,
                isFilterable: true
              }
            },

            processHistory: {
              id: {
                isColumn: false
              },
              entity_id: {
                isColumn: false
              },
              definition: {
                label: this.trans('entities.general.name')
              },
              created_at: {
                label: this.trans('entities.process.started')
              },
              created_by: {
                isColumn: false
              },
              updated_at: {
                label: this.trans('entities.general.updated')
              },
              updated_by: {
                isColumn: false
              },
              state: {
                label: this.trans('entities.general.status'),
                areFiltersSorted: true,
                isFilterable: true
              }
            }
          }
        }
      },

      canActionsBeVisible() {
        return this.hasRole('owner') || this.hasRole('admin');
      }
    },

    methods: {
      ...mapActions({
        startProcess: 'entities/processes/start'
      }),

      ...mapActions('entities/projects', [
        '$$delete'
      ]),

      onFormatData(normEntity) {
        normEntity.type = this.$options.filters.learningProductTypeSubTypeFilter(normEntity.type.name, normEntity.sub_type.name);
      },

      onLearningProductRowClick(learningProduct) {
        this.scrollToTop();
        this.$router.push(`/${this.language}/learning-products/${learningProduct.id}`);
      },

      onProcessRowClick(process) {
        this.scrollToTop();
        this.$router.push(`${process.entity_id}/process/${process.id}`);
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
            let process = await this.startProcess({ nameKey: processNameKey, entityId: this.viewing.id });
            this.notifySuccess({
              message: this.trans('components.notice.message.process_started')
            });
            this.$router.push(`${this.entityId}/process/${process.id}`);
          } catch (e) {
            if (!e.response) {
              throw e;
            } else if (e.response.status === HttpStatusCodes.FORBIDDEN) {
              // on error, re-fetch the info just to be in-sync
              loadData.apply(this);
            }
          }
        }).catch(() => false);
      },

      continueToProcess(processId) {
        this.$router.push(`${this.entityId}/process/${processId}`);
      },

      async onDelete() {
        // store a local version of the project to be deleted
        // so that when we reload the list in the store while transitioning to the project-list
        // that we still have acces to the info
        this.localViewing = {...this.viewing};
        await this.$$delete(this.viewing.id);
        this.notifySuccess({
          message: this.trans('components.notice.message.project_deleted')
        });
        this.goToParentPage();
      }
    },

    async beforeRouteEnter(to, from, next) {
      // Exception handled by interceptor
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
