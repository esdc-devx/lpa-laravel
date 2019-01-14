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

  getHistory(entityType, entityId) {
    return axios.get(`process-instances/?entity_type=${entityType}&entity_id=${entityId}`);
  },

  cancelInstance(id) {
    return axios.put(`process-instances/${id}/cancel`);
  },

  canCancel(id) {
    return axios.get(`authorization/process-instance/cancel/${id}`);
  }
};
