/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Authentication Interceptor
axios.interceptors.response.use((response) => response, (error) => {
  let value = error.response;

  // if (value.status === 401 && value.data.message === 'Expired JWT Token'
  //   && (!value.config || !value.config.renewToken)) {
  //   console.log('Token JWT expiré. Reconnexion ...');

  //   // renewToken performs authentication using username/password saved in sessionStorage/localStorage
  //   return this.renewToken().then(() => {
  //     error.config.baseURL = undefined; // baseURL is already present in url
  //     return this.axios.request(error.config);
  //   }).then((response) => {
  //     console.log('Reconnecté !');
  //     return response;
  //   });

  // } else if (value.status === 401 && value.config && value.config.renewToken) {
  if (value.status === 401 && value.config && value.config.renewToken) {
    console.log('Could not reconnect.');

    if (error.message) {
      error.message = 'Could not reconnect. ' + error.message + '.';
    } else {
      error.message = 'Could not reconnect.';
    }

  } else if (value.status === 401) {
    console.log('Access denied.', value);
    // window.location.href = "/login";
  }

  return Promise.reject(error);
});

import Vue from "vue";
import App from "./app.vue";

// Import Element UI
import ElementUI from 'element-ui';
// Element UI Localization
import locale from 'element-ui/lib/locale/lang/en';

import router from "./router";
import store from './store.js';

import _ from "lodash";

Vue.use(ElementUI, { locale });

new Vue({
  el: "app",
  router,
  store,
  template: "<app/>",
  components: { App }
});
