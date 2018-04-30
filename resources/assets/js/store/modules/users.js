import _ from 'lodash';
import LoadStatus from '../load-status-constants';
import UserAPI from '../../api/users.js';

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
    pagination: {},
    currentUserLoadStatus: LoadStatus.NOT_LOADED
  },

  getters: {
    currentUserLoadStatus(state) {
      return state.currentUserLoadStatus;
    },

    current(state) {
      return state.current;
    },

    viewing(state) {
      return state.viewing;
    },

    all(state) {
      return state.all;
    },

    organizationalUnits(state) {
      return state.organizationalUnits;
    },

    roles(state) {
      return state.roles;
    },

    hasRole(state) {
      return function (role) {
        return !!_.find(state.current.roles, ['unique_key', role]);
      };
    },

    pagination(state) {
      return state.pagination;
    }
  },

  actions: {
    async logout({ commit }) {
      await UserAPI.logout();
    },

    async loadUsers({ commit }, page) {
      let response = await UserAPI.getUsers(page);
      commit('setUsers', response.data.data);
      commit('setPagination', response.data.meta);
      return response.data.data;
    },

    async loadCurrentUser({ commit }) {
      commit('setCurrentUserLoadStatus', LoadStatus.LOADING_STARTED);
      let response;
      try {
        response = await UserAPI.getUser();
        commit('setCurrentUser', response.data.data);
        commit('setCurrentUserLoadStatus', LoadStatus.LOADING_SUCCESS);
        return response.data.data;
      } catch(e) {
        commit('setCurrentUserLoadStatus', LoadStatus.LOADING_FAILED);
        throw e;
      }
    },

    async loadViewingUser({ commit }, id) {
      let response = await UserAPI.getUser(id);
      commit('setViewingUser', response.data.data);
      return response.data.data;
    },

    async loadUserCreateInfo({ commit }) {
      let response = await UserAPI.getUserCreateInfo();
      commit('setOrganizationalUnits', response.data.data.organizational_units);
      commit('setRoles', response.data.data.roles);
      return response.data.data;
    },

    async loadUserEditInfo({ commit }, id) {
      let response = await UserAPI.getUserEditInfo(id);
      commit('setViewingUser', response.data.data.user);
      commit('setOrganizationalUnits', response.data.data.organizational_units);
      commit('setRoles', response.data.data.roles);
      return response.data.data;
    },

    // CRUD methods
    async createUser({ commit }, user) {
      await UserAPI.createUser(user);
    },

    async searchUser({ commit }, name) {
      let response = await UserAPI.searchUser(name);
      return response.data.data;
    },

    async updateUser({ commit }, user) {
      await UserAPI.updateUser(user);
    },

    async deleteUser({ commit }, id) {
      await UserAPI.deleteUser(id);
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

    setViewingUser(state, user) {
      state.viewing = user;
    },

    setOrganizationalUnits(state, organizationalUnits) {
      state.organizationalUnits = organizationalUnits;
    },

    setRoles(state, roles) {
      state.roles = roles;
    },

    setPagination(state, pagination) {
      state.pagination = pagination;
    }
  }
};
