<template>
  <div class="entity content">
    <el-row>
      <el-col>
        <process-current-bar :data="viewingLearningProduct" type="learning-product"/>
      </el-col>
    </el-row>
    <el-row :gutter="20" class="equal-height">
      <el-col :span="canBeVisible ? 18 : 24">
        <learning-product-info :learningProduct="viewingLearningProduct" v-on:learning-product-info:operation-denied="fetch(false)"/>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '@/event-bus.js';
  import PageUtils from '@mixins/page/utils.js';
  import TableUtils from '@mixins/table/utils.js';
  import ProcessCurrentBar from '@components/process-current-bar.vue';
  import LearningProductInfo from '@components/learning-product-info.vue';

  let namespace = 'learningProducts';

  export default {
    name: 'learning-products',

    components: { ProcessCurrentBar, LearningProductInfo },

    computed: {
      ...mapGetters({
        language: 'language',
        hasRole: 'users/hasRole',
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

      async fetch(isInitialLoad = true) {
        try {
          let learningProductId = this.$route.params.learningProductId;
          // @note: project info is loaded in the router's beforeEnter
          // do not reload the project info on page load
          if (!isInitialLoad) {
            await this.loadLearningProduct(learningProductId);
          }
        } catch (e) {
          // Exception handled by interceptor
          if (!e.response) {
            throw e;
          }
        }
      },

      async onLanguageUpdate() {
        this.fetch(false);
      }
    },

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      this.onLanguageUpdate();
      next();
    },

    beforeRouteEnter(to, from, next) {
      next(vm => {
        vm.fetch();
      });
    },

    mounted() {
      EventBus.$emit('App:ready');
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
