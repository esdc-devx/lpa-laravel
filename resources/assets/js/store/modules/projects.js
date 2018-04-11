import ProjectsAPI from '../../api/projects';

export default {
  namespaced: true,

  state: {
    // project being viewed
    viewing: {},
    all: [],
    pagination: {}
  },

  getters: {
    all(state) {
      return state.all;
    },

    viewing(state) {
      return state.viewing;
    },

    pagination(state) {
      return state.pagination;
    }
  },

  actions: {
    async loadProjects({ commit, dispatch }, page) {
      let response = await ProjectsAPI.getProjects(page);
      commit('setProjects', response.data.data);
      commit('setPagination', response.data.meta);
      return response.data.data;
    },

    async loadProject({ commit }, id) {
      let response = await ProjectsAPI.getProject(id);
      commit('setProject', response.data.data.project);
      return response.data.data;
    },

    async createProject({ commit }, project) {
      let response = await ProjectsAPI.createProject(project)
      // commit('setProject', response.data.data.project);
      // resolve(response.data.data);
    }
  },

  mutations: {
    setProjects(state, projects) {
      state.all = projects;
    },

    setProject(state, project) {
      state.viewing = project;
    },

    setPagination(state, pagination) {
      state.pagination = pagination;
    }
  }
};
