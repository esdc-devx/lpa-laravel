// Adds the promise polyfill for IE 11
if (typeof Promise === 'undefined') {
  require('es6-promise/auto');
}

import Vue from 'vue';
import Vuex from 'vuex';

import VuexORM from '@vuex-orm/core';
import database from './database.js';

// Root Scope of Vuex
import { state, getters, actions, mutations } from './root';

// Imports all of the modules used in the application to build the data store.
import projectsAuth from './modules/authorizations/projects';
import learningProductsAuth from './modules/authorizations/learning-products';
import processesAuth from './modules/authorizations/processes';
import processInstanceFormsAuth from './modules/authorizations/process-instance-forms';

import lists from './modules/lists';

Vue.use(Vuex);

window.store = new Vuex.Store({
  state,
  getters,
  actions,
  mutations,
  modules: {
    authorizations: {
      namespaced: true,
      modules: {
        projects: projectsAuth,
        learningProducts: learningProductsAuth,
        processes: processesAuth,
        forms: processInstanceFormsAuth
      }
    },
    lists
  },
  plugins: [VuexORM.install(database)],
  // https://vuex.vuejs.org/en/strict.html
  strict: process.env.NODE_ENV !== 'production'
});

export default window.store;
