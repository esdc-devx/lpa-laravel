import _ from 'lodash';
import axios from '../axios/interceptor';

export default {
  getProjects(page) {
    let query = !_.isUndefined(page) ? `?page=${page}` : '';
    return axios.get(`projects${query}`);
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
