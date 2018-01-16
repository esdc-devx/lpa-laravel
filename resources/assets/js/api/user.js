import axios from '../interceptor';

export default {
  getUser() {
    return axios.get('/user');
  },

  login(data) {
    return axios.post('/login', data);
  }
};
