import Vue from 'vue';
import axios from 'axios';
import axiosDefaults from './defaults';
import HttpStatusCodes from './http-status-codes';
import router from '@/router';
import store from '@/store/';
import Config from '@/config';
import EventBus from '@/event-bus';
import Notify from '@mixins/notify';

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
  let trans = Vue.prototype.trans;

  let response = error.response;
  // if the response is undefined, we likely got a timeout
  if (!response) {
    Notify.notifyError({
      message: trans('errors.timeout')
    });
    Vue.$log.error(`[axios][interceptor]: ${trans('errors.timeout')}`);
    return Promise.reject(error);
  }

  let errorResponse = response && response.data && response.data.errors ? response.data.errors : '';

  if (response.status === HttpStatusCodes.UNAUTHORIZED) {
    // not logged in, redirect to login
    Vue.prototype.$alert(
      trans('auth.session_expired'),
      trans('components.notice.type.info'),
      {
        type: 'info',
        showClose: false,
        confirmButtonText: trans('base.actions.ok'),
        callback: action => {
          // we need to change the location manually since the backend handles the login page
          window.location.href = `/${store.getters.language}/login`;
        }
      });
  } else if (response.status === HttpStatusCodes.FORBIDDEN) {
    Notify.notifyError({
      message: trans('errors.forbidden')
    });
  } else if (response.status === HttpStatusCodes.SERVER_ERROR) {
    // internal error
    Notify.notifyError({
      message: trans('errors.general')
    });
  } else if (response.status === HttpStatusCodes.BAD_REQUEST) {
    Notify.notifyError({
      message: trans('errors.bad_request')
    });
  }

  Vue.$log.error(`[axios][interceptor]: ${errorResponse}`);
  return Promise.reject(error);
});

EventBus.$on('Store:languageUpdate', onLanguageChange);

if (Config.DEBUG) {
  window.axios = axios;
}

export default axios;
