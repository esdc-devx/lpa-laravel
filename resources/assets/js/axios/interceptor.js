import '@babel/polyfill';

import Vue from 'vue';
import axios from 'axios';
import axiosDefaults from './defaults';
import HttpStatusCodes from './http-status-codes';
import router from '@/router';
import Config from '@/config';
import Notify from '@mixins/notify';

let onLanguageChange = lang => {
  axios.defaults.baseURL = '/' + lang + '/api';
  axios.defaults.headers.common['Accept-Language'] = lang;
};

import('@/store/').then(store => {
  store.default.watch((state, getters) => state.language, lang => {
    onLanguageChange(lang);
  });
});

// affect the language on init
onLanguageChange(Config.DEFAULT_LANG);
Object.assign(axios.defaults, axiosDefaults);

// General Request Processing
axios.interceptors.request.use(
  config => {
    if (config.showMainLoading) {
      // Show Main loading animation before sending any request.
      store.commit('showMainLoading');
    }
    return config;
  },
  error => {
    if (error.request.config.showMainLoading) {
      // Hide Main loading animation upon request processing error.
      store.dispatch('hideMainLoading');
    }
    Vue.$log.debug(error);
    return Promise.reject(error);
  }
);

// General Response Processing
axios.interceptors.response.use(
  response => {
    if (response.config.showMainLoading) {
      // Hide Main loading animation after receiving a response.
      store.dispatch('hideMainLoading');
    }
    return response.data;
  },
  error => {
    let trans = Vue.prototype.trans;
    let response = error.response;

    if (!response || (response && response.config.showMainLoading)) {
      // Hide Main loading animation upon response processing error.
      store.dispatch('hideMainLoading');
    }

    // if the response is undefined, we likely got a timeout
    if (!response) {
      Notify.notifyError({
        message: trans('errors.timeout')
      });
      Vue.$log.error(`[axios][interceptor]: ${trans('errors.timeout')}`);
      return Promise.reject(error);
    }

    let status = response.status;
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
            window.location.href = `/${store.state.language}/login`;
          }
        });
    } else if (status === HttpStatusCodes.NOT_FOUND) {
      let newPath = router.history.pending ? router.history.pending.path : router.history.current.path;
      router.replace({ name: 'not-found', params: { '0': newPath } });
    } else if (status === HttpStatusCodes.FORBIDDEN) {
      Notify.notifyError({
        title: errorResponse.type,
        message: errorResponse.message || trans('errors.forbidden')
      });
    } else if (status === HttpStatusCodes.SERVER_ERROR) {
      // internal error
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

    return Promise.reject(error);
  }
);

window.axios = axios;

export default axios;
