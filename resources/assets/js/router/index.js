import Vue from 'vue';
import Router from 'vue-router';

import Home from '../views/home.vue';
import Profile from '../views/profile.vue';
import ProjectList from '../views/project-list.vue';
import ProjectEdit from '../views/project-edit.vue';
import UserList from '../views/admin/user-list.vue';
import UserCreate from '../views/admin/user-create.vue';

import NotFound from '../views/errors/not-found.vue';

import store from '../store/';

Vue.use(Router);

// This will check to see if the user is authenticated or not.
function requireAuth(to, from, next) {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // Determines where we should send the user.
    let proceed = () => {
      // If the user has been loaded determine where we should
      // send the user.
      if (store.getters.getUserLoadStatus() === 2) {
        // If the user is not empty, that means there's a user
        // authenticated we allow them to continue. Otherwise, we
        // send the user back to the login page.
        if (store.getters.getUser !== '') {
          next();
        } else {
          // user not authenticated
          // we need to redirect to the login page since
          // we need a new csrf_token
          window.location.href = '/login';
        }
      }
    };

    // Confirms the user has been loaded
    if (store.getters.getUserLoadStatus() !== 2) {
      // If not, load the user
      store.dispatch('loadUser');

      // Watch for the user to be loaded. When it's finished, then
      // we proceed.
      store.watch(store.getters.getUserLoadStatus, function() {
        if (store.getters.getUserLoadStatus() === 2) {
          proceed();
        }
      });
    } else {
      // User call completed, so we proceed
      proceed();
    }
  }
}
const routes = [
  {
    path: '/:lang(en|fr)',
    name: 'home',
    component: Home,
    meta: {
      requiresAuth: true,
      title: 'navigation.home'
    }
  },
  {
    path: '/:lang/profile',
    name: 'profile',
    component: Profile,
    meta: {
      requiresAuth: true,
      title: 'navigation.profile'
    }
  },
  {
    path: '/:lang/projects',
    name: 'projects',
    component: ProjectList,
    meta: {
      requiresAuth: true,
      title: 'navigation.projects'
    }
  },
  {
    path: '/:lang/projects/edit/:id',
    name: 'project-edit',
    component: ProjectEdit,
    meta: {
      requiresAuth: true,
      title: 'navigation.projects_edit'
    }
  },
  {
    path: '/:lang/users',
    name: 'users',
    component: UserList,
    meta: {
      requiresAuth: true,
      title: 'navigation.users_list'
    }
  },
  {
    path: '/:lang/users/create',
    name: 'user-create',
    component: UserCreate,
    meta: {
      requiresAuth: true,
      title: 'navigation.users_create'
    }
  },
  {
    path: '/:lang/logout'
  },
  // serves as a 404 handler
  {
    path: '*',
    component: NotFound,
    meta: {
      requiresAuth: true,
      title: 'navigation.not_found'
    }
  }
];
const router = new Router({
  routes,
  mode: 'history'
});

// Router Guards
router.beforeEach((to, from, next) => {
  // make sure the user is authenticated before proceeding
  requireAuth(to, from, next);
  // record the language
  let lang = to.params.lang;
  store.dispatch('switchI18n', lang);
});
router.afterEach((to, from, next) => {
  document.title = Vue.prototype.trans('navigation.app_name') + ' - ' + Vue.prototype.trans(to.meta.title);
});

export default router;
