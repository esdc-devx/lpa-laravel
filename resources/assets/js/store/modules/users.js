import LoadStatus from '../load-status-constants';
import UserAPI from '../../api/user.js';

export default {
  namespaced: true,

  state: {
    // logged-in user
    current: {},
    // user being viewed
    viewing: {},
    all: [],
    organizationUnits: [],
    pagination: {},
    currentUserLoadStatus: LoadStatus.NOT_LOADED,
    viewingUserLoadStatus: LoadStatus.NOT_LOADED
  },

  getters: {
    currentUserLoadStatus(state) {
      return state.currentUserLoadStatus;
    },

    viewingUserLoadStatus(state) {
      return state.viewingUserLoadStatus;
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

    organizationUnits(state) {
      return state.organizationUnits;
    },

    pagination(state) {
      return state.pagination;
    }
  },

  actions: {
    async loadUsers({ commit }, page) {
      // commit('setUserLoadStatus', LoadStatus.LOADING_STARTED);
      let response;
      try {
        response = await UserAPI.getUsers(page);
        commit('setUsers', response.data.data);
        commit('setPagination', response.data.meta);
        // commit('setUserLoadStatus', LoadStatus.LOADING_SUCCESS);
        return response.data.data;
      } catch(e) {
        commit('setUsers', {});
        // commit('setUserLoadStatus', LoadStatus.LOADING_FAILED);
        throw e;
      }
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
        commit('setCurrentUser', {});
        commit('setCurrentUserLoadStatus', LoadStatus.LOADING_FAILED);
        throw e;
      }
    },

    async loadViewingUser({ commit }, id) {
      commit('setViewingUserLoadStatus', LoadStatus.LOADING_STARTED);
      let response;
      try {
        response = await UserAPI.getUser(id);
        commit('setViewingUser', response.data.data);
        commit('setViewingUserLoadStatus', LoadStatus.LOADING_SUCCESS);
        return response.data.data;
      } catch(e) {
        commit('setViewingUser', {});
        commit('setViewingUserLoadStatus', LoadStatus.LOADING_FAILED);
        throw e;
      }
    },

    async loadUserCreateInfo({ commit }) {
      let response;
      try {
        response = await UserAPI.getUserCreateInfo();
        commit('setOrganizationUnits', response.data.data.organization_units);
        return response.data.data.organization_units;
      } catch(e) {
        commit('setOrganizationUnits', []);
        throw e;
      }
    },

    async searchUser({ commit }, name) {
      let response;
      try {
        response = await UserAPI.searchUser(name);
        return response.data.data;
      } catch(e) {
        throw e;
      }
    },

    async createUser({ commit }, user) {
      let response;
      try {
        response = await UserAPI.createUser(user);
        return;
      } catch(e) {
        throw e;
      }
    },

    async updateUser({ commit }, user) {
      let response;
      try {
        response = await UserAPI.updateUser(user);
        return;
      } catch(e) {
        throw e;
      }
    },

    async deleteUser({ commit }, id) {
      let response;
      try {
        response = await UserAPI.deleteUser(id);
        return;
      } catch(e) {
        throw e;
      }
    }
  },

  mutations: {
    setCurrentUserLoadStatus(state, status) {
      state.currentUserLoadStatus = status;
    },

    setViewingUserLoadStatus(state, status) {
      state.viewingUserLoadStatus = status;
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

    setOrganizationUnits(state, organizationUnits) {
      state.organizationUnits = organizationUnits;
    },

    setPagination(state, pagination) {
      state.pagination = pagination;
    }
  }
};
