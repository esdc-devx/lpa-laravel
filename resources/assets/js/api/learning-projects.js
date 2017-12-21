import { LPA_CONFIG } from '../config.js';

// REMOVE ME
import data from "./fake-data.json";

export default {
  getLearningProjects() {
    // REMOVE ME
    return Promise.resolve({data});
    // return axios.get(`${LPA_CONFIG.API_URL}/learning-projects`);
  },

  getLearningProject(id) {
    return axios.get(`${LPA_CONFIG.API_URL}/learning-projects/${id}`);
  },

  postLearningProject(name) {
    return axios.post(`${LPA_CONFIG.API_URL}/learning-projects`,
      { name }
    );
  }
};
