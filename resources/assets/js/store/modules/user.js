import LoadStatus from '../load-status-constants';
import UserAPI from '../../api/user.js';

export default {
  state: {
    info: {},
    userLoadStatus: LoadStatus.NOT_LOADED
  },

  getters: {
    userLoadStatus(state) {
      // @note: we need to specify a function here
      // as the store.watch() only accepts a function
      return function() {
        return state.userLoadStatus;
      };
    },

    user(state) {
      return state.info;
    }
  },

  actions: {
    loadUser({ commit }) {
      commit('setUserLoadStatus', LoadStatus.LOADING_STARTED);

      return new Promise((resolve, reject) => {
        UserAPI.getUser()
          .then(response => {
            commit('setUser', response.data.data);
            commit('setUserLoadStatus', LoadStatus.LOADING_SUCCESS);
            resolve(response.data.data);
          })
          .catch(e => {
            commit('setUser', {});
            commit('setUserLoadStatus', LoadStatus.LOADING_FAILED);
            reject(e);
          });
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
