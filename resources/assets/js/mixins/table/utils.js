import { mapActions } from 'vuex';

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
     * @param Object filters - column's filters
     */
    onFilterChange(filters) {
      // store the current filter changed
      // since _.keys and _.values return an array and that we are only dealing with 1 applied filter at a time,
      // just take the first and only one index
      let filter = _.values(filters)[0];
      if (filter.length) {
        // let the store know that it should make the user confirm before leaving the page
        this.confirmBeforeLanguageChange(true);
        // apply filter
        this.filters[_.keys(filters)[0]] = _.values(filters)[0];
      } else {
        // filter removed
        delete this.filters[_.keys(filters)[0]];
      }

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
    beforeLanguageUpdate(updateLanguage) {
      // if no filters are set, just proceed updating the language
      if (!_.values(this.filters).length) {
        updateLanguage();
        return;
      }

      this.$confirm(
        this.trans('components.notice.message.language_toggle'),
        this.trans('components.notice.type.warning'),
        {
          type: 'warning',
          confirmButtonText: this.trans('base.actions.continue'),
          confirmButtonClass: 'el-button--warning',
          cancelButtonText: this.trans('base.actions.cancel'),
          dangerouslyUseHTMLString: true
        }
      )
      .then(() => {
        // reset the confirmation flag
        this.confirmBeforeLanguageChange(false);
        // reset the sorting and filtering
        this.filters = {};
        this.customFilters = [];
        this.$refs.table.$refs.elTable.clearFilter();
        updateLanguage();
      })
      .catch(() => false);
    }
  },

  beforeRouteLeave(to, from, next) {
    // Destroy any events we might be listening
    // so that they do not get called while being on another page
    EventBus.$off('TopBar:beforeLanguageUpdate', this.beforeLanguageUpdate);
    next();
  },

  mounted() {
    // fix pagination styling since vue-data-tables doesn't support  passing 'background as property
    this.$refs.table.$el.querySelector('.el-pagination').classList.add('is-background');

    EventBus.$on('TopBar:beforeLanguageUpdate', this.beforeLanguageUpdate);
  }
};
