/*
|-------------------------------------------------------------------------------
| VUEX modules/user.js
|-------------------------------------------------------------------------------
| The Vuex data store for the user
*/

/**
 * status = 1 -> Loading has started
 * status = 2 -> Loading completed successfully
 * status = 3 -> Loading completed unsuccessfully
 */

import UserAPI from "../../api/user.js";

export const user = {
  state: {
    info: {},
    userLoadStatus: 0
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
      return state.user;
    }
  },

  actions: {
    loadUser({ commit }) {
      commit("setUserLoadStatus", 1);

      UserAPI.getUser()
        .then(function(response) {
          commit("setUser", response.data);
          commit("setUserLoadStatus", 2);
        })
        .catch(function() {
          commit("setUser", {});
          commit("setUserLoadStatus", 3);
        });
    },

    login({ commit }, data) {
      commit("setUserLoginStatus", 0);

      UserAPI.login(data)
        .then(response => {
          commit("setUserLoginStatus", 2);
          console.log('LOGGED IN', response);
        })
        .catch((response) => {
          commit("setUserLoginStatus", 3);
          console.error('LOGIN: ERROR', response);
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
