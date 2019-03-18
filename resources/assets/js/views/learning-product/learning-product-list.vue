<template>
  <div>
    <el-row>
      <el-col>
        <el-card-wrap
          icon="el-icon-lpa-learning-product"
          :headerTitle="trans('base.navigation.learning_products')"
        >
          <template slot="controls" v-if="hasRole('owner') || hasRole('admin')">
            <el-control-wrap
              componentName="el-button"
              :tooltip="trans('pages.learning_product_list.create_learning_product')"
              size="mini"
              icon="el-icon-lpa-add"
              @click.native="goToPage('learning-product-create')"
            />
          </template>
        </el-card-wrap>
      </el-col>
    </el-row>
    <el-row>
      <el-col>
        <entity-data-table
          :data="all"
          :attributes="dataTableAttributes.learningProducts"
          @rowClick="onLearningProductRowClick"
          @formatData="onFormatData"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapState } from 'vuex';

  import Page from '@components/page';
  import EntityDataTable from '@components/entity-data-table.vue';
  import ElCardWrap from '@components/el-card-wrap';
  import ElControlWrap from '@components/el-control-wrap';

  import LearningProduct from '@/store/models/Learning-Product';

  const loadData = async () => {
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch('entities/learningProducts/loadAll')
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

    components: { EntityDataTable, ElCardWrap, ElControlWrap },

    computed: {
      all() {
        return LearningProduct.all();
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
            }
          }
        }
      }
    },

    methods: {
      loadData,

      onLearningProductRowClick(learningProduct) {
        this.scrollToTop();
        this.$router.push(`/${this.language}/learning-products/${learningProduct.id}`);
      },

      onFormatData(normEntity) {
        normEntity.type = this.$options.filters.learningProductTypeSubTypeFilter(normEntity);
      }
    },

    async beforeRouteEnter(to, from, next) {
      await loadData();
      next();
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .learning-product-list {
    i.el-icon-lpa-learning-product {
      @include svg(learning-product, $--color-primary);
    }
  }
</style>
