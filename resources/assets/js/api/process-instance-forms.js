import axios from '@axios/interceptor';

export default {
  getInstanceForm(id) {
    return axios.get(`process-instance-forms/${id}`);
  },

  claimForm(id) {
    return axios.put(`process-instance-forms/${id}/claim`);
  },

  unclaimForm(id) {
    return axios.put(`process-instance-forms/${id}/unclaim`);
  },

  saveForm(id, form) {
    return axios.put(`process-instance-forms/${id}/edit`, form);
  },

  releaseForm(id, username) {
    return axios.put(`process-instance-forms/${id}/release?editor=${username}`);
  },

  submitForm(id, form) {
    return axios.put(`process-instance-forms/${id}/submit`, form);
  },

  canEditForm(id) {
    return axios.get(`authorization/process-instance-form/edit/${id}`);
  },

  canClaimForm(id) {
    return axios.get(`authorization/process-instance-form/claim/${id}`);
  },

  canUnclaimForm(id) {
    return axios.get(`authorization/process-instance-form/unclaim/${id}`);
  },

  canSubmitForm(id) {
    return axios.get(`authorization/process-instance-form/submit/${id}`);
  },

  canReleaseForm(id) {
    return axios.get(`authorization/process-instance-form/release/${id}`);
  }
};
