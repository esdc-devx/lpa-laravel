// Adds the promise polyfill for IE 11
import 'es6-promise/auto';

import Vue from 'vue';
import Vuex from 'vuex';

// Root Scope of Vuex
import { state, getters, actions, mutations } from './root';

// Imports all of the modules used in the application to build the data store.
import projects from './modules/projects.js';
import users from './modules/users.js';

Vue.use(Vuex);

export default new Vuex.Store({
  state,
  getters,
  actions,
  mutations,
  modules: {
    projects,
    users
  },
  // https://vuex.vuejs.org/en/strict.html
  strict: process.env.NODE_ENV !== 'production'
});
