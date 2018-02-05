import Vue from 'vue';
import Router from 'vue-router';

import Home from '../views/home.vue';
import Profile from '../views/profile.vue';
import ProjectList from '../views/project-list.vue';
import ProjectView from '../views/project-view.vue';
import UserList from '../views/admin/user-list.vue';
import UserCreate from '../views/admin/user-create.vue';
import NotFound from '../views/errors/not-found.vue';

import LoadStatus from '../store/load-status-constants';
import store from '../store/';

import EventBus from '../components/event-bus.js';

Vue.use(Router);

// This will check to see if the user is authenticated or not.
function requireAuth(to, from, next) {
  // Determines where we should send the user.
  let beforeProceed = (to, from, next) => {
    // If the user has been loaded determine where we should
    // send the user.
    if (store.getters.userLoadStatus() === LoadStatus.LOADING_SUCCESS) {
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
  if (store.getters.userLoadStatus() !== LoadStatus.LOADING_SUCCESS) {
    // If not, load the user
    store.dispatch('loadUser')
      .then(() => {
        if (store.getters.userLoadStatus() === LoadStatus.LOADING_SUCCESS) {
          beforeProceed(to, from, next);
        }
      })
      .catch(e => {
        console.error('[router][loadUser]: ' + e);
        alert('[router][loadUser]: ' + e);
      });
  } else {
    // User call completed, so we proceed
    beforeProceed(to, from, next);
  }
}

function proceed(to, from, next) {
  // record the language if changed
  let lang = to.params.lang;
  if (lang !== store.getters.language) {
    store.dispatch('setLanguage', lang);
  }

  // remove trailling slashes if any
  if (to.fullPath.charAt(to.fullPath.length - 1) === '/') {
    let newPath = Object.assign({}, to);
    newPath.fullPath = newPath.fullPath.slice(0, -1);
    newPath.path = newPath.path.slice(0, -1);
    next(newPath);
  } else {
    next();
  }
}

// @note: Vue-router needs a name property when using history mode
// so that it can differ route changes. e.g.: language change
const routes = [
  {
    path: '/:lang(en|fr)',
    name: "home",
    component: Home,
    meta: {
      breadcrumbs: () => '{navigation.home}'
    }
  },
  {
    path: '/:lang/profile',
    name: 'profile',
    component: Profile,
    meta: {
      breadcrumbs: () => `{navigation.home}/{navigation.profile}`
    }
  },
  {
    path: '/:lang/projects',
    name: 'projects',
    component: ProjectList,
    meta: {
      breadcrumbs: () => `{navigation.home}/{navigation.projects}`
    }
  },
  {
    path: '/:lang/projects/:id(\\d+)',
    name: 'project-view',
    component: ProjectView,
    meta: {
      breadcrumbs: () => `{navigation.home}/{navigation.projects}/${store.getters.project.name}`
    }
  },
  {
    path: '/:lang/users',
    name: 'users',
    component: UserList,
    meta: {
      breadcrumbs: () => `{navigation.home}/{navigation.users_list}`
    }
  },
  {
    path: '/:lang/users/create',
    name: 'user-create',
    component: UserCreate,
    meta: {
      breadcrumbs: () => `{navigation.home}/{navigation.users_create}`
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
      breadcrumbs: () => `{navigation.home}/{navigation.not_found}`
    }
  }
];
const router = new Router({
  routes,
  mode: 'history',
  saveScrollPosition: "true"
});

// Router Guards
router.beforeEach(requireAuth);

router.onReady(() => {
  EventBus.$emit('App:ready');
});

export default router;
