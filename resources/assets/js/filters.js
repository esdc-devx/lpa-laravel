import _ from 'lodash';
import Vue from 'vue';
import Constants from '@/constants';

Vue.filter('LPANumFilter', function (id) {
  return _.padStart(id, Constants.LPA_NUM_PAD, 0);
});

Vue.filter('learningProductTypeSubTypeFilter', function (type, subType) {
  return `${type} / ${subType}`;
});
