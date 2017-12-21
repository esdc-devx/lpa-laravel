import { LPA_CONFIG } from '../config.js';

// REMOVE ME
import data from "../fake-data.json";

export default {
  /*
    GET     /api/learning-projects
  */
  getLearningProjects() {
    // REMOVE ME
    return Promise.resolve({data});
    // return axios.get(`${LPA_CONFIG.API_URL}/learning-projects`);
  },
  /*
    GET     /api/learning-projects/{id}
  */
  getLearningProject(id) {
    return axios.get(`${LPA_CONFIG.API_URL}/learning-projects/${id}`);
  },
  /*
    POST    /api/learning-projects
  */
  postLearningProject(name) {
    return axios.post(`${LPA_CONFIG.API_URL}/learning-projects`,
      { name }
    );
  }
};
