import axios from 'axios';
import store from './store/';

// Config
axios.defaults.baseURL = '/api';
axios.defaults.timeout = 5000;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// Register the CSRF Token as a common header with Axios so that
// all outgoing HTTP requests automatically have it attached. This is just
// a simple convenience so we don't have to attach every token manually.
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Authentication Interceptor
axios.interceptors.response.use((response) => response, (error) => {
  let value = error.response;

  // if (value.status === 401 && value.data.message === 'Expired JWT Token'
  //   && (!value.config || !value.config.renewToken)) {
  //   console.log('Expired JWT Token. Reconnecting ...');

  //   // renewToken performs authentication using username/password saved in sessionStorage/localStorage
  //   return this.renewToken().then(() => {
  //     error.config.baseURL = undefined; // baseURL is already present in url
  //     return this.axios.request(error.config);
  //   }).then((response) => {
  //     console.log('Reconnected !');
  //     return response;
  //   });

  // } else if (value.status === 401 && value.config && value.config.renewToken) {
  if (value.status === 401 && value.config && value.config.renewToken) {

    if (error.message) {
      error.message = 'Could not reconnect. ' + error.message + '.';
    } else {
      error.message = 'Could not reconnect.';
    }
  } else if (value.status === 401) {
    window.location.href = '/' + store.getters.language + '/login';
  }

  return Promise.reject(error);
});

export default axios;
