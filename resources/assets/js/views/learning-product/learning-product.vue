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

  const loadData = async function ({ to } = {}) {
    const { learningProductId } = to ? to.params : this;
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];

    requests.push(
      store.dispatch(`${namespace}/loadLearningProduct`, learningProductId),
      store.dispatch(`${namespace}/loadCanEdit`, learningProductId),
      store.dispatch(`${namespace}/loadCanDelete`, learningProductId)
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

      onAfterDelete() {
        this.goToParentPage();
      }
    },

    async beforeRouteEnter(to, from, next) {
      await loadData({to});
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

  .entity {
    margin: 0 auto;
  }
</style>
