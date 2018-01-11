// Adds the promise polyfill for IE 11
import 'es6-promise/auto';

// Imports Vue and Vuex
import Vue from "vue";
import Vuex from "vuex";

// Root Scope of VUEX
import { state, getters, actions, mutations } from './root';

// Imports all of the modules used in the application to build the data store.
import auth from '../api/auth';
import { learningProjects } from './modules/learning-projects.js';
import { user } from './modules/user.js';

// Initializes Vuex on Vue.
Vue.use(Vuex);

export default new Vuex.Store({
  state,
  getters,
  actions,
  mutations,
  modules: {
    auth,
    learningProjects,
    user
  }
});
