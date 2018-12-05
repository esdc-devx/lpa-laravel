import ProjectsAPI from '@api/projects';
import { helpers } from '@/helpers.js';

export default {
  namespaced: true,

  state: {
    // project being viewed
    viewing: {
      name: ''
    },
    all: [],
    permissions: {
      canEdit: false,
      canCreate: false,
      canDelete: false
    },
    processPermissions: {},
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
      commit('setOrganizationalUnits', response.data.organizational_units);
      return response.data;
    },

    async loadProjectEditInfo({ commit }, id) {
      let response = await ProjectsAPI.getProjectEditInfo(id);
      commit('setViewing', response.data.project);
      commit('setOrganizationalUnits', response.data.organizational_units);
      return response.data;
    },

    async loadProjects({ commit }) {
      let response = await ProjectsAPI.getProjects();
      commit('setProjects', response.data.projects);
      return response.data.projects;
    },

    async loadProject({ commit }, id) {
      let response = await ProjectsAPI.getProject(id);
      commit('setViewing', response.data.project);
      return response.data;
    },

    async loadCanCreate({ commit }) {
      let response = await ProjectsAPI.canCreate();
      commit('setPermission', {
        name: 'canCreate',
        isAllowed: response.data.allowed
      });
    },

    async loadCanEdit({ commit }, id) {
      let response = await ProjectsAPI.canEdit(id);
      commit('setPermission', {
        name: 'canEdit',
        isAllowed: response.data.allowed
      });
    },

    async loadCanDelete({ commit }, id) {
      let response = await ProjectsAPI.canDelete(id);
      commit('setPermission', {
        name: 'canDelete',
        isAllowed: response.data.allowed
      });
    },

    async loadCanStartProcess({ commit }, { projectId, processDefinitionNameKey }) {
      let response = await ProjectsAPI.canStartProcess(projectId, processDefinitionNameKey);
      commit('setProcessPermission', {
        name: processDefinitionNameKey,
        isAllowed: response.data.allowed
      });
    },

    async create({ commit }, project) {
      let response = await ProjectsAPI.create(project);
      commit('setViewing', response.data);
      return response.data;
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

    setProcessPermission(state, { name, isAllowed }) {
      state.processPermissions[name] = isAllowed;
    },

    setPermission(state, { name, isAllowed }) {
      state.permissions[name] = isAllowed;
    },

    setViewing(state, project) {
      state.viewing = project;
    },

    setOrganizationalUnits(state, organizationalUnits) {
      state.organizationalUnits = organizationalUnits.sort((a, b) => helpers.localeSort(a, b, 'name'));
    }
  }
};
