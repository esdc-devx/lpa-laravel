import _ from 'lodash';
import Vue from 'vue';
import Constants from '@/constants';

Vue.filter('LPANumFilter', function (id) {
  return _.padStart(id, Constants.LPA_NUM_PAD, 0);
});

Vue.filter('learningProductTypeSubTypeFilter', function (type, subType) {
  return `${type} / ${subType}`;
})

Vue.mixin({
  methods: {
    getColumnFilters(list, attr) {
      // grab the attrs values,
      // put them in an array, remove dupplicates, remove falsy values,
      // and then rearrange its format to match ElementUI's
      // @note: flatMapDeep is mainly used in user-list
      // since we may have multiple organizational units associated to a user
      return _.chain(list)
              .mapValues(attr)
              .toArray().flatMapDeep().uniq().compact()
              .map((val, key) => { return { text: val, value: val } })
              .value();
    }
  }
})
