import _ from 'lodash';
import Vue from 'vue';
import Constants from './constants';

Vue.filter('LPANumFilter', function (id) {
  return _.padStart(id, Constants.LPA_NUM_PAD, 0);
});

Vue.mixin({
  methods: {
    getColumnFilters(list, attr) {
      return _.chain(list)
              .mapValues(attr)
              .toArray().uniq()
              .map((val, key) => { return { text: val, value: val } })
              .value();
    }
  }
})
