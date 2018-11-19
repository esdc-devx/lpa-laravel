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
        <span v-else-if="!scope.row[attr]">{{ trans('entities.general.na') }}</span>
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
  import { mapGetters, mapActions } from 'vuex';

  import TableUtils from '@mixins/table/utils.js';

  export default {
    name: 'entity-data-table',

    mixins: [ TableUtils ],

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
          throw new Error('Columns are required to render the data-table. Make sure you specified which attributes are columns using `isColumn` property.')
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
            }
          }
          return normEntity;
        });
      }
    },

    methods: {
      isArray(rowValue) {
        return _.isArray(rowValue);
      },

      getFilters(attr) {
        let filters = [];
        if (!this.attributes[attr].isFilterable) {
          return filters;
        } else if (this.attributes[attr].areFiltersSorted) {
          filters = this.sortFilterEntries(this.parsedData, attr);
        } else {
          filters = this.getColumnFilters(this.parsedData, attr);
        }

        // if there is only one filter, don't show the filter arrow
        if (filters.length === 1) {
          return [];
        }
        return filters;
      },

      onRowClick(entity) {
        this.$emit('rowClick', entity);
      }
    }
  }
</script>

<style lang="scss">
</style>
