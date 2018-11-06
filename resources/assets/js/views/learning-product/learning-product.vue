<template>
  <div class="entity content">
    <el-row :gutter="20" class="equal-height">
      <el-col :span="canBeVisible ? 18 : 24">
        <learning-product-info :learningProduct="viewingLearningProduct" @onAfterDelete="onAfterDelete"/>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';

  import Page from '@components/page';
  import LearningProductInfo from '@components/learning-product-info.vue';

  let namespace = 'learningProducts';

  export default {
    name: 'learning-products',

    extends: Page,

    components: { LearningProductInfo },

    computed: {
      ...mapGetters({
        viewingLearningProduct: `${namespace}/viewing`
      }),

      canBeVisible() {
        return this.hasRole('owner') || this.hasRole('admin');
      }
    },

    methods: {
      ...mapActions({
        loadLearningProduct: `${namespace}/loadLearningProduct`
      }),

      viewProcess(process) {
        this.$router.push(`${process.entity_id}/process/${process.id}`);
      },

      async loadData() {
        try {
          let learningProductId = this.$route.params.learningProductId;
          await this.loadLearningProduct(learningProductId);
        } catch (e) {
          // Exception handled by interceptor
          if (!e.response) {
            throw e;
          }
        }
      },

      onAfterDelete() {
        this.goToParentPage();
      }
    },

    beforeRouteEnter(to, from, next) {
      store.dispatch('learningProducts/loadLearningProduct', to.params.learningProductId)
        .then(next);
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

  .entity {
    margin: 0 auto;
  }
</style>
