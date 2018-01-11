import { LPA_CONFIG } from '../config.js';
import axios from '../interceptor';

export default {
  refreshToken(data) {
    const accessToken = data;
    return axios.get(`${LPA_CONFIG}/token/refresh`, {
      params: {
        // 'accessToken': accessToken
      }
    });
  }
};
