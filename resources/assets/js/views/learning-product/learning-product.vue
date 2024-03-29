<template>
  <div>
    <el-row :gutter="20" class="equal-height">
      <el-col>
        <infobox-learning-product
          :learningProduct="viewing"
          @delete="onDelete"
        />
      </el-col>
      <process-actions-col
        :entity="viewing"
        :entityType="viewing.type.name_key"
        :processPermissions="processPermissions"
        @error="onError"
      />
    </el-row>
    <el-row>
      <el-col>
        <el-tabs type="border-card">
          <!-- Process history -->
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
  import InfoboxLearningProduct from '@components/infoboxes/infobox-learning-product';
  import EntityDataTable from '@components/entity-data-table';
  import ProcessActionsCol from '@components/process-actions-col';

  import LearningProduct from '@/store/models/Learning-Product';

  const loadData = async function ({ to } = {}) {
    const { entityId } = to ? to.params : this;
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];

    requests.push(
      store.dispatch('entities/learningProducts/load', entityId),
      store.dispatch('authorizations/learningProducts/loadCanEdit', entityId),
      store.dispatch('authorizations/learningProducts/loadCanDelete', entityId)
    );

    // Exception handled by interceptor
    try {
      await axios.all(requests).then(async () => {
        // load all the permissions in the store
        // so that we can access them afterwards
        try {
          const learningProduct = LearningProduct.find(entityId);
          await axios.all([
            store.dispatch('entities/processes/loadDefinitions', learningProduct.type.name_key),
            store.dispatch('entities/processes/loadHistory', { entityType: learningProduct.type.name_key, entityId })
          ]);
          await axios.all(store.state.entities.processes.definitions.map(
            ({name_key}) => store.dispatch('authorizations/learningProducts/loadCanStartProcess', {
              learningProductId: entityId,
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
    name: 'learning-products',

    extends: Page,

    components: { InfoboxLearningProduct, EntityDataTable, ProcessActionsCol },

    props: {
      entityId: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        localViewing: null
      };
    },

    computed: {
      ...mapState('authorizations/learningProducts', [
        'processPermissions'
      ]),
      ...mapState('entities/processes', {
        viewingHistory: 'viewingHistory'
      }),

      viewing() {
        return this.localViewing || LearningProduct.find(this.entityId);
      },

      dataTableAttributes: {
        get() {
          return {
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
      }
    },

    methods: {
      ...mapActions('entities/learningProducts', [
        '$$delete'
      ]),

      loadData,

      viewProcess(process) {
        this.$router.push(`${process.entity_id}/process/${process.id}`);
      },

      onProcessRowClick(process) {
        this.scrollToTop();
        this.$router.push(`${process.entity_id}/process/${process.id}`);
      },

      async onDelete() {
        try {
          // By deleting the learning-product, we are affecting the viewing learning-product
          // and so after deleting it, the info on the page will react and be empty.
          // To avoid that, we store a local version of the learning-product to be deleted
          // so that when we reload the list in the store while transitioning to the learning-product-list
          // that we still have acces to the info
          this.localViewing = {...this.viewing};
          await this.$$delete(this.viewing.id);
          this.notifySuccess({
            message: this.trans('components.notice.message.learning_product_deleted')
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
      await loadData({ to });
      next();
    }
  };
</script>

<style lang="scss">

</style>
