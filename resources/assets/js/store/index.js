// Adds the promise polyfill for IE 11
if (typeof Promise === 'undefined') {
  require('es6-promise/auto');
}

import Vue from 'vue';
import Vuex from 'vuex';

// Root Scope of Vuex
import { state, getters, actions, mutations } from './root';

// Imports all of the modules used in the application to build the data store.
import projects from './modules/projects.js';
import processes from './modules/processes.js';
import users from './modules/users.js';
import learningProducts from './modules/learning-products.js';

Vue.use(Vuex);

export default new Vuex.Store({
  state,
  getters,
  actions,
  mutations,
  modules: {
    projects,
    processes,
    users,
    learningProducts
  },
  // https://vuex.vuejs.org/en/strict.html
  strict: process.env.NODE_ENV !== 'production'
});
