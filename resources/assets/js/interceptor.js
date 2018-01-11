import axios from "axios";
import store from "./store/";

//  Config
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
    console.log('Could not reconnect.');

    if (error.message) {
      error.message = 'Could not reconnect. ' + error.message + '.';
    } else {
      error.message = 'Could not reconnect.';
    }

  } else if (value.status === 401) {
    console.log('Access denied.', value);
    window.location.href = "/login";
  }

  return Promise.reject(error);
});

// axios.interceptors.request.use(
//   config => {
//     let credential = store.state.credential;
//     let isAuthorize = store.state.isAuthorize;
//     if (credential && isAuthorize) {
//       // config.headers.common["Authorization"] = "Bearer " + credential;
//     }
//     return config;
//   },
//   error => {
//     console.group("[Axios][Interceptor] Request Error");
//     console.log(error);
//     console.groupEnd();
//     return Promise.reject(error);
//   }
// );

axios.interceptors.response.use(
  data => {
    return data;
  },
  error => {
    console.group("[Axios][Interceptor] Response Error");
    console.log(error);
    console.groupEnd();
    return Promise.reject(error);
  }
);

export default axios;
