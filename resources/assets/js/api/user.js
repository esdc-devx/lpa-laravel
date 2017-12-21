/*
  Imports the Roast API URL from the config.
*/
import { LPA_CONFIG } from '../config.js';

export default {
  /*
    GET   /api/user
  */
  getUser() {
    return axios.get(`${LPA_CONFIG.API_URL}/user`);
  },

  login(data) {
    return axios.post(`${LPA_CONFIG.API_URL}/login`, data);
  },

  logout() {
    // debugger;
    // return axios.post('/logout');
  }
};
