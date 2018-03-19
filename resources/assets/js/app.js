import Vue from 'vue';
import VueI18n from 'vue-i18n';

import VeeValidate from 'vee-validate';
import veeLocaleFR from 'vee-validate/dist/locale/fr';

import ElementUI from 'element-ui';
import elementUILocaleEN from 'element-ui/lib/locale/lang/en';
import elementUILocaleFR from 'element-ui/lib/locale/lang/fr';

import { sync } from 'vuex-router-sync';
import router from './router';
import store from './store/';

import Logger from './logger';
import Notify from './mixins/notify.js';

import { setLanguage, loadLanguages } from './locale';

import App from './app.vue';
import Config from './config';

sync(store, router);

Vue.use(Logger, {
  logLevel          : Config.debug ? 'debug' : 'error',
  separator         : '',
  stringifyArguments: false,
  showLogLevel      : false,
  showMethodName    : true,
  showConsoleColors : true
});

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
        events: 'input',
        strict: false,
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
      Notify.notifyError('Languages could not be loaded. Please contact your administrator.');
      Vue.$log.error(`[app][loadLanguages]: ${e}`);
    })
  ).catch(e => {
    Notify.notifyError('Language could not be set. Please contact your administrator.');
    Vue.$log.error(`[app][setLanguage]: ${e}`);
  });
