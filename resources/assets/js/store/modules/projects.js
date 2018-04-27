import ProjectsAPI from '../../api/projects';

export default {
  namespaced: true,

  state: {
    // project being viewed
    viewing: {},
    all: [],
    pagination: {},
    organizationalUnits: []
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
    },

    organizationalUnits(state) {
      return state.organizationalUnits;
    }
  },

  actions: {
    async loadProjectCreateInfo({ commit }) {
      let response = await ProjectsAPI.getProjectCreateInfo();
      commit('setOrganizationalUnits', response.data.data.organizational_units);
      return response.data.data;
    },

    async loadProjectEditInfo({ commit }, id) {
      let response = await ProjectsAPI.getProjectEditInfo(id);
      commit('setViewingProject', response.data.data.project);
      commit('setOrganizationalUnits', response.data.data.organizational_units);
      return response.data.data;
    },

    async loadProjects({ commit, dispatch }, page) {
      let response = await ProjectsAPI.getProjects(page);
      commit('setProjects', response.data.data.projects);
      commit('setPagination', response.data.meta);
      return response.data.data.projects;
    },

    async loadProject({ commit }, id) {
      let response = await ProjectsAPI.getProject(id);
      commit('setViewingProject', response.data.data.project);
      return response.data.data;
    },

    async canCreateProject({ commit }) {
      let response = await ProjectsAPI.canCreateProject();
      return response.data.data.allowed;
    },

    async canEditProject({ commit }, id) {
      let response = await ProjectsAPI.canEditProject(id);
      return response.data.data.allowed;
    },

    async canDeleteProject({ commit }, id) {
      let response = await ProjectsAPI.canDeleteProject(id);
      return response.data.data.allowed;
    },

    async createProject({ commit }, project) {
      let response = await ProjectsAPI.createProject(project);
      return response.data.data;
    },

    async updateProject({ commit }, project) {
      await ProjectsAPI.updateProject(project);
    },

    async deleteProject({ commit }, id) {
      await ProjectsAPI.deleteProject(id);
    }
  },

  mutations: {
    setProjects(state, projects) {
      state.all = projects;
    },

    setViewingProject(state, project) {
      state.viewing = project;
    },

    setPagination(state, pagination) {
      state.pagination = pagination;
    },

    setOrganizationalUnits(state, organizationalUnits) {
      state.organizationalUnits = organizationalUnits;
    }
  }
};
