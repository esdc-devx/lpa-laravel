import ProjectsAPI from '@api/projects';

import Project from '../models/Project';

import { helpers } from '@/helpers.js';

export default {
  namespaced: true,

  state: {
    organizationalUnits: []
  },

  actions: {
    async $$create({ commit }, project) {
      let response = await ProjectsAPI.create(project);
      return response.data;
    },

    async $$update({ commit }, project) {
      await ProjectsAPI.update(project);
    },

    async $$delete({ commit }, id) {
      await ProjectsAPI.delete(id);
    },

    async load({ commit }, id) {
      let response = await ProjectsAPI.get(id);
      Project.insertOrUpdate({ data: response.data.project });
    },

    async loadAll({ commit }) {
      let response = await ProjectsAPI.getAll();
      Project.create({ data: response.data.projects });
    },

    async loadCreateInfo({ commit }) {
      let response = await ProjectsAPI.getCreateInfo();
      commit('setOrganizationalUnits', response.data.organizational_units);
    },

    async loadEditInfo({ commit }, id) {
      let response = await ProjectsAPI.getEditInfo(id);
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
