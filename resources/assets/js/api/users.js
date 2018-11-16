import axios from '@axios/interceptor';
import Config from '@/config';

export default {
  logout() {
    // create a new instance of axios
    // so that we are not using the api defaults
    let request = axios.create({baseURL: '/' + Config.DEFAULT_LANG});
    return request.post('logout');
  },

  async getUsers(page) {
    let query = !_.isUndefined(page) ? `?page=${page}` : '';
    return await axios.get(`users${query}`);
  },

  async getUser(id) {
    let request = !_.isUndefined(id) ? id : 'current';
    return await axios.get(`users/${request}`);
  },

  async create(user) {
    return await axios.post('users', user);
  },

  async search(name) {
    let response = await axios.get('users/search', {
      showMainLoading: false,
      params: { name: name }
    });
    return response.data;
  },

  async update(user) {
    return await axios.put(`users/${user.id}`, user);
  },

  async delete(id) {
    return await axios.delete(`users/${id}`);
  },

  async getUserCreateInfo() {
    return await axios.get('users/create');
  },

  async getUserEditInfo(id) {
    return await axios.get(`users/${id}/edit`);
  }
};
