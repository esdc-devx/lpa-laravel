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

// This will check to see if the user is authenticated or not.
function requireAuth(to, from, next) {
  // Determines where we should send the user.
  let beforeProceed = (to, from, next) => {
    // If the user has been loaded determine where we should
    // send the user.
    if (store.getters.userLoadStatus === LoadStatus.LOADING_SUCCESS) {
      // If the user is not empty, that means there's a user
      // authenticated we allow them to continue. Otherwise, we
      // send the user back to the login page.
      if (store.getters.user !== '') {
        proceed(to, from, next);
      } else {
        // user not authenticated
        // we need to redirect to the login page since
        // we need a new csrf_token
        window.location.href = '/login';
      }
    }
  };

  // Confirms the user has been loaded
  if (store.getters.userLoadStatus !== LoadStatus.LOADING_SUCCESS) {
    // If not, load the user
    store.dispatch('loadUser')
      .then(() => {
        if (store.getters.userLoadStatus === LoadStatus.LOADING_SUCCESS) {
          beforeProceed(to, from, next);
        }
      })
      .catch(e => {
        Notify.notifyError('[router][loadUser]: ' + e);
        console.error('[router][loadUser]: ' + e);
      });
  } else {
    // User call completed, so we proceed
    beforeProceed(to, from, next);
  }
}

function proceed(to, from, next) {
  let isPathDirty = false;
  // record the language if changed
  let lang = to.params.lang;
  if (lang && lang !== store.getters.language) {
    store.dispatch('setLanguage', lang);
  }

  // append language to route
  // and remove trailling slashes if any
  let newPath = to.fullPath;

  if (!newPath.match(/\/en|fr/)) {
    newPath = '/' + store.getters.language + newPath;
    isPathDirty = true;
  }

  if (newPath.charAt(newPath.length - 1) === '/') {
    newPath = newPath.slice(0, -1);
    isPathDirty = true;
  }

  if (isPathDirty) {
    next(newPath);
  }
  next();
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
      breadcrumbs: () => `home/profile`
    }
  },
  {
    path: '/:lang/projects',
    name: 'projects',
    component: ProjectList,
    meta: {
      title: () => 'navigation.projects',
      breadcrumbs: () => `home/projects`
    }
  },
  {
    path: '/:lang/projects/:id(\\d+)',
    name: 'project-view',
    component: ProjectView,
    meta: {
      title: () => `${store.getters.project.name}`,
      breadcrumbs: () => `home/projects/project-view`
    }
  },
  {
    path: '/:lang/admin',
    name: 'admin-dashboard',
    component: AdminDashboard,
    meta: {
      title: () => 'navigation.admin_dashboard',
      breadcrumbs: () => `home/admin-dashboard`
    },
    beforeEnter(to, from, next) {
      store.dispatch('setAdminBarShown', true).then(() => next());
    }
  },
  {
    path: '/:lang/admin/users',
    name: 'admin-user-list',
    component: UserList,
    meta: {
      title: () => 'navigation.admin_user_list',
      breadcrumbs: () => `home/admin-dashboard/admin-user-list`
    },
    beforeEnter(to, from, next) {
      store.dispatch('setAdminBarShown', true).then(() => next());
    }
  },
  {
    path: '/:lang/admin/users/create',
    name: 'admin-user-create',
    component: UserCreate,
    meta: {
      title: () => 'navigation.admin_user_create',
      breadcrumbs: () => `home/admin-dashboard/admin-user-list/admin-user-create`
    },
    beforeEnter(to, from, next) {
      store.dispatch('setAdminBarShown', true).then(() => next());
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
      breadcrumbs: () => `home/{navigation.not_found}`
    }
  }
];
const router = new Router({
  routes,
  mode: 'history',
  saveScrollPosition: 'true'
});

// Router Guards
router.beforeEach(requireAuth);

router.onReady(() => {
  EventBus.$emit('App:ready');
});

export default router;
