import Vue from 'vue';
import axios from 'axios';
import axiosDefaults from './axios/defaults';

import VeeValidate from 'vee-validate';
import veeLocaleFR from 'vee-validate/dist/locale/fr';

import ElementUI from 'element-ui';
import elementUILocaleEN from 'element-ui/lib/locale/lang/en';
import elementUILocaleFR from 'element-ui/lib/locale/lang/fr';

import Logger from './logger';
import Notify from './mixins/notify';

import Config from './config';

import FormError from './components/forms/error';
import FormUtils from './mixins/form/utils';

const elementUILocale = Config.DEFAULT_LANG === 'en' ? elementUILocaleEN : elementUILocaleFR;
Vue.use(ElementUI, { locale: elementUILocale });

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

Object.assign(axios.defaults, axiosDefaults);
// override the baseURL since we are not posting to the api url
axios.defaults.baseURL = '/' + Config.DEFAULT_LANG;

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
    login() {
      axios.post('/login', {
        username: this.username,
        password: this.password,
        remember: this.remember
      })
      .then(({ request }) => {
        // all good, submit form manually
        window.location = request.responseURL;
      })
      .catch(e => {
        this.isSaving = false;
        this.manageBackendErrors(e.response.data.errors);
      });
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

  mounted() {
    let spinner = document.querySelectorAll('.loading-spinner')[0];
    if (spinner) {
      spinner.classList.add('fade-out');
      setTimeout(() => {
        spinner.parentNode.removeChild(spinner);
        // css animation is 300ms,
        // buffer it by 200ms so that the element is removed smoothly
      }, 500);
    }
  }
});
