<template>
  <div class="learning-product-list content">
    <div class="controls" v-if="hasRole('owner') || hasRole('admin')">
      <el-button @click="goToPage('learning-product-create')">{{ trans('pages.learning_product_list.create_learning_product') }}</el-button>
    </div>
    <learning-product-data-tables
     :data="learningProducts" />
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';

  import Page from '@components/page';
  import LearningProductDataTables from '@components/data-tables/learning-product-data-tables.vue';

  import PageUtils from '@mixins/page/utils.js';

  let namespace = 'learningProducts';

  export default {
    name: 'learning-product-list',

    extends: Page,

    mixins: [ PageUtils ],

    components: { LearningProductDataTables },

    computed: {
      ...mapGetters({
        learningProducts: `${namespace}/all`
      })
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
