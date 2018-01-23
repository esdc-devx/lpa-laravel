import axios from '../interceptor';

// REMOVE ME
import data from "./fake-project-list.json";

export default {
  getProjects() {
    // REMOVE ME
    return Promise.resolve({ data });
    // return axios.get(`${LPA_CONFIG.API_URL}/projects`);
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
