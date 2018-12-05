import _ from 'lodash';
import axios from '@axios/interceptor';

export default {
  getProjects() {
    return axios.get(`projects`);
  },

  getProject(id) {
    return axios.get(`projects/${id}`);
  },

  getProjectCreateInfo() {
    return axios.get('projects/create');
  },

  getProjectEditInfo(id) {
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
  },

  getProcess(projectId, processId) {
    return axios.get(`projects/${projectId}/process/${processId}`);
  },

  create(project) {
    return axios.post('projects', project);
  },

  update(project) {
    return axios.put(`projects/${project.id}`, {
      name: project.name,
      organizational_unit: project.organizational_unit
    });
  },

  delete(id) {
    return axios.delete(`projects/${id}`);
  }
};
