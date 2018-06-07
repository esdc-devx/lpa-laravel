import _ from 'lodash';
import axios from '../axios/interceptor';

export default {
  canStartProcess(entityId, nameKey) {
    return axios.get(`authorization/project/${entityId}/start-process/${nameKey}`);
  },

  startProcess(entityId, nameKey) {
    return axios.post(`projects/${entityId}/process/${nameKey}`);
  },

  getProcess(entityId, processId) {
    return axios.get(`projects/${entityId}/process/${processId}`);
  }
};
