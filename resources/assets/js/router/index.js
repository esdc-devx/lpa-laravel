import Vue from "vue";
import Router from "vue-router";

import Home from "../views/home.vue";
import Profile from "../views/profile.vue";
import ProjectList from "../views/project-list.vue";
import ProjectEdit from "../views/project-edit.vue";
import UserList from "../views/user-list.vue";
import UserCreate from "../views/user-create.vue";

import NotFound from "../views/errors/not-found.vue";

import store from "../store/index.js";

Vue.use(Router);

/*
  This will check to see if the user is authenticated or not.
*/
function requireAuth(to, from, next) {
  /*
    Determines where we should send the user.
  */
  function proceed() {
    /*
      If the user has been loaded determine where we should
      send the user.
    */
    if (store.getters.getUserLoadStatus() == 2) {
      /*
        If the user is not empty, that means there's a user
        authenticated we allow them to continue. Otherwise, we
        send the user back to the login page.
      */
      if (store.getters.getUser != "") {
        console.log('LOGGED IN');
        next();
      } else {
        // user not authenticated
        // we need to redirect to the login page since
        // we need a new csrf_token
        console.log('redirecting...');
        window.location.href = "/login";
      }
    }
  }

  // Confirms the user has been loaded
  if (store.getters.getUserLoadStatus != 2) {
    // If not, load the user
    store.dispatch("loadUser");

    // Watch for the user to be loaded. When it's finished, then
    // we proceed.
    store.watch(store.getters.getUserLoadStatus, function() {
      if (store.getters.getUserLoadStatus() == 2) {
        proceed();
      }
    });
  } else {
    /*
      User call completed, so we proceed
    */
    proceed();
  }
}

const routes = [
  {
    path: "/",
    name: "home",
    component: Home,
    beforeEnter: requireAuth
  },
  {
    path: "/profile",
    name: "profile",
    component: Profile,
    beforeEnter: requireAuth
  },
  {
    path: "/projects",
    name: "projects",
    component: ProjectList,
    beforeEnter: requireAuth
  },
  {
    path: "/projects/edit/:id",
    name: "project-edit",
    component: ProjectEdit,
    beforeEnter: requireAuth
  },
  {
    path: "/users",
    name: "users",
    component: UserList,
    beforeEnter: requireAuth
  },
  {
    path: "/users/create",
    name: "user-create",
    component: UserCreate,
    beforeEnter: requireAuth
  },
  // serves as a 404 handler
  {
    path: '*',
    component: NotFound,
    beforeEnter: requireAuth
  }
];

export default new Router({
  routes,
  mode: "history"
});
