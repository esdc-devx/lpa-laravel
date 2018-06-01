import _ from 'lodash';
import store from './store/';

import Config from './config.js';

const helpers = {
  throttleAction: _.throttle(callback => {
    callback();
  }, Config.THROTTLE_WAIT_TIME),

  /**
   * Sorting method called when a column header is clicked to compare each rows and sort them accordingly
   * See: https://stackoverflow.com/questions/19993639/difference-in-performance-between-calling-localecompare-on-string-objects-and-c
   */
  localeSort(a, b, attr = null) {
    // if we are dealing with an object, we want to sort by a certain attribute
    if (_.isObject(a) && _.isObject(b) && !_.isNull(attr)) {
      let collator = new Intl.Collator(store.getters.language);
      let flag = a[attr] - b[attr];
      return _.isNaN(flag) ? collator.compare(a[attr], b[attr]) : flag;
    } else {
      let aName = String(a).toLowerCase();
      let bName = String(b).toLowerCase();

      // Flawless, but not localized
      // return aName > bName ? 1 : aName < bName ? -1 : 0

      // Perf issues...
      // let flag = aName - bName;
      // return _.isNaN(flag) ? aName.localeCompare(bName, this.language) : flag;

      // Perf issues but a bit faster than localeCompare...
      let collator = new Intl.Collator(store.getters.language);
      let flag = aName - bName;
      return _.isNaN(flag) ? collator.compare(aName, bName) : flag;
    }
  },
};

const HelpersPlugin = {
  install(Vue, options) {
    Vue.helpers = helpers;
    Vue.prototype.$helpers = helpers;
  }
};

export default HelpersPlugin;
