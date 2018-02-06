import _ from 'lodash';
import axios from '../interceptor';

export default {
  getProjects(page) {
    let query = !_.isUndefined(page) ? `?page=${page}` : '';
    return axios.get(`projects${query}`);
  },

  getProject(id) {
    return axios.get(`projects/${id}`);
  },

  createProject(project) {
    return axios.post('projects', project);
  },

  updateProject(project) {
    return axios.put('projects', project);
  }
};
