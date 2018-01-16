import LoadStatus from '../load-status-constants';
import UserAPI from '../../api/user.js';

export const user = {
  state: {
    info: {},
    userLoadStatus: LoadStatus.NOT_LOADED
  },

  getters: {
    getUserLoadStatus(state) {
      // we need to specify a function here
      // as the store.watch() only accepts a function
      return function() {
        return state.userLoadStatus;
      };
    },

    getUser(state) {
      return state.info;
    }
  },

  actions: {
    loadUser({ commit }) {
      commit('setUserLoadStatus', LoadStatus.LOADING_STARTED);

      UserAPI.getUser()
        .then(function(response) {
          commit('setUser', response.data);
          commit('setUserLoadStatus', LoadStatus.LOADING_SUCCESS);
        })
        .catch(function() {
          commit('setUser', {});
          commit('setUserLoadStatus', LoadStatus.LOADING_FAILED);
        });
    },

    login({ commit }, data) {
      commit('setUserLoginStatus', LoadStatus.NOT_LOADED);

      UserAPI.login(data)
        .then(response => {
          commit('setUserLoginStatus', LoadStatus.LOADING_SUCCESS);
        })
        .catch((response) => {
          commit('setUserLoginStatus', LoadStatus.LOADING_FAILED);
        });
    }
  },

  mutations: {
    setUserLoadStatus(state, status) {
      state.userLoadStatus = status;
    },

    setUser(state, user) {
      state.info = user;
    }
  }
};
