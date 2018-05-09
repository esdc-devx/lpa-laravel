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
      // See: https://stackoverflow.com/questions/19993639/difference-in-performance-between-calling-localecompare-on-string-objects-and-c
      let aName = a.toLowerCase();
      let bName = b.toLowerCase();
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
    }
  }
};
