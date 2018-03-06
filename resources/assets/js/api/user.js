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

  deleteUser(id) {
    return axios.delete(`users/${id}`);
  },

  getUserCreateInfo() {
    return axios.get('users/create');
  },

  // @todo: wait for backend route to be implemented
  getUserEditInfo() {
    return axios.get('users/edit');
  },

  searchUser(name) {
    return axios.get(`users/search?name=${name}`);
  }
};
