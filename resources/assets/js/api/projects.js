import _ from 'lodash';
import axios from '@axios/interceptor';

export default {
  create(project) {
    return axios.post('projects', project);
  },

  update(project) {
    return axios.put(`projects/${project.id}`, project);
  },

  delete(id) {
    return axios.delete(`projects/${id}`);
  },

  get(id) {
    return axios.get(`projects/${id}`);
  },

  getAll() {
    return axios.get(`projects`);
  },

  getCreateInfo() {
    return axios.get('projects/create');
  },

  getEditInfo(id) {
    return axios.get(`projects/${id}/edit`);
  },

  canCreate() {
    return axios.get('authorization/project/create');
  },

  canEdit(id) {
    return axios.get(`authorization/project/edit/${id}`);
  },

  canDelete(id) {
    return axios.get(`authorization/project/delete/${id}`);
  },

  canStartProcess(projectId, processDefinitionNameKey) {
    return axios.get(`authorization/project/${projectId}/start-process/${processDefinitionNameKey}`);
  }
};
