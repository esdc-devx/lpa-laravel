import Vue from 'vue';
import VueI18n from 'vue-i18n';

import VeeValidate from 'vee-validate';
import veeLocaleFR from 'vee-validate/dist/locale/fr';

import ElementUI from 'element-ui';
import elementUILocaleEN from 'element-ui/lib/locale/lang/en';
import elementUILocaleFR from 'element-ui/lib/locale/lang/fr';

import VueTheMask from 'vue-the-mask';

import DataTables from 'vue-data-tables'

import { sync } from 'vuex-router-sync';
import router from '@/router';
import store from '@/store/';

import Logger from '@/plugins/logger';
import Helpers from '@/helpers';
import '@/polyfills';
import '@/filters';
import '@/directives';
import Notify from '@mixins/notify';
import Confirm from '@mixins/confirm';

import { setLanguage, loadLanguages } from '@/locale';

import App from '@/app.vue';
import Config from '@/config';

Vue.use(VueTheMask);

Vue.use(DataTables);

Vue.use(Helpers);

sync(store, router);

Vue.use(Logger, {
  logLevel          : Config.DEBUG ? 'debug' : 'error',
  separator         : '',
  stringifyArguments: false,
  showLogLevel      : false,
  showMethodName    : true,
  showConsoleColors : true
});

Vue.mixin({ methods: Notify });
Vue.mixin({ methods: Confirm });

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

      // Override the default placeholder for the components that has one
      ElementUI.Select.props.placeholder.default = '';
      ElementUI.Cascader.props.placeholder.default = '';
      Vue.use(ElementUI, {
        i18n: (key, value) => window.i18n.t(key, value)
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
        },
        // Gives us the ability to inject validation in child components
        // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
        inject: false
      });

      new Vue({
        el: 'app',
        router,
        store,
        i18n,
        template: '<app/>',
        components: { App }
      });
    }).catch(e => {
      alert('Languages could not be loaded. Please contact your administrator.');
      Vue.$log.error(`[app][loadLanguages]: ${e}`);
    })
  ).catch(e => {
    alert('Language could not be set. Please contact your administrator.');
    Vue.$log.error(`[app][setLanguage]: ${e}`);
  });
