import axios from '@axios/interceptor';

export default {

  update(organizationalUnit) {
    return axios.put(`organizational-units/${organizationalUnit.id}`, {
      name_en: organizationalUnit.name_en,
      name_fr: organizationalUnit.name_fr,
      email: organizationalUnit.email,
      director: organizationalUnit.director.username
    });
  },

  get(id) {
    return axios.get(`organizational-units/${id}`);
  },

  getAll() {
    return axios.get(`organizational-units`);
  },

  getEditInfo(id) {
    return axios.get(`organizational-units/${id}/edit`);
  }
};
