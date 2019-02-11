import axios from '@axios/interceptor';

export default {
  update(organizationalUnit) {
    const { name_en, name_fr, email, director } = organizationalUnit;
    return axios.put(`organizational-units/${organizationalUnit.id}`, {
      name_en,
      name_fr,
      email,
      director: director.username
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
