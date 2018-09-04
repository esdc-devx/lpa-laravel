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

  cancelInstance(processId) {
    return axios.put(`process-instances/${processId}/cancel`);
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

  releaseForm(formId, username) {
    return axios.put(`process-instance-forms/${formId}/release?editor=${username}`);
  },

  submitForm(formId, form) {
    return axios.put(`process-instance-forms/${formId}/submit`, form);
  },

  canCancelProcess(processId) {
    return axios.get(`authorization/process-instance/cancel/${processId}`);
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
  },

  canReleaseForm(formId, username) {
    return axios.get(`authorization/process-instance-form/release/${formId}?editor=${username}`);
  }
};
