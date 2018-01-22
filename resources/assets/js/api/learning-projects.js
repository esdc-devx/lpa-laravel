import axios from '../interceptor';

// REMOVE ME
import data from "./fake-project-list.json";

export default {
  getLearningProjects() {
    // REMOVE ME
    return Promise.resolve({ data });
    // return axios.get(`${LPA_CONFIG.API_URL}/learning-projects`);
  },

  getLearningProject(id) {
    return axios.get(`/learning-projects/${id}`);
  },

  postLearningProject(name) {
    return axios.post('/learning-projects',
      { name }
    );
  }
};
