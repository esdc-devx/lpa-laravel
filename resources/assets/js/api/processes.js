import _ from 'lodash';
import axios from '@axios/interceptor';

export default {
  start(nameKey, entityId) {
    return axios.post(`process-definitions/${nameKey}?entity_id=${entityId}`);
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

  saveForm(formId, form) {
    return axios.put(`process-instance-forms/${formId}/edit`, form);
  },

  submitForm(formId, form) {
    return axios.put(`process-instance-forms/${formId}/submit`, form);
  },

  canEditForm(formId) {
    return axios.get(`authorization/process-instance-form/edit/${formId}`);
  },

  canClaimForm(formId) {
    return axios.get(`authorization/process-instance-form/claim/${formId}`);
  },

  canUnclaimForm(formId) {
    return axios.get(`authorization/process-instance-form/unclaim/${formId}`);
  },

  canSubmitForm(formId) {
    return axios.get(`authorization/process-instance-form/submit/${formId}`);
  }
};
