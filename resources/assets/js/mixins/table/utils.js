export default {
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

    onSort(a, b) {
      // sort strings
      if (_.isString(a) && _.isString(b)) {
        return a.toLowerCase().localeCompare(b.toLowerCase(), this.language);
      }
      // sort numbers
      return a - b;
    }
  }
};
