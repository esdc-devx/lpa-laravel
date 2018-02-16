import Vue from 'vue';
import Router from 'vue-router';

import Config from '../config';

import Home from '../views/home.vue';
import Profile from '../views/profile.vue';
import ProjectList from '../views/project-list.vue';
import ProjectView from '../views/project-view.vue';
import AdminDashboard from '../views/admin-dashboard.vue';
import UserList from '../views/admin/user-list.vue';
import UserCreate from '../views/admin/user-create.vue';
import NotFound from '../views/not-found.vue';

import LoadStatus from '../store/load-status-constants';
import store from '../store/';

import EventBus from '../components/event-bus.js';
import Notify from '../mixins/notify.js';

Vue.use(Router);

function beforeProceed(to, from, next) {
  requireAuth(to, from, next)
    .then(() => {
      proceed(to, from, next);
    });
}

// This will check to see if the user is authenticated or not.
function requireAuth(to, from, next) {
  return new Promise((resolve, reject) => {
    store.dispatch('loadUser')
      .then(() => {
        if (store.getters.userLoadStatus === LoadStatus.LOADING_SUCCESS) {
          return resolve();
        }
        return reject();
      });
  });
}

let isPathDirty = false;
function proceed(to, from, next) {
  // record the language if changed
  setLanguage(to);

  let to_ = Object.assign({}, to);
  let newPath = to_.fullPath;
  // prefix the route with the language if needed
  newPath = prefixRoute(newPath);
  // remove non-necessary trailling slashes at end of path
  newPath = trimTraillingSlashes(newPath);

  setupAdmin(newPath);

  if (isPathDirty) {
    next(newPath);
    isPathDirty = false;
  }
  next();
}

function setLanguage(to) {
  let lang = to.params.lang;
  if (lang && lang !== store.getters.language) {
    store.dispatch('setLanguage', lang);
  }
}

function prefixRoute(newPath) {
  if (!newPath.match(/\/en|fr/)) {
    newPath = '/' + store.getters.language + newPath;
    isPathDirty = true;
  }
  return newPath;
}

function trimTraillingSlashes(newPath) {
  if (newPath.charAt(newPath.length - 1) === '/') {
    newPath = newPath.slice(0, -1);
    isPathDirty = true;
  }
  return newPath;
}

function setupAdmin(newPath) {
  // @removeme: should get the admin rights from the user queried
  store.state.user.info.isAdmin = true;
  if (store.getters.user.isAdmin) {
    store.dispatch('toggleAdminBar', !!newPath.match(/\/admin/));
  }
}

// @note: Vue-router needs a name property when using history mode
// so that it can differ route changes. e.g.: language change
// @note: There is a direct correlation between the routes
//        and the breadcrumb since the latter will be based on
//        the route's path
const routes = [
  {
    path: '/:lang(en|fr)',
    name: 'home',
    component: Home,
    meta: {
      title: () => 'navigation.home'
    }
  },
  {
    path: '/:lang/profile',
    name: 'profile',
    component: Profile,
    meta: {
      title: () => 'navigation.profile',
      breadcrumbs: () => 'profile'
    }
  },
  {
    path: '/:lang/projects',
    name: 'projects',
    component: ProjectList,
    meta: {
      title: () => 'navigation.projects',
      breadcrumbs: () => 'projects'
    }
  },
  {
    path: '/:lang/projects/:id(\\d+)',
    name: 'project-view',
    component: ProjectView,
    meta: {
      title: () => `${store.getters.project.name}`,
      breadcrumbs: () => 'projects/project-view'
    }
  },
  {
    path: '/:lang/admin',
    name: 'admin-dashboard',
    component: AdminDashboard,
    meta: {
      title: () => 'navigation.admin_dashboard',
      breadcrumbs: () => 'admin-dashboard'
    }
  },
  {
    path: '/:lang/admin/users',
    name: 'admin-user-list',
    component: UserList,
    meta: {
      title: () => 'navigation.admin_user_list',
      breadcrumbs: () => 'admin-dashboard/admin-user-list'
    }
  },
  {
    path: '/:lang/admin/users/create',
    name: 'admin-user-create',
    component: UserCreate,
    meta: {
      title: () => 'navigation.admin_user_create',
      breadcrumbs: () => 'admin-dashboard/admin-user-list/admin-user-create'
    }
  },
  {
    path: '/:lang/logout'
  },
  // serves as a 404 handler
  {
    path: '/:lang/*',
    name: 'not-found',
    component: NotFound,
    meta: {
      title: () => 'navigation.not_found',
      breadcrumbs: () => 'not-found'
    }
  }
];
const router = new Router({
  routes,
  mode: 'history',
  saveScrollPosition: 'true'
});

// Router Guards
router.beforeEach(beforeProceed);

router.onReady(() => {
  EventBus.$emit('App:ready');
});

export default router;
