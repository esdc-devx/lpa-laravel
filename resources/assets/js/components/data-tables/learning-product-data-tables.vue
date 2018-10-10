<template>
  <div>
    <data-tables 
      class="learning-product-data-tables"
      ref="table"
      :search-def="{show: false}"
      :custom-filters="customFilters"
      :pagination-def="paginationDef"
      :data="learningProducts"
      @filter-change="onFilterChange"
      @current-page-change="scrollToTop"
      @row-click="viewLearningProduct"
      @header-click="headerClick"
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
        :filters="stateFilters"
        prop="state"
        min-width="14"
        :label="trans('entities.general.status')">
      </el-table-column>
      <el-table-column
        sortable="custom"
        column-key="current_process"
        :filters="currentProcessFilters"
        prop="current_process"
        min-width="21"
        :label="trans('entities.process.current')">
      </el-table-column>
    </data-tables>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import _ from 'lodash';
  import TableUtils from '@mixins/table/utils.js';

  export default {
    name: 'learning-product-data-tables',

    mixins: [ TableUtils ],

    props: {
      data: {
        type: Array,
        required: true,
        default: () => []
      }
    },

    data() {
      return {
        learningProductAttributes: [
          'id', 'name', 'type', 'sub_type', 'organizational_unit.name', 'updated_at', 'state.name', 'current_process'
        ]
      };
    },

    computed: {

      ...mapGetters({
          language: 'language'
      }),

      learningProducts() { 
        return _.map(this.data, learningProduct => {
          let normLearningProduct = _.pick(learningProduct, this.learningProductAttributes);
          normLearningProduct.organizational_unit = normLearningProduct.organizational_unit.name;
          normLearningProduct.state = normLearningProduct.state.name;
          normLearningProduct.type = this.$options.filters.learningProductTypeSubTypeFilter(normLearningProduct.type.name, normLearningProduct.sub_type.name);
          // @todo: change to real property instead
          normLearningProduct.current_process = normLearningProduct.current_process && normLearningProduct.current_process.definition ? normLearningProduct.current_process.definition.name : this.trans('entities.general.na');
          return normLearningProduct;
        });
      },

      orgUnitFilters() {
        return this.sortFilterEntries(this.learningProducts, 'organizational_unit');
      },

      typeFilters() {
        return this.sortFilterEntries(this.learningProducts, 'type');
      },

      stateFilters() {
        return this.sortFilterEntries(this.learningProducts, 'state');
      },

      currentProcessFilters() {
        return this.sortFilterEntries(this.learningProducts, 'current_process');
      }
    },

    methods: {

      viewLearningProduct(learningProduct) {
        this.$router.push(`/${this.language}/learning-products/${learningProduct.id}`);
      }
    }
  }
</script>

<style lang="scss">
</style>
