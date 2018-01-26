import LoadStatus from '../load-status-constants';
import ProjectsAPI from '../../api/projects.js';

export const projects = {
  state: {
    projects: [],
    projectsLoadStatus: LoadStatus.NOT_LOADED,
    project: {},
    projectLoadStatus: LoadStatus.NOT_LOADED
  },

  getters: {
    projectsLoadStatus(state) {
      return state.projectsLoadStatus;
    },

    projects(state) {
      return state.projects;
    },

    projectLoadStatus(state) {
      return state.projectLoadStatus;
    },

    project(state) {
      return state.project;
    }
  },

  actions: {
    loadProjects({ commit }) {
      commit('setProjectsLoadStatus', LoadStatus.LOADING_STARTED);
      ProjectsAPI.getProjects()
        .then(function(response) {
          commit('setProjects', response.data.data.paginator.data);
          commit('setProjectsLoadStatus', LoadStatus.LOADING_SUCCESS);
        })
        .catch(function() {
          commit('setProjects', []);
          commit('setProjectsLoadStatus', LoadStatus.LOADING_FAILED);
        });
    },

    loadProject({ commit }, data) {
      commit('setProjectLoadStatus', LoadStatus.LOADING_STARTED);
      ProjectsAPI.getProject(data.id)
        .then(function(response) {
          commit('setProject', response.data);
          commit('setProjectLoadStatus', LoadStatus.LOADING_SUCCESS);
        })
        .catch(function() {
          commit('setProject', {});
          commit('setProjectLoadStatus', LoadStatus.LOADING_FAILED);
        });
    }
  },
  mutations: {
    setProjectsLoadStatus(state, status) {
      state.projectsLoadStatus = status;
    },

    setProjects(state, projects) {
      state.projects = projects;
    },

    setProjectLoadStatus(state, status) {
      state.projectLoadStatus = status;
    },

    setProject(state, project) {
      state.project = project;
    }
  }
};
