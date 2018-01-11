import './bootstrap';

import Vue from "vue";
import VueI18n from 'vue-i18n';
import App from "./app.vue";
import { LPA_CONFIG } from './config.js';

// Import Element UI
import ElementUI from 'element-ui';
// Element UI Localization
import elementUILocaleEN from 'element-ui/lib/locale/lang/en';
import elementUILocaleFR from 'element-ui/lib/locale/lang/fr';

import router from "./router";
import store from './store/';
import { sync } from 'vuex-router-sync';

import { loadLanguages } from './locale';

Vue.prototype.trans = (string, args) => {
  let value = _.get(window.i18n, string);

  _.eachRight(args, (paramVal, paramKey) => {
      value = _.replace(value, `:${paramKey}`, paramVal);
  });
  return value;
};

sync(store, router);

loadLanguages().then(data => {
  let i18n = new VueI18n({
    locale: store.getters.getLanguage,
    messages: {
      en: Object.assign(data.en, elementUILocaleEN),
      fr: Object.assign(data.fr, elementUILocaleFR)
    }
  });

  // let elementUILang = store.getters.getLanguage === 'en' ? elementUILocaleEN : elementUILocaleFR;
  // Vue.use(ElementUI, { elementUILang });
  Vue.use(ElementUI, {
    i18n: (key, value) => i18n.t(key, value)
  });


  new Vue({
    el: "app",
    router,
    store,
    i18n,
    template: "<app/>",
    components: { App }
  });
});
