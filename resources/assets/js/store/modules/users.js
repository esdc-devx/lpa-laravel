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
    loadUsers({ commit }, page) {
      // commit('setUserLoadStatus', LoadStatus.LOADING_STARTED);
      return new Promise((resolve, reject) => {
        UserAPI.getUsers(page)
          .then(response => {
            commit('setUsers', response.data.data);
            commit('setPagination', response.data.meta);
            // commit('setUserLoadStatus', LoadStatus.LOADING_SUCCESS);
            resolve(response.data.data);
          })
          .catch(e => {
            commit('setUsers', {});
            // commit('setUserLoadStatus', LoadStatus.LOADING_FAILED);
            reject(e);
          });
      });
    },

    loadCurrentUser({ commit }) {
      commit('setCurrentUserLoadStatus', LoadStatus.LOADING_STARTED);
      return new Promise((resolve, reject) => {
        UserAPI.getUser()
          .then(response => {
            commit('setCurrentUser', response.data.data);
            commit('setCurrentUserLoadStatus', LoadStatus.LOADING_SUCCESS);
            resolve(response.data.data);
          })
          .catch(e => {
            commit('setCurrentUser', {});
            commit('setCurrentUserLoadStatus', LoadStatus.LOADING_FAILED);
            reject(e);
          });
      });
    },

    loadViewingUser({ commit }, id) {
      commit('setViewingUserLoadStatus', LoadStatus.LOADING_STARTED);
      return new Promise((resolve, reject) => {
        UserAPI.getUser(id)
          .then(response => {
            commit('setViewingUser', response.data.data);
            commit('setViewingUserLoadStatus', LoadStatus.LOADING_SUCCESS);
            resolve(response.data.data);
          })
          .catch(e => {
            commit('setViewingUser', {});
            commit('setViewingUserLoadStatus', LoadStatus.LOADING_FAILED);
            reject(e);
          });
      });
    },

    loadUserCreateInfo({ commit }) {
      return new Promise((resolve, reject) => {
        UserAPI.getUserCreateInfo()
          .then(response => {
            commit('setOrganizationUnits', response.data.data.organization_units);
            resolve(response.data.data.organization_units);
          })
          .catch(e => {
            commit('setOrganizationUnits', []);
            reject(e);
          });
      });
    },

    searchUser({ commit }, name) {
      return new Promise((resolve, reject) => {
        UserAPI.searchUser(name)
          .then(response => {
            resolve(response.data.data);
          })
          .catch(e => {
            reject(e);
          });
      });
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
