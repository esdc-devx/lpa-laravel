import Constants from '../../constants.js';
import EventBus from '../../event-bus.js';

export default {
  data() {
    return {
      filters: [],
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
    // handle click on sortable and filterable columns
    // since ElementUI has no behavior when clicking a column that has both methods
    headerClick(col, e) {
      if (!this.$refs.table) {
        this.$log.warn('[Mixin][Table][utils] Missing ref="table" reference on the table element.');
        return;
      }
      if (col.sortable && !_.isUndefined(col.filterable)) {
        this.$refs.table.$refs.elTable.$refs.tableHeader.handleSortClick(e, col);
      }
    },

    onFilterChange(filters) {
      // store the current filter changed
      // since _.keys and _.values return an array and that we are only dealing with 1 applied filter at a time,
      // just take the first and only one index
      this.filters[_.keys(filters)[0]] = _.values(filters)[0];

      // loop through all the applied filters, and build the customFilters
      for (let i = 0; i < _.values(this.filters).length; i++) {
        // make sure to make the customFilter reactive
        this.$set(this.customFilters, i, {});
        this.$set(this.customFilters[i], 'vals', _.values(this.filters)[i]);
      }
    },

    /**
     * Sorting method called when a column header is clicked to compare each rows and sort them accordingly
     */
    onSort(a, b) {
      // See: https://stackoverflow.com/questions/19993639/difference-in-performance-between-calling-localecompare-on-string-objects-and-c
      let aName = String(a).toLowerCase();
      let bName = String(b).toLowerCase();
      // Flawless, but not localized
      // if (aName < bName)
      //     return -1;
      //   if (aName == bName)
      //     return 0;
      //   else
      //     return 1;

      // Perf issues...
      // let flag = aName - bName;
      // return _.isNaN(flag) ? aName.localeCompare(bName, this.language) : flag;

      // Perf issues but a bit faster than localeCompare...
      let collator = new Intl.Collator(this.language);
      let flag = aName - bName;
      return _.isNaN(flag) ? collator.compare(aName, bName) : flag;
    },

    /**
     * Called when toggling language in order to reset the filters on confirmation
     */
    beforeLanguageUpdate(updateLanguage) {
      // if no filters are set, just proceed updating the language
      if (!this.filters.length) {
        updateLanguage();
        return;
      }

      this.$confirm(
        this.trans('components.notice.language_toggle'),
        this.trans('components.notice.warning'),
        {
          type: 'warning',
          confirmButtonText: this.trans('base.actions.continue'),
          confirmButtonClass: 'el-button--warning',
          cancelButtonText: this.trans('base.actions.cancel'),
          dangerouslyUseHTMLString: true
        }
      )
      .then(() => {
        // reset the sorting and filtering
        this.customFilters = [{
          vals: []
        }];
        this.$refs.table.$refs.elTable.clearFilter();
        updateLanguage();
      })
      .catch(() => false);
    }
  },

  mounted() {
    EventBus.$on('TopBar:beforeLanguageUpdate', this.beforeLanguageUpdate);
  }
};
