import ProjectsAPI from '../../api/projects';

export default {
  namespaced: true,

  state: {
    // project being viewed
    viewing: {
      name: ''
    },
    all: [],
    organizationalUnits: []
  },

  getters: {
    all(state) {
      return state.all;
    },

    viewing(state) {
      return state.viewing;
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
      commit('setViewing', response.data.data.project);
      commit('setOrganizationalUnits', response.data.data.organizational_units);
      return response.data.data;
    },

    async loadProjects({ commit, dispatch }) {
      let response = await ProjectsAPI.getProjects();
      commit('setProjects', response.data.data.projects);
      return response.data.data.projects;
    },

    async loadProject({ commit }, id) {
      let response = await ProjectsAPI.getProject(id);
      commit('setViewing', response.data.data.project);
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

    async canStartProcess({ commit }, { projectId, processDefinitionNameKey }) {
      let response = await ProjectsAPI.canStartProcess(projectId, processDefinitionNameKey);
      return response.data.data.allowed;
    },

    async create({ commit }, project) {
      let response = await ProjectsAPI.create(project);
      return response.data.data;
    },

    async update({ commit }, project) {
      await ProjectsAPI.update(project);
    },

    async delete({ commit }, id) {
      await ProjectsAPI.delete(id);
    }
  },

  mutations: {
    setProjects(state, projects) {
      state.all = projects;
    },

    setViewing(state, project) {
      state.viewing = project;
    },

    setOrganizationalUnits(state, organizationalUnits) {
      state.organizationalUnits = organizationalUnits;
    }
  }
};
