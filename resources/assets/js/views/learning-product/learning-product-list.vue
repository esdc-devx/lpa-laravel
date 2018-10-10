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
  import EventBus from '@/event-bus.js';
  import LearningProductDataTables from '@components/data-tables/learning-product-data-tables.vue';
  import PageUtils from '@mixins/page/utils.js';
  
  let namespace = 'learningProducts';

  export default {
    name: 'learning-product-list',

    mixins: [ PageUtils ],

    components: { LearningProductDataTables },

    computed: {
      ...mapGetters({
        language: 'language',
        hasRole: 'users/hasRole',
        learningProducts: `${namespace}/all`
      })
    },

    methods: {
      ...mapActions({
        loadLearningProducts: `${namespace}/loadLearningProducts`
      }),

      async triggerLoadLearningProducts() {
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

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      this.triggerLoadLearningProducts();
      next();
    },

    created() {
      this.triggerLoadLearningProducts();
    },

    mounted() {
      EventBus.$emit('App:ready');
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
