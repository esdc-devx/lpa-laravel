// Adds the promise polyfill for IE 11
if (typeof Promise === 'undefined') {
  require('es6-promise/auto');
}

import Vue from 'vue';
import axios from 'axios';

import VeeValidate from 'vee-validate';
import veeLocaleFR from 'vee-validate/dist/locale/fr';

import ElementUI from 'element-ui';
import elementUILocaleEN from 'element-ui/lib/locale/lang/en';
import elementUILocaleFR from 'element-ui/lib/locale/lang/fr';

import Logger from '@/plugins/logger';
import Notify from '@mixins/notify';
import '@/locale';

import Config from '@/config';

import FormError from '@components/forms/error';
import FormUtils from '@mixins/form/utils';

import HttpStatusCodes from '@axios/http-status-codes';

const elementUILocale = Config.DEFAULT_LANG === 'en' ? elementUILocaleEN : elementUILocaleFR;
Vue.use(ElementUI, { locale: elementUILocale });

Vue.mixin({ methods: Notify });

Vue.use(Logger, {
  logLevel          : Config.DEBUG ? 'debug' : 'error',
  separator         : '',
  stringifyArguments: false,
  showLogLevel      : false,
  showMethodName    : true,
  showConsoleColors : true
});

Vue.use(VeeValidate, {
  events: 'input',
  strict: false,
  locale: Config.DEFAULT_LANG,
  // modify the defaults for errors and fields
  // since ElementUI already has these properties injected
  errorBagName: 'verrors',
  fieldsBagName: 'vfields',
  dictionary: {
    fr: veeLocaleFR
  }
});

new Vue({
  el: '#app',

  mixins: [ FormUtils ],

  components: { FormError },

  data() {
    return {
      username: '',
      password: '',
      remember: false,
      isPasswordVisible: false,
      toggledLocale: Config.DEFAULT_LANG === 'en' ? 'fr' : 'en'
    };
  },

  methods: {
    // manage basic validation
    onSubmit() {
      this.submit(this.login);
    },

    // attempt to login if basic validation succeed
    async login() {
      let request = axios.create({baseURL: '/' + Config.DEFAULT_LANG});
      let response;
      try {
        response = await request.post('login', {
          username: this.username,
          password: this.password,
          remember: this.remember
        });
        // all good, submit form manually
        window.location = response.data.redirectURL;
      } catch({ response }) {
        // catch in case of token mismatch, invalid session, etc due to cache cleared by user
        if (response.status === HttpStatusCodes.BAD_REQUEST) {
          this.notifyError({
            message: Vue.prototype.trans('errors.bad_request')
          });
          return;
        } else if (response.status === HttpStatusCodes.SERVER_ERROR) {
          this.notifyError({
            message: Vue.prototype.trans('errors.server_error')
          });
          return;
        }
        this.isSubmitting = false;
        this.manageBackendErrors(response.data.errors);
      }
    },

    async loadLanguages() {
      let request = axios.create({baseURL: '/' + Config.DEFAULT_LANG});
      let response;
      response = await request.get('api/locales');
      window.i18n.messages = response.data.data;
    },

    setupLocale() {
      window.i18n = {};
      window.i18n.locale = Config.DEFAULT_LANG;
    },

    manageBackendErrors(errors) {
      let fieldBag;
      for (let fieldName in errors) {
        fieldBag = errors[fieldName];
        for (let j = 0; j < fieldBag.length; j++) {
          this.verrors.add({field: fieldName, msg: fieldBag[j]})
        }
      }
      this.focusOnError();
    }
  },

  async mounted() {
    this.setupLocale();
    await this.loadLanguages();
    let spinner = document.querySelectorAll('.loading-spinner')[0];
    if (spinner) {
      spinner.classList.add('fade-out');
    }
  }
});
