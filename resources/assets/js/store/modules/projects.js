import ProjectsAPI from '@api/projects';

import Project from '@/store/models/Project';

import { helpers } from '@/helpers.js';

export default {
  namespaced: true,

  state: {
    organizationalUnits: []
  },

  actions: {
    async $$create({ commit }, project) {
      const response = await ProjectsAPI.create(project);
      Project.create({ data: response.data });
      return response.data;
    },

    async $$update({ commit }, project) {
      const response = await ProjectsAPI.update(project);
      Project.insertOrUpdate({ data: response.data });
    },

    async $$delete({ commit }, id) {
      await ProjectsAPI.delete(id);
      Project.delete(id);
    },

    async load({ commit }, id) {
      const response = await ProjectsAPI.get(id);
      Project.insertOrUpdate({ data: response.data.project });
    },

    async loadAll({ commit }) {
      const response = await ProjectsAPI.getAll();
      Project.create({ data: response.data.projects });
    },

    async loadCreateInfo({ commit }) {
      const response = await ProjectsAPI.getCreateInfo();
      commit('setOrganizationalUnits', response.data.organizational_units);
    },

    async loadEditInfo({ commit }, id) {
      const response = await ProjectsAPI.getEditInfo(id);
      Project.insertOrUpdate({ data: response.data.project });
      commit('setOrganizationalUnits', response.data.organizational_units);
    }
  },

  mutations: {
    setOrganizationalUnits(state, organizationalUnits) {
      state.organizationalUnits = organizationalUnits.sort((a, b) => helpers.localeSort(a, b, 'name'));
    }
  }
};
