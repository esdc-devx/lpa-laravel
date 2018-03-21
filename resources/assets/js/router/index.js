import Vue from 'vue';
import Router from 'vue-router';

import Config from '../config';

import Home from '../views/home.vue';
import Profile from '../views/profile.vue';
import ProjectList from '../views/project-list.vue';
import ProjectView from '../views/project-view.vue';
import AdminDashboard from '../views/admin/dashboard.vue';
import UserList from '../views/admin/user-list.vue';
import UserCreate from '../views/admin/user-create.vue';
import UserEdit from '../views/admin/user-edit.vue';
import NotFound from '../views/errors/404.vue';
import Forbidden from '../views/errors/403.vue';

import LoadStatus from '../store/load-status-constants';
import store from '../store/';

import EventBus from '../helpers/event-bus.js';
import Notify from '../mixins/notify.js';

Vue.use(Router);

async function beforeProceed(to, from, next) {
  let isAuthorized = await requireAuth(to, from, next);
  if (isAuthorized) {
    proceed(to, from, next);
  }
}

// This will check to see if the user is authenticated or not.
async function requireAuth(to, from, next) {
  let response;
  try {
    response = await store.dispatch('users/loadCurrentUser');
    if (store.getters['users/currentUserLoadStatus'] === LoadStatus.LOADING_SUCCESS) {
      return true;
    }
  } catch(e) {
    Vue.$log.error(`[router][requireAuth] ${e}`);
    return false;
  }
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
  store.state.users.current.isAdmin = true;
  if (store.getters['users/current'].isAdmin) {
    store.dispatch('toggleAdminBar', !!newPath.match(/\/admin/));
  }
}

// @note: Vue-router needs a name property when using history mode
// so that it can differ route changes. e.g.: language change
// @note: the meta.breadcrumbs property need to follow this convention:
//            'routeName/someOtherRouteName/currentRouteName'
//        And for the title, it can only be either a value or a a translatable property
//            'navigation.admin_user_edit' or
//            `${store.getters['users/viewing'].name}`
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
      title: () => `${store.getters['projects/viewing'].name}`,
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
    path: '/:lang/admin/users/edit/:id(\\d+)',
    name: 'admin-user-edit',
    component: UserEdit,
    meta: {
      title: () => `${store.getters['users/viewing'].name}`,
      breadcrumbs: () => 'admin-dashboard/admin-user-list/admin-user-edit'
    }
  },
  {
    path: '/:lang/logout'
  },
  {
    path: '/:lang/403',
    name: 'forbidden',
    component: Forbidden,
    meta: {
      title: () => 'navigation.forbidden',
      breadcrumbs: () => 'forbidden'
    }
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

export default router;
