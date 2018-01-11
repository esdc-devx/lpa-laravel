import Vue from "vue";
import VueI18n from "vue-i18n";
import store from './store/';

Vue.use(VueI18n);

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
