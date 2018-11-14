<template>
  <div class="learning-product-list content">
    <div class="controls" v-if="hasRole('owner') || hasRole('admin')">
      <el-button @click="goToPage('learning-product-create')">{{ trans('pages.learning_product_list.create_learning_product') }}</el-button>
    </div>
    <entity-data-table
      entityType="learning-product"
      :data="learningProducts"
      :attributes="dataTableAttributes.learningProducts"
      @rowClick="onLearningProductRowClick"
      @formatData="onFormatData"
    />
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters } from 'vuex';

  import Page from '@components/page';
  import EntityDataTable from '@components/entity-data-table.vue';

  let namespace = 'learningProducts';

  const loadData = async () => {
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch(`${namespace}/loadLearningProducts`)
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
    name: 'learning-product-list',

    extends: Page,

    components: { EntityDataTable },

    computed: {
      ...mapGetters({
        learningProducts: `${namespace}/all`
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
            }
          }
        }
      }
    },

    methods: {
      onLearningProductRowClick(learningProduct) {
        this.scrollToTop();
        this.$router.push(`/${this.language}/learning-products/${learningProduct.id}`);
      },

      onFormatData(normEntity) {
        normEntity.type = this.$options.filters.learningProductTypeSubTypeFilter(normEntity.type.name, normEntity.sub_type.name);
      }
    },

    async beforeRouteEnter(to, from, next) {
      await loadData();
      next();
    },

    // called when url params change, e.g: language
    async beforeRouteUpdate(to, from, next) {
      await loadData();
      next();
    }
  };
</script>

<style lang="scss">
  .learning-product-list {

    .controls {
      margin-bottom: 0;
    }
  }
</style>
