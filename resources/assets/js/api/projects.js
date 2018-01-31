import axios from '../interceptor';

export default {
  getProjects(page) {
    let query = !_.isUndefined(page) ? `?page=${page}` : '';
    return axios.get(`projects${query}`);
  },

  getProject(id) {
    return axios.get(`projects/${id}`);
  },

  postProject(name) {
    return axios.post('projects',
      { name }
    );
  }
};
