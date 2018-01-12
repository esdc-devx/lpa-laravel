import './bootstrap';

// Import libs
import Vue from "vue";
import VueI18n from 'vue-i18n';
import { sync } from 'vuex-router-sync';

import App from "./app.vue";

import { LPA_CONFIG } from './config.js';

// Import Element UI
import ElementUI from 'element-ui';
// Element UI Localization
import elementUILocaleEN from 'element-ui/lib/locale/lang/en';
import elementUILocaleFR from 'element-ui/lib/locale/lang/fr';

import router from "./router";
import store from './store/';

import { loadLanguages } from './locale';

sync(store, router);

loadLanguages().then(data => {
  data.en = data.en || {};
  data.fr = data.fr || {};
  window.i18n = new VueI18n({
    locale: store.getters.getLanguage,
    messages: {
      en: Object.assign(data.en, elementUILocaleEN),
      fr: Object.assign(data.fr, elementUILocaleFR)
    }
  });

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
