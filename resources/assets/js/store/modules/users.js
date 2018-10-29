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
        return !!_.find(state.current.roles, ['name_key', role]);
      };
    },

    isCurrentUser(state) {
      return function (user) {
        return !! (user && state.current.username === user.username);
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
      commit('setUsers', response.data);
      commit('setPagination', response.meta);
      return response.data;
    },

    async loadCurrentUser({ commit }) {
      commit('setCurrentUserLoadStatus', LoadStatus.LOADING_STARTED);
      let response;
      try {
        response = await UserAPI.getUser();
        commit('setCurrentUser', response.data);
        commit('setCurrentUserLoadStatus', LoadStatus.LOADING_SUCCESS);
        return response.data;
      } catch (e) {
        commit('setCurrentUserLoadStatus', LoadStatus.LOADING_FAILED);
        throw e;
      }
    },

    async loadViewingUser({ commit }, id) {
      let response = await UserAPI.getUser(id);
      commit('setViewing', response.data);
      return response.data;
    },

    async loadUserCreateInfo({ commit }) {
      let response = await UserAPI.getUserCreateInfo();
      commit('setOrganizationalUnits', response.data.organizational_units);
      commit('setRoles', response.data.roles);
      return response.data;
    },

    async loadUserEditInfo({ commit }, id) {
      let response = await UserAPI.getUserEditInfo(id);
      commit('setViewing', response.data.user);
      commit('setOrganizationalUnits', response.data.organizational_units);
      commit('setRoles', response.data.roles);
      return response.data;
    },

    // CRUD methods
    async create({ commit }, user) {
      await UserAPI.create(user);
    },

    async search({ commit }, name) {
      let response = await UserAPI.search(name);
      return response.data;
    },

    async update({ commit }, user) {
      await UserAPI.update(user);
    },

    async delete({ commit }, id) {
      await UserAPI.delete(id);
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
    },

    setPagination(state, pagination) {
      state.pagination = pagination;
    }
  }
};
