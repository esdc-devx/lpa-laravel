import _ from 'lodash';
import Vue from 'vue';
import VueI18n from 'vue-i18n';
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
  let value = _.get(window.i18n.messages[window.i18n.locale], string, '');

  _.eachRight(args, (paramVal, paramKey) => {
    value = _.replace(value, `:${paramKey}`, paramVal);
  });
  return value;
};

export async function setLanguage() {
  try {
    await store.dispatch('setLanguage');
    return;
  } catch(e) {
    return e;
  }
};

export async function loadLanguages() {
  let response;
  try {
    response = await store.dispatch('loadLanguages');
    return response;
  } catch(e) {
    return e;
  }
};
