import axios from '@axios/interceptor';

export default {
  update(processNotification) {
    const { name_en, name_fr, subject_en, subject_fr, body_en, body_fr } = processNotification;
    return axios.put(`process-notifications/${processNotification.id}`, {
      name_en,
      name_fr,
      subject_en,
      subject_fr,
      body_en,
      body_fr
    });
  },

  get(id) {
    return axios.get(`process-notifications/${id}`);
  },

  getAll() {
    return axios.get('process-notifications');
  },

  getEditInfo(id) {
    return axios.get(`process-notifications/${id}/edit`);
  }
};
