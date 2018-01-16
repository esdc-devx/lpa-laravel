import LoadStatus from '../load-status-constants';
import LearningProjectsAPI from '../../api/learning-projects.js';

export const learningProjects = {
  state: {
    learningProjects: [],
    learningProjectsLoadStatus: LoadStatus.NOT_LOADED,
    learningProject: {},
    learningProjectLoadStatus: LoadStatus.NOT_LOADED
  },

  getters: {
    getLearningProjectsLoadStatus(state) {
      return state.learningProjectsLoadStatus;
    },

    getLearningProjects(state) {
      return state.learningProjects;
    },

    getLearningProjectLoadStatus(state) {
      return state.learningProjectLoadStatus;
    },

    getLearningProject(state) {
      return state.learningProject;
    }
  },

  actions: {
    loadLearningProjects({ commit }) {
      commit('setLearningProjectsLoadStatus', LoadStatus.LOADING_STARTED);
      LearningProjectsAPI.getLearningProjects()
        .then(function(response) {
          commit('setLearningProjects', response.data);
          commit('setLearningProjectsLoadStatus', LoadStatus.LOADING_SUCCESS);
        })
        .catch(function() {
          commit('setLearningProjects', []);
          commit('setLearningProjectsLoadStatus', LoadStatus.LOADING_FAILED);
        });
    },

    loadLearningProject({ commit }, data) {
      commit('setLearningProjectLoadStatus', LoadStatus.LOADING_STARTED);
      LearningProjectsAPI.getLearningProject(data.id)
        .then(function(response) {
          commit('setLearningProject', response.data);
          commit('setLearningProjectLoadStatus', LoadStatus.LOADING_SUCCESS);
        })
        .catch(function() {
          commit('setLearningProject', {});
          commit('setLearningProjectLoadStatus', LoadStatus.LOADING_FAILED);
        });
    }
  },
  mutations: {
    setLearningProjectsLoadStatus(state, status) {
      state.learningProjectsLoadStatus = status;
    },

    setLearningProjects(state, learningProjects) {
      state.learningProjects = learningProjects;
    },

    setLearningProjectLoadStatus(state, status) {
      state.learningProjectLoadStatus = status;
    },

    setLearningProject(state, learningProject) {
      state.learningProject = learningProject;
    }
  }
};
