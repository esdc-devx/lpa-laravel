import axios from '../axios/interceptor';

export default {
  getUsers() {
    return axios.get('users');
  },

  getUser() {
    return axios.get('users/current');
  }
};
