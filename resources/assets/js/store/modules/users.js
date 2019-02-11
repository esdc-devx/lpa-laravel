import _ from 'lodash';

import UserAPI from '@api/users.js';

import { helpers } from '@/helpers.js';

import User from '@/store/models/User';

export default {
  namespaced: true,

  state: {
    // logged-in user
    current: {},
    organizationalUnits: [],
    roles: []
  },

  getters: {
    hasRole(state) {
      return function (role) {
        return !!_.find(state.current.roles, ['name_key', role]);
      };
    },

    isCurrentUser(state) {
      return function (user) {
        return !! (user && state.current.username === user.username);
      };
    }
  },

  actions: {
    async $$create({ commit }, user) {
      const response = await UserAPI.create(user);
      User.insertOrUpdate({ data: response.data });
      return response.data;
    },

    async $$update({ commit }, user) {
      const response = await UserAPI.update(user);
      User.insertOrUpdate({ data: response.data });
    },

    // Not used for now
    async $$delete({ commit }, id) {
      await UserAPI.delete(id);
      User.delete(id);
    },

    async load({ commit }) {
      const response = await UserAPI.get();
      commit('setCurrentUser', response.data);
    },

    async loadAll({ commit }) {
      const response = await UserAPI.getAll();
      User.create({ data: response.data });
    },

    async loadCreateInfo({ commit }) {
      const response = await UserAPI.getCreateInfo();
      commit('setOrganizationalUnits', response.data.organizational_units);
      commit('setRoles', response.data.roles);
    },

    async loadEditInfo({ commit }, id) {
      const response = await UserAPI.getEditInfo(id);
      User.insertOrUpdate({ data: response.data.user });
      commit('setOrganizationalUnits', response.data.organizational_units);
      commit('setRoles', response.data.roles);
    },

    async logout({ commit }) {
      await UserAPI.logout();
    },

    async search({ commit }, name) {
      return await UserAPI.search(name);
    }
  },

  mutations: {
    setCurrentUser(state, user) {
      state.current = user;
    },

    setOrganizationalUnits(state, organizationalUnits) {
      state.organizationalUnits = organizationalUnits.sort((a, b) => helpers.localeSort(a, b, 'name'));
    },

    setRoles(state, roles) {
      state.roles = roles;
    }
  }
};
