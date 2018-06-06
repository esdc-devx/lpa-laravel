import _ from 'lodash';
import axios from '../axios/interceptor';

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

  canCreateProject() {
    return axios.get('authorization/project/create');
  },

  canEditProject(id) {
    return axios.get(`authorization/project/edit/${id}`);
  },

  canDeleteProject(id) {
    return axios.get(`authorization/project/delete/${id}`);
  },

  canStartProcess(projectId, processNameKey) {
    return axios.get(`authorization/project/${projectId}/start-process/${processNameKey}`);
  },

  startProcess(projectId, processNameKey) {
    return axios.post(`projects/${projectId}/process/${processNameKey}`);
  },

  getProcess(projectId, processId) {
    return axios.get(`projects/${projectId}/process/${processId}`);
  },

  createProject(project) {
    return axios.post('projects', project);
  },

  updateProject(project) {
    return axios.put(`projects/${project.id}`, {
      name: project.name,
      organizational_unit: project.organizational_unit
    });
  },

  deleteProject(id) {
    return axios.delete(`projects/${id}`);
  }
};
