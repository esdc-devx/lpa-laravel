import _ from 'lodash';
import Vue from 'vue';
import VueI18n from 'vue-i18n';
import store from '@/store/';

Vue.use(VueI18n);

/**
 * Used as an interpretor for
 * Laravel named formatting locale messages that understands :attribute in strings
 * @param  {String} string - The Laravel locale string to interprete
 * @param  {Object} args - Contains the values to map in the string
 * @return {String}        Return the formatted string
 */
Vue.prototype.trans = (string, args) => {
  string = string.replace(/-/g, '_'); // Standardized resource path to snake-case while keeping the dot separators between tokens.
  let value = _.get(window.i18n.messages[window.i18n.locale], string, string);

  if (string === value) {
    Vue.$log.warn(`Could not find translatable string for: ${string}`);
  }

  _.eachRight(args, (paramVal, paramKey) => {
    value = _.replace(value, `:${paramKey}`, paramVal);
  });
  return value;
};

export async function setLanguage() {
  try {
    return await store.commit('setLanguage');
  } catch (e) {
    throw e;
  }
};

export async function loadLanguages() {
  try {
    return await store.dispatch('loadLanguages');
  } catch (e) {
    throw e;
  }
};
