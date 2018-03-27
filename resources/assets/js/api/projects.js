import _ from 'lodash';
import axios from '../axios/interceptor';

export default {
  getProjects(page) {
    let query = !_.isUndefined(page) ? `?page=${page}` : '';
    return axios.get(`api/projects${query}`);
  },

  getProject(id) {
    return axios.get(`api/projects/${id}`);
  },

  createProject(project) {
    return axios.post('api/projects', project);
  },

  updateProject(project) {
    return axios.put('api/projects', project);
  }
};
