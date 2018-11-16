<template>
  <div class="learning-product-list content">
    <div class="controls" v-if="hasRole('owner') || hasRole('admin')">
      <el-button @click="goToPage('learning-product-create')">{{ trans('pages.learning_product_list.create_learning_product') }}</el-button>
    </div>
    <entity-data-tables
      entityType="learning-product"
      :data="learningProducts"
      :attributes="dataTableAttributes.learningProducts"
    />
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';

  import Page from '@components/page';
  import EntityDataTables from '@components/entity-data-tables.vue';

  let namespace = 'learningProducts';

  export default {
    name: 'learning-product-list',

    extends: Page,

    components: { EntityDataTables },

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
            }
          }
        }
      }
    },

    methods: {
      ...mapActions({
        loadLearningProducts: `${namespace}/loadLearningProducts`
      }),

      async loadData() {
        try {
          await this.loadLearningProducts();
        } catch (e) {
          // Exception handled by interceptor
          if (!e.response) {
            throw e;
          }
        }
      }
    },

    beforeRouteEnter(to, from, next) {
      next(vm => {
        vm.loadData();
      });
    },

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      this.loadData().then(next);
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
