import axios from "axios";
import store from "./store/";

// Config
axios.defaults.timeout = 5000;

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

    if (error.message) {
      error.message = 'Could not reconnect. ' + error.message + '.';
    } else {
      error.message = 'Could not reconnect.';
    }
    console.log('[interpretor] ' + error.message);

  } else if (value.status === 401) {
    console.log('[interpretor] Access denied.', value);
    window.location.href = "/login";
  }

  return Promise.reject(error);
});

export default axios;
