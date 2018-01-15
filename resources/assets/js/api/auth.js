import axios from '../interceptor';

export default {
  refreshToken(data) {
    const accessToken = data;
    return axios.get('/token/refresh', {
      params: {
        // 'accessToken': accessToken
      }
    });
  }
};
