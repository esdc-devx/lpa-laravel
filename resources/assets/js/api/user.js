import axios from '../axios/interceptor';

export default {
  getUsers(page) {
    let query = !_.isUndefined(page) ? `?page=${page}` : '';
    return axios.get(`api/users${query}`);
  },

  getUser(id) {
    let request = !_.isUndefined(id) ? id : 'current';
    return axios.get(`api/users/${request}`);
  },

  createUser(user) {
    return axios.post('api/users', user);
  },

  updateUser(user) {
    return axios.put(`api/users/${user.id}`, user);
  },

  deleteUser(id) {
    return axios.delete(`api/users/${id}`);
  },

  getUserCreateInfo() {
    return axios.get('api/users/create');
  },

  // @todo: wait for backend route to be implemented
  getUserEditInfo() {
    return axios.get('api/users/edit');
  },

  searchUser(name) {
    return axios.get(`api/users/search?name=${name}`);
  }
};
