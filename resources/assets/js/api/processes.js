import _ from 'lodash';
import axios from '../axios/interceptor';

export default {
  startProcess(nameKey, entityId) {
    return axios.post(`process-definitions/${nameKey}/?entity_id=${entityId}`);
  },

  getProcesses(entityType) {
    return axios.get(`process-definitions/${entityType}`);
  },

  getProcess(entityId) {
    return axios.get(`process-instances/${entityId}`);
  }
};
