import _ from 'lodash';
import Vue from "vue";
import VueI18n from "vue-i18n";
import store from './store/';

Vue.use(VueI18n);

/**
 * Used as an interpretor for
 * Laravel named formatting locale messages
 * @param  {String} string - The Laravel locale string to interprete
 * @param  {Object} args - Contains the values to map in the string
 * @return {String}        Return the formatted string
 */
Vue.prototype.trans = (string, args) => {
  let value = _.get(window.i18n.messages[window.i18n.locale], string);

  _.eachRight(args, (paramVal, paramKey) => {
      value = _.replace(value, `:${paramKey}`, paramVal);
  });
  return value;
};

export function loadLanguages() {
  return new Promise((resolve, reject) => {
    store.dispatch('loadLanguages')
      .then(data => {
        resolve(data);
      })
      .catch(e => {
        reject(e);
      });
  });
}
