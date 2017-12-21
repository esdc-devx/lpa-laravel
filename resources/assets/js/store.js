/*
|-------------------------------------------------------------------------------
| VUEX store.js
|-------------------------------------------------------------------------------
| Builds the data store from all of the modules for the Roast app.
*/

/*
  Imports Vue and Vuex
*/
import Vue from "vue";
import Vuex from "vuex";

/*
  Initializes Vuex on Vue.
*/
Vue.use(Vuex);

/*
  Imports all of the modules used in the application to build the data store.
*/
import { learningProjects } from './modules/learning-projects.js';
import { users } from './modules/users.js';

/*
  Exports our data store.
*/
export default new Vuex.Store({
  modules: {
    learningProjects,
    users
  }
});
