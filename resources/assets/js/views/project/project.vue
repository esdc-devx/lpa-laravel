<template>
  <div>
    <el-row :gutter="20" class="equal-height">
      <el-col>
        <infobox-project
          :project="viewing"
          @delete="onDelete"
        />
      </el-col>
      <process-actions-col
        :entity="viewing"
        entityType="project"
        :processPermissions="processPermissions"
        @error="onError"
      />
    </el-row>
    <el-row>
      <el-col>
        <el-tabs type="border-card">
          <!-- Learning products -->
          <el-tab-pane>
            <span slot="label"><i class="el-icon el-icon-lpa-learning-product tab-icon"></i> {{ trans('base.navigation.learning_products') }}</span>
            <entity-data-table
              :data="projectLearningProducts"
              :attributes="dataTableAttributes.learningProducts"
              @rowClick="onLearningProductRowClick"
              @formatData="onFormatData"
            />
          </el-tab-pane>
          <!-- Process history -->
          <el-tab-pane>
            <span slot="label"><i class="el-icon el-icon-lpa-history"></i> {{ trans('entities.process.history') }}</span>
            <entity-data-table
              :data="viewingHistory"
              :attributes="dataTableAttributes.processHistory"
              @rowClick="onProcessRowClick"
            />
          </el-tab-pane>
          <!-- Information sheets -->
          <el-tab-pane>
            <span slot="label"><i class="el-icon-lpa-info-sheet"></i> <b>{{ $tc('entities.general.information_sheet', 2) }}</b></span>
            <el-table
              class="information-sheets-table"
              :data="informationSheets"
              stripe
            >
              <el-table-column :label="trans('entities.general.name')">
                <template slot-scope="scope">
                  <a :href="`/${language}/information-sheets/${scope.row.id}`" target="_blank">{{ scope.row.definition.name }}</a>
                </template>
              </el-table-column>
            </el-table>
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
  import InfoboxProject from '@components/infoboxes/infobox-project';
  import EntityDataTable from '@components/entity-data-table';
  import ProcessActionsCol from '@components/process-actions-col';

  import HttpStatusCodes from '@axios/http-status-codes';

  import InformationSheet from '@/store/models/Information-Sheet';
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
      store.dispatch('entities/processes/loadHistory', { entityType: 'project', entityId }),
      store.dispatch('entities/informationSheets/loadAll', { entityType: 'project', entityId }),
      store.dispatch('entities/learningProducts/loadProjectLearningProducts', entityId),
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

    components: { InfoboxProject, EntityDataTable, ProcessActionsCol },

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
        viewingHistory: 'viewingHistory'
      }),
      ...mapState('entities/learningProducts', [
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

      informationSheets() {
        return InformationSheet.query()
          .where('entity_id', _.parseInt(this.entityId))
          .where('entity_type', 'project')
          .get();
      }
    },

    methods: {
      ...mapActions('entities/projects', [
        '$$delete'
      ]),

      onFormatData(normEntity) {
        normEntity.type = this.$options.filters.learningProductTypeSubTypeFilter(normEntity);
      },

      onLearningProductRowClick(learningProduct) {
        this.scrollToTop();
        this.$router.push(`/${this.language}/learning-products/${learningProduct.id}`);
      },

      onProcessRowClick(process) {
        this.scrollToTop();
        this.$router.push(`${process.entity_id}/process/${process.id}`);
      },

      async onDelete() {
        try {
          // By deleting the project, we are affecting the viewing project
          // and so after deleting it, the info on the page will react and be empty.
          // To avoid that, we store a local version of the project to be deleted
          // so that when we reload the list in the store while transitioning to the project-list
          // that we still have acces to the info
          this.localViewing = {...this.viewing};
          await this.$$delete(this.viewing.id);
          this.notifySuccess({
            message: this.trans('components.notice.message.project_deleted')
          });
          this.goToParentPage();
        } catch (e) {
          // Exception handled by interceptor
          if (!e.response) {
            throw e;
          }
        }
      },

      onError() {
        loadData.apply(this);
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

    .information-sheets-table {
      .el-table__row {
        cursor: default;
      }
    }
  }

  .el-icon-lpa-learning-product.tab-icon {
    @include svg(learning-product, $--color-primary);
  }
</style>
