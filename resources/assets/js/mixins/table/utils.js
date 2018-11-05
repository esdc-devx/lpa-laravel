import { mapActions, mapMutations } from 'vuex';

import Constants from '@/constants.js';
import EventBus from '@/event-bus.js';

export default {

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

    /**
     * Sort table header filter options in alphabetical order.
     * @param {Array} data 
     * @param {String} column 
     */
    sortFilterEntries(data, column) {
      return this.getColumnFilters(data, column)
                 .sort((a, b) => this.$helpers.localeSort(a, b, 'text'));
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
};
