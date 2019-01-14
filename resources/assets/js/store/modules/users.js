import _ from 'lodash';
import LoadStatus from '../load-status-constants';
import UserAPI from '@api/users.js';
import { helpers } from '@/helpers.js';

export default {
  namespaced: true,

  state: {
    // logged-in user
    current: {},
    // user being viewed
    viewing: {},
    all: [],
    organizationalUnits: [],
    roles: [],
    currentUserLoadStatus: LoadStatus.NOT_LOADED
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
    async create({ commit }, user) {
      await UserAPI.create(user);
    },

    async search({ commit }, name) {
      return await UserAPI.search(name);
    },

    async update({ commit }, user) {
      await UserAPI.update(user);
    },

    async delete({ commit }, id) {
      await UserAPI.delete(id);
    },

    async load({ commit }) {
      commit('setCurrentUserLoadStatus', LoadStatus.LOADING_STARTED);
      try {
        let response = await UserAPI.getUser();
        commit('setCurrentUser', response.data);
        commit('setCurrentUserLoadStatus', LoadStatus.LOADING_SUCCESS);
      } catch (e) {
        commit('setCurrentUserLoadStatus', LoadStatus.LOADING_FAILED);
        throw e;
      }
    },

    async loadAll({ commit }) {
      let response = await UserAPI.getUsers();
      commit('setUsers', response.data);
    },

    async loadCreateInfo({ commit }) {
      let response = await UserAPI.getUserCreateInfo();
      commit('setOrganizationalUnits', response.data.organizational_units);
      commit('setRoles', response.data.roles);
    },

    async loadEditInfo({ commit }, id) {
      let response = await UserAPI.getUserEditInfo(id);
      commit('setViewing', response.data.user);
      commit('setOrganizationalUnits', response.data.organizational_units);
      commit('setRoles', response.data.roles);
    },

    async logout({ commit }) {
      await UserAPI.logout();
    }
  },

  mutations: {
    setCurrentUserLoadStatus(state, status) {
      state.currentUserLoadStatus = status;
    },

    setUsers(state, users) {
      state.all = users;
    },

    setCurrentUser(state, user) {
      state.current = user;
    },

    setViewing(state, user) {
      state.viewing = user;
    },

    setOrganizationalUnits(state, organizationalUnits) {
      state.organizationalUnits = organizationalUnits.sort((a, b) => helpers.localeSort(a, b, 'name'));
    },

    setRoles(state, roles) {
      state.roles = roles;
    }
  }
};
