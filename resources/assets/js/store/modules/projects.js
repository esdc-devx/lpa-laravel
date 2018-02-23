import LoadStatus from '../load-status-constants';
import ProjectsAPI from '../../api/projects';

export default {
  namespaced: true,
  state: {
    // project being viewed
    viewing: {},
    all: [],
    projectsLoadStatus: LoadStatus.NOT_LOADED,
    projectLoadStatus: LoadStatus.NOT_LOADED,
    pagination: {}
  },

  getters: {
    projectsLoadStatus(state) {
      return state.projectsLoadStatus;
    },

    all(state) {
      return state.all;
    },

    projectLoadStatus(state) {
      return state.projectLoadStatus;
    },

    viewing(state) {
      return state.viewing;
    },

    pagination(state) {
      return state.pagination;
    }
  },

  actions: {
    loadProjects({ commit, dispatch }, page) {
      commit('setProjectsLoadStatus', LoadStatus.LOADING_STARTED);
      return new Promise((resolve, reject) => {
        ProjectsAPI.getProjects(page)
          .then(response => {
            commit('setProjects', response.data.data);
            commit('setPagination', response.data.meta);
            commit('setProjectsLoadStatus', LoadStatus.LOADING_SUCCESS);
            resolve(response.data.data);
          })
          .catch(e => {
            commit('setProjects', []);
            commit('setPagination', {});
            commit('setProjectsLoadStatus', LoadStatus.LOADING_FAILED);
            reject(e);
          });
      });
    },

    loadProject({ commit }, id) {
      commit('setProjectLoadStatus', LoadStatus.LOADING_STARTED);
      return new Promise((resolve, reject) => {
        ProjectsAPI.getProject(id)
          .then(response => {
            commit('setProject', response.data.data.project);
            commit('setProjectLoadStatus', LoadStatus.LOADING_SUCCESS);
            resolve(response.data.data);
          })
          .catch(e => {
            commit('setProject', {});
            commit('setProjectLoadStatus', LoadStatus.LOADING_FAILED);
            reject(e);
          });
      });
    },

    createProject({ commit }, project) {
      // commit('setProjectLoadStatus', LoadStatus.LOADING_STARTED);
      return new Promise((resolve, reject) => {
        ProjectsAPI.createProject(project)
          .then(response => {
            // commit('setProject', response.data.data.project);
            // commit('setProjectLoadStatus', LoadStatus.LOADING_SUCCESS);
            // resolve(response.data.data);
          })
          .catch(e => {
            // commit('setProject', {});
            // commit('setProjectLoadStatus', LoadStatus.LOADING_FAILED);
            reject(e);
          });
      });
    }
  },
  mutations: {
    setProjectsLoadStatus(state, status) {
      state.projectsLoadStatus = status;
    },

    setProjects(state, projects) {
      state.all = projects;
    },

    setProjectLoadStatus(state, status) {
      state.projectLoadStatus = status;
    },

    setProject(state, project) {
      state.viewing = project;
    },

    setPagination(state, pagination) {
      state.pagination = pagination;
    }
  }
};
