// Import libs
import Vue from 'vue';
import VueI18n from 'vue-i18n';
import VeeValidate from 'vee-validate';
import veeLocaleFR from 'vee-validate/dist/locale/fr';
import { sync } from 'vuex-router-sync';

import App from './app.vue';

import Notify from './mixins/notify.js';

// Import Element UI
import ElementUI from 'element-ui';
// Element UI Localization
import elementUILocaleEN from 'element-ui/lib/locale/lang/en';
import elementUILocaleFR from 'element-ui/lib/locale/lang/fr';

import { setLanguage, loadLanguages } from './locale';

import router from './router';
import store from './store/';

sync(store, router);

Vue.mixin({ methods: Notify });

setLanguage()
  .then(loadLanguages()
    .then(data => {
      data.en = data.en || {};
      data.fr = data.fr || {};
      window.i18n = new VueI18n({
        locale: store.getters.language,
        messages: {
          en: Object.assign(data.en, elementUILocaleEN),
          fr: Object.assign(data.fr, elementUILocaleFR)
        }
      });

      Vue.use(ElementUI, {
        i18n: (key, value) => i18n.t(key, value)
      });

      Vue.use(VeeValidate, {
        events: 'blur',
        locale: store.getters.language,
        // modify the defaults for errors and fields
        // since ElementUI already has these properties injected
        errorBagName: 'verrors',
        fieldsBagName: 'vfields',
        dictionary: {
          fr: veeLocaleFR
        }
      });

      new Vue({
        el: 'app',
        router,
        store,
        i18n,
        template: '<app/>',
        components: { App }
      });
    })
    .catch(e => {
      Notify.notifyError('[app][loadLanguages]: ' + e);
      console.error('[app][loadLanguages]: ' + e);
    })
  ).catch(e => {
    Notify.notifyError('[app][setLanguage]: ' + e);
    console.error('[app][setLanguage]: ' + e);
  });
