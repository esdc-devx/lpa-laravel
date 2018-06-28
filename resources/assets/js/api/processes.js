import _ from 'lodash';
import axios from '../axios/interceptor';

export default {
  start(nameKey, entityId) {
    return axios.post(`process-definitions/${nameKey}/?entity_id=${entityId}`);
  },

  getDefinitions(entityType) {
    return axios.get(`process-definitions/${entityType}`);
  },

  getInstance(entityId) {
    return axios.get(`process-instances/${entityId}`);
  },

  getInstanceForm(formId) {
    return axios.get(`process-instance-forms/${formId}`);
  },

  claimForm(formId) {
    return axios.put(`process-instance-forms/${formId}/claim`);
  },

  unclaimForm(formId) {
    return axios.put(`process-instance-forms/${formId}/unclaim`);
  },

  save(form) {
    return axios.put(`process-instance-forms/${form.id}/edit`, form);
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
};
