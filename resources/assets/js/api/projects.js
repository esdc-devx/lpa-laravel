import axios from '../interceptor';

// REMOVE ME
import data from "./fake-project-list.json";

export default {
  getProjects() {
    return axios.get('/projects');
  },

  getProject(id) {
    return axios.get(`/projects/${id}`);
  },

  postProject(name) {
    return axios.post('/projects',
      { name }
    );
  }
};
