/*
|-------------------------------------------------------------------------------
| VUEX modules/users.js
|-------------------------------------------------------------------------------
| The Vuex data store for the users
*/

/**
 * status = 1 -> Loading has started
 * status = 2 -> Loading completed successfully
 * status = 3 -> Loading completed unsuccessfully
 */

import UserAPI from "../api/user.js";

export const users = {
  /*
    Defines the state being monitored for the module.
  */
  state: {
    user: {},
    userLoadStatus: 0,
    userUpdateStatus: 0
  },

  /*
    Defines the actions used to retrieve the data.
  */
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

    /*
      Edits a user
    */
    editUser({ commit, state, dispatch }, data) {
      commit("setUserUpdateStatus", 1);

      UserAPI.putUpdateUser(
        data.public_visibility,
        data.favorite_coffee,
        data.flavor_notes,
        data.city,
        data.state
      )
        .then(response => {
          commit("setUserUpdateStatus", 2);
          dispatch("loadUser");
        })
        .catch(() => {
          commit("setUserUpdateStatus", 3);
        });
    },

    /*
      Login a user.
    */
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
    },

    /*
      Logs out a user and clears the status and user pieces of
      state.
    */
    logout({ commit }) {
      commit("setUserLoadStatus", 0);
      commit("setUser", {});
      UserAPI.logout()
        .then(response => {
          console.log('LOGGED OUT', response);
        })
        .catch((response) => {
          console.error('LOGOUT: ERROR', r);
        });
    }
  },

  /*
    Defines the mutations used
  */
  mutations: {
    /*
      Sets the user load status
    */
    setUserLoadStatus(state, status) {
      state.userLoadStatus = status;
    },

    /*
      Sets the user
    */
    setUser(state, user) {
      state.user = user;
    },

    /*
      Sets the user update status
    */
    setUserUpdateStatus(state, status) {
      state.userUpdateStatus = status;
    }
  },

  /*
    Defines the getters used by the module.
  */
  getters: {
    /*
      Returns the user load status.
    */
    getUserLoadStatus(state) {
      return function() {
        return state.userLoadStatus;
      };
    },

    /*
      Returns the user.
    */
    getUser(state) {
      return state.user;
    },

    /*
      Gets the user update status
    */
    getUserUpdateStatus(state, status) {
      return state.userUpdateStatus;
    }
  }
};
