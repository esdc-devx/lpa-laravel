import axios from '@axios/interceptor';
import Config from '@/config';

export default {
  create(user) {
    return axios.post('users', user);
  },

  update(user) {
    return axios.put(`users/${user.id}`, user);
  },

  delete(id) {
    return axios.delete(`users/${id}`);
  },

  get(id) {
    let request = !_.isUndefined(id) ? id : 'current';
    return axios.get(`users/${request}`);
  },

  getAll() {
    return axios.get('users');
  },

  getCreateInfo() {
    return axios.get('users/create');
  },

  getEditInfo(id) {
    return axios.get(`users/${id}/edit`);
  },

  logout() {
    // create a new instance of axios
    // so that we are not using the api defaults
    let request = axios.create({ baseURL: '/' + Config.DEFAULT_LANG });
    return request.post('logout');
  },

  search(name) {
    return axios.get('users/search', {
      showMainLoading: false,
      params: { name: name }
    });
  }
};
