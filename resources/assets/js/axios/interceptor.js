import axios from 'axios';
import HttpStatusCodes from './http-status-codes';
import router from '../router';
import store from '../store/';
import Config from '../config';
import EventBus from '../helpers/event-bus';
import Notify from '../mixins/notify.js';

let onLanguageChange = lang => {
  axios.defaults.baseURL = '/' + lang + apiUrl;
  axios.defaults.headers.common['Accept-Language'] = lang;
};

// Config
let defaultLang = Config.defaultLang;
let apiUrl = '/api';
// affect the language on init
onLanguageChange(defaultLang);
axios.defaults.timeout = 5000;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// Register the CSRF Token as a common header with Axios so that
// all outgoing HTTP requests automatically have it attached. This is just
// a simple convenience so we don't have to attach every token manually.
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
  Notify.notifyError('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// General error handling
axios.interceptors.request.use(config => config, error => {
  console.group('[axios][interceptor] Request Error');
  console.log(error);
  console.groupEnd();
  return Promise.reject(error);
});
axios.interceptors.response.use(response => response, error => {
  let response = error.response;
  if (response.status === HttpStatusCodes.UNAUTHORIZED) {
    // not logged in, redirect to login
    // @refactorme: should warn the user with a popup (Ok only) then redirect to login
    alert('You are not authenticated. You will be redirected to the login page.');
    // we need to change the location manually since the backend handles the login page
    window.location.href = `/${store.getters.language}/login`;
  } else if (response.status === HttpStatusCodes.FORBIDDEN) {
    alert('You are not authorized to access this page. You will be redirected to the home page.');
    router.push(`/${HttpStatusCodes.FORBIDDEN}`);
  } else if (response.status === HttpStatusCodes.NOT_FOUND) {
    router.push(`/${HttpStatusCodes.NOT_FOUND}`);
  } else if (response.status === HttpStatusCodes.SERVER_ERROR) {
    // internal error
    let errorResponse = response.data && response.data.errors ? response.data.errors : '';
    let errorMsg = error.message || '';
    Notify.notifyError(errorMsg);
    console.group('[axios][interceptor] Response Error');
    console.error(errorMsg, errorResponse);
    console.groupEnd();
  }

  return Promise.reject(error);
});

EventBus.$on('Store:languageUpdate', onLanguageChange);

if (Config.debug) {
  window.axios = axios;
}

export default axios;
