import { LPA_CONFIG } from '../config.js';
import axios from '../interceptor';

export default {
  getUser() {
    return axios.get(`${LPA_CONFIG.API_URL}/user`);
  },

  login(data) {
    return axios.post(`${LPA_CONFIG.API_URL}/login`, data);
  }
};
