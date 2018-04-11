import Vue from 'vue';
import axios from 'axios';
import axiosDefaults from './defaults';
import HttpStatusCodes from './http-status-codes';
import router from '../router';
import store from '../store/';
import Config from '../config';
import EventBus from '../event-bus';
import Notify from '../mixins/notify';

let onLanguageChange = lang => {
  axios.defaults.baseURL = '/' + lang + '/api';
  axios.defaults.headers.common['Accept-Language'] = lang;
};
// affect the language on init
onLanguageChange(Config.DEFAULT_LANG);
Object.assign(axios.defaults, axiosDefaults);

// General error handling
axios.interceptors.request.use(config => config, error => {
  Vue.$log.debug(error);
  return Promise.reject(error);
});
axios.interceptors.response.use(response => response, error => {
  let response = error.response;
  let errorResponse = response.data && response.data.errors ? response.data.errors : '';
  let errorMsg = error.message || '';

  if (response.status === HttpStatusCodes.UNAUTHORIZED) {
    // not logged in, redirect to login
    Vue.prototype.$alert(
      'You are not authenticated. You will be redirected to the login page.',
      'Error',
      {
        showClose: false,
        confirmButtonText: 'Ok',
        callback: action => {
          // we need to change the location manually since the backend handles the login page
          window.location.href = `/${store.getters.language}/login`;
        }
      });
  } else if (response.status === HttpStatusCodes.FORBIDDEN) {
    router.replace({name: 'forbidden', params: {'0': router.history.current.path}});
  } else if (response.status === HttpStatusCodes.NOT_FOUND) {
    router.replace(`/${store.getters.language}/${HttpStatusCodes.NOT_FOUND}`);
  } else if (response.status === HttpStatusCodes.SERVER_ERROR) {
    // internal error
    Notify.notifyError('General exception. Please contact your administrator.');
  } else if (response.status === HttpStatusCodes.BAD_REQUEST) {
    Notify.notifyError('Bad request. Please refresh your page.');
  }

  Vue.$log.error(`[axios][interceptor]: ${errorResponse}`);
  return Promise.reject(error);
});

EventBus.$on('Store:languageUpdate', onLanguageChange);

if (Config.DEBUG) {
  window.axios = axios;
}

export default axios;
