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
  let status = response.status;

  // if the response is undefined, we likely got a timeout
  if (!response) {
    Notify.notifyError({
      message: trans('errors.timeout')
    });
    Vue.$log.error(`[axios][interceptor]: ${trans('errors.timeout')}`);
    return Promise.reject(error);
  }

  let errorResponse = response.data;

  if (status === HttpStatusCodes.UNAUTHORIZED) {
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
  } else if (status === HttpStatusCodes.NOT_FOUND) {
    let newPath = router.history.pending ? router.history.pending.path : router.history.current.path;
    // cannot redirect user to same path,
    // so we need to change it while keeping the path to be accessed
    // thus we add '/404' at the end
    router.replace({ name: 'not-found', params: { '0': newPath + '/404' } });
  } else if (status === HttpStatusCodes.FORBIDDEN) {
    Notify.notifyError({
      title: errorResponse.type,
      message: errorResponse.message || trans('errors.forbidden')
    });
  } else {
    // internal error or anything else
    Notify.notifyError({
      message: trans('errors.general')
    });
  }

  // Log error to the console.
  if (errorResponse) {
    let errorMessage = '';
    errorMessage += errorResponse.message || '';
    errorMessage += errorResponse.debug || '';
    if (errorMessage) {
      Vue.$log.error(`[axios][interceptor]: ${errorMessage}`);
    }
  }

  // make sure that the loading is hidden
  // in case we hit a page that cannot be loaded
  store.dispatch('hideMainLoading');

  return Promise.reject(error);
});

EventBus.$on('Store:languageUpdate', onLanguageChange);

if (Config.DEBUG) {
  window.axios = axios;
}

export default axios;
