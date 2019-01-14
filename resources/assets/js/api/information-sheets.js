import axios from '@axios/interceptor';

export default {
  getAll(entityType, entityId) {
    return axios.get(`information-sheets/${entityType}/${entityId}`);
  }
};
