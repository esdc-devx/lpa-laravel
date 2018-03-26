import Config from '../config';
import Notify from '../mixins/notify';

let defaultLang = Config.DEFAULT_LANG;
let apiUrl = '/api';

const defaults = {
  baseURL: '/' + defaultLang + apiUrl,
  timeout: 5000,
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
  Notify.notifyError('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

export default defaults;
