import Vue from 'vue';
import Config from '@/config';
import Notify from '@mixins/notify';
import '@/locale';

let defaultLang = Config.DEFAULT_LANG;

const defaults = {
  baseURL: '/' + defaultLang + '/api',
  // buff to 10s to be sure that when on slow connections we are still capable of loading data
  timeout: 10000,
  headers: {
    common: {
      'Accept-Language': defaultLang,
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    }
  }
};
// Register the CSRF Token as a common header with Axios so that
// all outgoing HTTP requests automatically have it attached. This is just
// a simple convenience so we don't have to attach every token manually.
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
  defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
  Notify.notifyError({
    message: Vue.prototype.trans('errors.csrf_not_found')
  });
}

export default defaults;
