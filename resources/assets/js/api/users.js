import axios from '@axios/interceptor';
import Config from '@/config';

export default {
  logout() {
    // create a new instance of axios
    // so that we are not using the api defaults
    let request = axios.create({baseURL: '/' + Config.DEFAULT_LANG});
    return request.post('logout');
  },

  getUsers(page) {
    let query = !_.isUndefined(page) ? `?page=${page}` : '';
    return axios.get(`users${query}`);
  },

  getUser(id) {
    let request = !_.isUndefined(id) ? id : 'current';
    return axios.get(`users/${request}`);
  },

  create(user) {
    return axios.post('users', user);
  },

  async search(name) {
    let response = await axios.get('users/search', {
      showMainLoading: false,
      params: { name: name }
    });
    return response.data.data;
  },

  update(user) {
    return axios.put(`users/${user.id}`, user);
  },

  delete(id) {
    return axios.delete(`users/${id}`);
  },

  getUserCreateInfo() {
    return axios.get('users/create');
  },

  getUserEditInfo(id) {
    return axios.get(`users/${id}/edit`);
  }
};
