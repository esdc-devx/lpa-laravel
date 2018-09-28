<template>
  <div class="learning-product-list content">
    <!-- <div class="controls" v-if="hasRole('owner') || hasRole('admin')">
      <el-button @click="goToPage('learning-product-create')">{{ trans('pages.learning_product_list.create_learning_product') }}</el-button>
    </div> -->

    <data-tables
      ref="table"
      :search-def="{show: false}"
      :custom-filters="customFilters"
      :pagination-def="paginationDef"
      :data="normalizedList"
      @filter-change="onFilterChange"
      @current-page-change="scrollToTop"
      @row-click="viewLearningProduct"
      @header-click="onHeaderClick"
      :sort-method="$helpers.localeSort">
      <el-table-column
        sortable="custom"
        prop="id"
        min-width="12"
        :label="trans('entities.general.lpa_num')">
        <template slot-scope="scope">
          {{ scope.row.id | LPANumFilter }}
        </template>
      </el-table-column>
      <el-table-column
        sortable="custom"
        prop="name"
        min-width="36"
        :label="trans('entities.general.name')">
      </el-table-column>
      <el-table-column
        sortable="custom"
        prop="type"
        min-width="20"
        :label="trans('entities.learning_product.type')"
        :filters="typeFilters">
      </el-table-column>
      <el-table-column
        sortable="custom"
        column-key="organizational_unit"
        :filters="orgUnitFilters"
        prop="organizational_unit"
        min-width="25"
        :label="$tc('entities.general.organizational_units')">
      </el-table-column>
      <el-table-column
        sortable="custom"
        prop="updated_at"
        min-width="13"
        :label="trans('entities.general.updated')">
      </el-table-column>
      <el-table-column
        sortable="custom"
        column-key="state"
        :filters="getColumnFilters(this.normalizedList, 'state')"
        prop="state"
        min-width="14"
        :label="trans('entities.general.status')">
      </el-table-column>
      <el-table-column
        sortable="custom"
        column-key="current_process"
        :filters="getColumnFilters(this.normalizedList, 'current_process')"
        prop="current_process"
        min-width="21"
        :label="trans('entities.process.current')">
      </el-table-column>
    </data-tables>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '@/event-bus.js';

  import PageUtils from '@mixins/page/utils.js';
  import TableUtils from '@mixins/table/utils.js';

  let namespace = 'learningProducts';

  export default {
    name: 'learning-product-list',

    mixins: [ PageUtils, TableUtils ],

    data() {
      return {
        normalizedList: [],
        normalizedListAttrs: ['id', 'name', 'type', 'sub_type', 'organizational_unit.name', 'updated_at', 'state.name', 'current_process']
      }
    },

    computed: {
      ...mapGetters({
        language: 'language',
        hasRole: 'users/hasRole',
        learningProducts: `${namespace}/all`
      }),

      orgUnitFilters() {
        return this.getColumnFilters(this.normalizedList, 'organizational_unit')
                   .sort((a, b) => this.$helpers.localeSort(a, b, 'text'));
      },

      typeFilters() {
        return this.getColumnFilters(this.normalizedList, 'type')
                   .sort((a, b) => this.$helpers.localeSort(a, b, 'text'));
      }
    },

    methods: {
      ...mapActions({
        loadLearningProducts: `${namespace}/loadLearningProducts`
      }),

      viewLearningProduct(learningProduct) {
        this.scrollToTop();
        this.$router.push(`learning-products/${learningProduct.id}`);
      },

      onHeaderClick(col, e) {
        this.headerClick(col, e);
      },

      scrollToTop() {
        document.querySelectorAll('.el-main')[0].scrollTop = 0;
        // IE11 scroll to top
        document.querySelectorAll('.content-wrap')[0].scrollTop = 0
      },

      async triggerLoadLearningProducts() {
        try {
          await this.loadLearningProducts();
          this.normalizedList = _.map(this.learningProducts, learningProduct => {
            let normLearningProduct = _.pick(learningProduct, this.normalizedListAttrs);
            normLearningProduct.organizational_unit = normLearningProduct.organizational_unit.name;
            normLearningProduct.state = normLearningProduct.state.name;
            normLearningProduct.type = this.$options.filters.learningProductTypeSubTypeFilter(normLearningProduct.type.name, normLearningProduct.sub_type.name);
            // @todo: change to real property instead
            normLearningProduct.current_process = normLearningProduct.current_process && normLearningProduct.current_process.definition ? normLearningProduct.current_process.definition.name : this.trans('entities.general.na');
            return normLearningProduct;
          });
        } catch (e) {
          // Exception handled by interceptor
          if (!e.response) {
            throw e;
          }
        }
      },

      async onLanguageUpdate() {
        await this.triggerLoadLearningProducts();
      }
    },

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      this.onLanguageUpdate();
      next();
    },

    mounted() {
      EventBus.$emit('App:ready');
      this.triggerLoadLearningProducts();
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  .learning-product-list {
    .el-table__row {
      cursor: pointer;
    }

    .el-tag {
      height: auto;
      white-space: normal;
      margin-right: 4px;
      margin-top: 2px;
      margin-bottom: 2px;
    }
    .controls {
      margin-bottom: 0;
    }
  }
</style>
