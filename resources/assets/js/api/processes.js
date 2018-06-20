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
  }
};
