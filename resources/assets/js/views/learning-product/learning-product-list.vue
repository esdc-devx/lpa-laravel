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
    />
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';

  import Page from '@components/page';
  import EntityDataTable from '@components/entity-data-table.vue';

  let namespace = 'learningProducts';

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
                isSortedAlphabetically: true
              },
              organizational_unit: {
                label: this.$tc('entities.general.organizational_units'),
                minWidth: 25,
                isSortedAlphabetically: true
              },
              updated_at: {
                label: this.trans('entities.general.updated'),
                minWidth: 20
              },
              state: {
                label: this.trans('entities.general.status'),
                minWidth: 14,
                isSortedAlphabetically: true
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

      onLearningProductRowClick(learningProduct) {
        this.scrollToTop();
        this.$router.push(`/${this.language}/learning-products/${learningProduct.id}`);
      },

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
