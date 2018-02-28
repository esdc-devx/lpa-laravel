import axios from '../axios/interceptor';

export default {
  getUsers(page) {
    let query = !_.isUndefined(page) ? `?page=${page}` : '';
    return axios.get(`users${query}`);
  },

  getUser(id) {
    let request = !_.isUndefined(id) ? id : 'current';
    return axios.get(`users/${request}`);
  },

  createUser(user) {
    return axios.post('users', user);
  },

  updateUser(user) {
    return axios.put(`users/${user.id}`, user);
  },

  getUserCreateInfo() {
    return axios.get('users/create');
  },

  searchUser(name) {
    return axios.get(`users/search?name=${name}`);
  }
};
