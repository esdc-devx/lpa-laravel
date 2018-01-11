/**
 * status = 1 -> Loading has started
 * status = 2 -> Loading completed successfully
 * status = 3 -> Loading completed unsuccessfully
 */

import LearningProjectsAPI from '../../api/learning-projects.js';

export const learningProjects = {
  state: {
    learningProjects: [],
    learningProjectsLoadStatus: 0,
    learningProject: {},
    learningProjectLoadStatus: 0
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
      commit('setLearningProjectsLoadStatus', 1);
      LearningProjectsAPI.getLearningProjects()
        .then(function(response) {
          commit('setLearningProjects', response.data);
          commit('setLearningProjectsLoadStatus', 2);
        })
        .catch(function() {
          commit('setLearningProjects', []);
          commit('setLearningProjectsLoadStatus', 3);
        });
    },

    loadLearningProject({ commit }, data) {
      commit('setLearningProjectLoadStatus', 1);
      LearningProjectsAPI.getLearningProject(data.id)
        .then(function(response) {
          commit('setLearningProject', response.data);
          commit('setLearningProjectLoadStatus', 2);
        })
        .catch(function() {
          commit('setLearningProject', {});
          commit('setLearningProjectLoadStatus', 3);
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
