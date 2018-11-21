<template>
  <data-tables
    ref="table"
    :search-def="{show: false}"
    :custom-filters="customFilters"
    :pagination-def="paginationDef"
    :data="parsedData"
    @filter-change="onFilterChange"
    @current-page-change="scrollToTop"
    @row-click="onRowClick"
    @header-click="headerClick"
    :sort-method="$helpers.localeSort">
    <el-table-column
      v-for="(attr, index) in columns"
      :key="index"
      :label="labels[index]"
      :prop="attr"
      :column-key="attr"
      :filters="getFilters(attr)"
      :min-width="minWidths[index]"
      sortable="custom"
    >
      <template slot-scope="scope">
        <span v-if="attr === 'id'">{{ scope.row.id | LPANumFilter }}</span>
        <template v-else-if="isArray(scope.row[attr])">
          <el-tag
            v-for="item in scope.row[attr]"
            :key="item.id"
            type="info"
            size="small"
            :title="item"
            disable-transitions
          >
            {{item}}
          </el-tag>
        </template>
        <template v-else-if="attr === 'created_at'">
          <span>{{ scope.row.created_at }}</span>
          <template v-if="scope.row.created_by">
            <br>
            <span>{{ scope.row.created_by }}</span>
          </template>
        </template>
        <template v-else-if="attr === 'updated_at'">
          <span>{{ scope.row.updated_at }}</span>
          <template v-if="scope.row.updated_by">
            <br>
            <span>{{ scope.row.updated_by }}</span>
          </template>
        </template>
        <span v-else>{{ scope.row[attr] }}</span>
      </template>
    </el-table-column>
  </data-tables>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions, mapMutations } from 'vuex';

  import Constants from '@/constants.js';
  import EventBus from '@/event-bus.js';

  export default {
    name: 'entity-data-table',

    props: {
      data: {
        type: Array,
        required: true
      },
      attributes: {
        type: Object,
        required: true
      },
      entityType: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        filters: {},
        // used in order to sync the pagination with the filters
        customFilters: [],
        paginationDef: {
          layout: 'total, prev, pager, next, sizes',
          // @todo: ideally get values from localstorage
          pageSize: Constants.PAGE_SIZE_DEFAULT,
          pageSizes: Constants.PAGE_SIZES,
          currentPage: 1
        }
      }
    },

    computed: {
      ...mapGetters([
        'language'
      ]),

      /**
       * Gets all the root keys of the attributes as props
       * @return { Array }
       */
      props() {
        return _.keys(this.attributes);
      },

      /**
       * Gets all the objects that are not set isColumn: false
       * @return { Array }
       */
      columns() {
        let columns = _.keys(_.pickBy(this.attributes, (val, key) => {
          if (val.isColumn === false) {
            return false;
          }
          return true;
        }));
        if (!columns.length) {
          this.$log.error('Columns are required to render the data-table. Make sure you specified which attributes are columns using `isColumn` property.')
        }
        return columns;
      },

      /**
       * Gets all the labels properties from the attributes objects
       * @return { Array }
       */
      labels() {
        return _.compact(_.map(this.attributes, 'label'));
      },

      /**
       * Gets all the minWidth properties from the attributes objects
       * @return { Array }
       */
      minWidths() {
        return _.compact(_.map(this.attributes, 'minWidth'));
      },

      /**
       * Used in order to normalize the props of an entity
       * so that we only get what we need on rendering.
       * E.g.: entity.organizational_unit.name -> entity.organizational_unit
       *       entity.organizational_units[x].name -> entity.organizational_units[x]
       *       current_process.definition.name -> current_process
       */
      parsedData() {
        let entityProps = _.cloneDeep(this.props);
        return _.map(_.cloneDeep(this.data), entity => {
          // equivalent of _.pick, but keeps the property if it is found in the attributes
          // but is undefined / null
          let normEntity = _.pickBy(entity, (val, key) => {
            // if key is found in desired column names
            if (entityProps.includes(key)) {
              return true;
            } else {
              // else, loop through every desired entity props
              // and check for possibilities like
              // current_process.definition
              // since we only want current_process to show up in the column name
              // we cannot put current_process.definition in the props
              // hence why we need to check if the column name
              // current_process is found in current_process.definition string
              for (const attr of entityProps) {
                if (attr.indexOf(key) !== -1) {
                  return true;
                }
              }
            }
          });
          // formatting
          this.$emit('formatData', normEntity);

          // sort the props so that we have an array properly indexed
          // to reference later on
          entityProps.sort();
          // sort here as well to make correlation possible with the column names
          let normEntityKeysSorted = _.keys(normEntity).sort();
          for (let i = 0; i < normEntityKeysSorted.length; i++) {
            let key = normEntityKeysSorted[i];
            let column = _.get(normEntity, key);
            // check if we need to return the name of the path to the value
            // given in the entity props

            if (_.isArray(column)) {
              normEntity[key] = _.map(normEntity[entityProps[i]], 'name');
            } else if (_.isObject(_.get(normEntity, key))) {
              normEntity[key] = _.get(normEntity, entityProps[i]).name;
            } else if (_.isNull(normEntity[key])) {
              // since we dcannot filter on null values
              // we need to re-assign the value to '-' so that we can filter on something
              normEntity[key] = this.trans('entities.general.none');
            }
          }
          return normEntity;
        });
      }
    },

    methods: {
      ...mapActions([
        'confirmBeforeLanguageChange'
      ]),

      ...mapMutations([
        'addFilteredDataTable',
        'deleteFilteredDataTable'
      ]),

      /**
       * Scroll the current page up. Called when navigating from one table page to the other.
       */
      scrollToTop() {
        document.querySelectorAll('.el-main')[0].scrollTop = 0;
        // IE11 scroll to top
        document.querySelectorAll('.content-wrap')[0].scrollTop = 0
      },

      // handle click on sortable and filterable columns
      // since ElementUI has no behavior when clicking a column that has both methods
      headerClick(col, e) {
        if (!this.$refs.table) {
          this.$log.warn('[Mixin][Table][utils] Missing ref="table" reference on the table element.');
          return;
        }
        if (col.sortable && !_.isUndefined(col.filters)) {
          this.$refs.table.$refs.elTable.$refs.tableHeader.handleSortClick(e, col);
        }
      },

      /**
       * Grabs the attrs values,
       * put them in an array, remove dupplicates
       * then rearrange its format to match ElementUI's
       * and format it so that we do not show '-' as options
       * @note: flatMapDeep is mainly used in user-list
       *        since we may have multiple organizational units associated to a user
       */
      getColumnFilters(list, attr, isSorted) {
        let filters = _.chain(list)
                .mapValues(attr)
                .toArray().flatMapDeep().uniq()
                .map((val, key) => {
                  let text = val === this.trans('entities.general.none') ? this.trans('entities.general.na') : val;
                  return { text, value: val };
                })
                .value();
        return isSorted ? filters.sort((a, b) => this.$helpers.localeSort(a, b, 'text')) : filters;
      },

      /**
       * Called upon changing the filters on a column
       * @param Object columFilters - column's filters
       */
      onFilterChange(columFilters) {
        // store the current filter changed
        // since _.keys and _.values return an array and that we are only dealing with 1 applied filter at a time,
        // just take the first and only one index
        let filter = _.values(columFilters)[0];
        if (filter.length) {
          // apply filter
          this.filters[_.keys(columFilters)[0]] = _.values(columFilters)[0];
        } else {
          // filter removed
          delete this.filters[_.keys(columFilters)[0]];
        }

        // If there are active filters, make sure to prompt the user before changing the page language.
        this[_.isEmpty(this.filters) ? 'deleteFilteredDataTable' : 'addFilteredDataTable'](this.$options.name);

        // reset custom filters so that we can rebuild them
        this.customFilters = [];
        // loop through all the applied filters, and build the customFilters
        for (let i = 0; i < _.keys(this.filters).length; i++) {
          // make sure to make the customFilter reactive
          this.$set(this.customFilters, i, {});
          this.$set(this.customFilters[i], 'vals', _.values(this.filters)[i]);
        }
      },

      /**
       * Called when toggling language in order to reset the filters on confirmation
       */
      resetFilters() {
        // if no filters are set, just proceed updating the language
        if (!_.isEmpty(this.filters)) {
          this.filters = {};
          this.customFilters = [];
          this.$refs.table.$refs.elTable.clearFilter();
          this.deleteFilteredDataTable(this.$options.name);
        }
      },

      isArray(rowValue) {
        return _.isArray(rowValue);
      },

      getFilters(attr) {
        let filters = [];
        // this allows us to parse attributes passed in like:
        // current_process.definition
        let parsedAttr = attr.indexOf('.') ? attr.split('.')[0] : attr;
        if (this.attributes[attr].isFilterable) {
          return this.getColumnFilters(
            this.parsedData,
            parsedAttr,
            this.attributes[attr].areFiltersSorted
          );
        }
        return filters;
      },

      onRowClick(entity) {
        this.$emit('rowClick', entity);
      }
    },

    mounted() {
      // fix pagination styling since vue-data-tables doesn't support  passing 'background as property
      this.$refs.table.$el.querySelector('.el-pagination').classList.add('is-background');
      EventBus.$on('TopBar:ResetDataTableFilters', this.resetFilters);
    },

    beforeDestroy() {
      EventBus.$off('TopBar:ResetDataTableFilters', this.resetFilters);
      this.deleteFilteredDataTable(this.$options.name);
    }
  }
</script>

<style lang="scss">
</style>
