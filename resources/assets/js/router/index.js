import Vue from 'vue';
import Router from 'vue-router';
import Config from '@/config';

import Home                 from '@/views/home.vue';
import Profile              from '@/views/profile.vue';
import ProjectList          from '@/views/project/project-list.vue';
import ProjectView          from '@/views/project/project.vue';
import ProjectEdit          from '@/views/project/project-edit.vue';
import ProjectCreate        from '@/views/project/project-create.vue';
import ProjectProcess       from '@/views/project/project-process.vue';
import ProjectProcessForm   from '@/views/project/project-process-form.vue';
import LearningProductList  from '@/views/learning-product/learning-product-list.vue';
import AdminDashboard       from '@/views/admin/dashboard.vue';
import UserList             from '@/views/admin/user-list.vue';
import UserCreate           from '@/views/admin/user-create.vue';
import UserEdit             from '@/views/admin/user-edit.vue';
import NotFound             from '@/views/errors/404.vue';
import Forbidden            from '@/views/errors/403.vue';

import LoadStatus           from '@/store/load-status-constants';
import store                from '@/store/';
import HttpStatusCodes      from '@axios/http-status-codes';

Vue.use(Router);

let forceProceed = false;

async function beforeProceed(to, from, next) {
  // Here's the flow of events:
  //  1. authenticate user
  //  2. if authenticated, url may be standardized or changed to a forbidden url, else just proceed with requested page
  //  3. if url was altered, forceProceed to the result url
  if (forceProceed) {
    next();
    // reset flag
    forceProceed = false;
    return;
  }

  let isAuth = await isAuthenticated(to, from, next);
  if (isAuth) {
    proceed(to, from, next);
  }
}

// This will check to see if the user is authenticated or not.
async function isAuthenticated(to, from, next) {
  let response;
  try {
    response = await store.dispatch('users/loadCurrentUser');
    if (store.getters['users/currentUserLoadStatus'] === LoadStatus.LOADING_SUCCESS) {
      return true;
    }
  } catch (e) {
    Vue.$log.error(`[router][isAuthenticated] ${e}`);
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

  // check if user has rights to view the requested page
  let pathForbidden = isPathForbidden(newPath);

  // user has no access rights for the requested page, redirect to forbidden page
  if (pathForbidden) {
    forceProceed = true;
    //@note: when matching a route with a '*' as path,
    // vue-router needs to know what the dynamic params are
    // and since * is not a understandable word, it tries to find the param at position '0'
    router.replace({name: 'forbidden', params: {'0': newPath}});
    return;
  }

  // url has been cleaned up, so redirect to new url
  if (isPathDirty) {
    forceProceed = true;
    next(newPath);
    return;
  }

  setupAdmin(newPath);

  // url do not need any modifications and the user do have access to it
  next();
}

function setLanguage(to) {
  let lang = to.params.lang;
  if (lang && lang !== store.getters.language) {
    store.dispatch('setLanguage', lang).then(() => {
      store.dispatch('hideMainLoading');
    });
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

function isPathForbidden(newPath) {
  if (!!newPath.match(/\/admin/)) {
    return !store.getters['users/hasRole']('admin');
  }
  return false;
}

function setupAdmin(newPath) {
  // make sure that the admin bar is opened when browsing an admin url
  store.dispatch('toggleAdminBar', !!newPath.match(/\/admin/));
}
// @note: Vue-router needs a name property when using history mode
// so that it can differ route changes. e.g.: language change
// @note: the meta.breadcrumbs property need to follow this convention:
//            'routeName/someOtherRouteName/currentRouteName'
//        And for the title, it can only be either a value or a a translatable property
//            'base.navigation.admin_user_edit' or
//            `${store.getters['users/viewing'].name}`
const routes = [
  {
    path: '/:lang(en|fr)',
    name: 'home',
    component: Home,
    meta: {
      title() {
        return this.trans('base.navigation.home');
      }
    }
  },
  {
    path: '/:lang/projects',
    name: 'projects',
    component: ProjectList,
    meta: {
      title() {
        return this.trans('base.navigation.projects');
      },
      breadcrumbs: () => 'projects'
    }
  },
  {
    path: '/:lang/projects/create',
    name: 'project-create',
    component: ProjectCreate,
    meta: {
      title() {
        return this.trans('base.navigation.create');
      },
      breadcrumbs: () => 'projects/project-create'
    },
    beforeEnter: async (to, from, next) => {
      try {
        // @note: corresponding hideMainLoading will be done
        // in the component itself
        store.dispatch('showMainLoading');
        let canCreateProject = await store.dispatch('projects/canCreateProject');
        if (canCreateProject) {
          next();
        } else {
          router.replace({ name: 'forbidden', params: { '0': to.path } });
        }
      } catch (e) {
        // Exception handled by interceptor
        if (!e.response) {
          throw e;
        }
      }
    }
  },
  {
    path: '/:lang/projects/:projectId(\\d+)',
    name: 'project',
    component: ProjectView,
    meta: {
      title: () => `${store.getters['projects/viewing'].name}`,
      breadcrumbs: () => 'projects/project'
    },
    beforeEnter: async (to, from, next) => {
      try {
        // @note: corresponding hideMainLoading will be done
        // in the component itself
        store.dispatch('showMainLoading');
        await store.dispatch('projects/loadProject', to.params.projectId);
        next();
      } catch (e) {
        // Exception handled by interceptor
        if (!e.response) {
          throw e;
        }
      }
    }
  },
  {
    path: '/:lang/projects/:projectId(\\d+)/edit',
    name: 'project-edit',
    component: ProjectEdit,
    meta: {
      title() {
        return this.trans('base.navigation.edit');
      },
      breadcrumbs: () => 'projects/project/project-edit'
    },
    beforeEnter: async (to, from, next) => {
      try {
        // @note: corresponding hideMainLoading will be done
        // in the component itself
        store.dispatch('showMainLoading');
        let canEditProject = await store.dispatch(
          'projects/canEditProject',
          to.params.projectId
        );
        if (canEditProject) {
          next();
        } else {
          router.replace({ name: 'forbidden', params: { '0': to.path } });
        }
      } catch (e) {
        // Exception handled by interceptor
        if (!e.response) {
          throw e;
        }
      }
    }
  },
  {
    path: '/:lang/projects/:projectId(\\d+)/process/:processId(\\d+)',
    name: 'project-process',
    component: ProjectProcess,
    meta: {
      title: () => `${store.getters['processes/viewing'].definition.name}`,
      breadcrumbs: () => 'projects/project/project-process'
    },
    beforeEnter: async (to, from, next) => {
      try {
        // @note: corresponding hideMainLoading will be done
        // in the component itself
        store.dispatch('showMainLoading');
        await store.dispatch('projects/loadProject', to.params.projectId);
        await store.dispatch('processes/loadInstance', to.params.processId);
        next();
      } catch (e) {
        // Exception handled by interceptor
        if (!e.response) {
          throw e;
        }
      }
    }
  },
  {
    path: '/:lang/projects/:projectId(\\d+)/process/:processId(\\d+)/form/:formId(\\d+)',
    name: 'project-process-form',
    component: ProjectProcessForm,
    meta: {
      title: () => `${store.getters['processes/viewingFormInfo'].definition.name}`,
      breadcrumbs: () => 'projects/project/project-process/project-process-form'
    },
    beforeEnter: async (to, from, next) => {
      try {
        // @note: corresponding hideMainLoading will be done
        // in the component itself
        store.dispatch('showMainLoading');
        await store.dispatch('projects/loadProject', to.params.projectId);
        await store.dispatch('processes/loadInstance', to.params.processId);
        await store.dispatch('processes/loadInstanceForm', to.params.formId);
        next();
      } catch (e) {
        // Exception handled by interceptor
        if (!e.response) {
          throw e;
        }
      }
    }
  },
  {
    path: '/:lang/learning-products',
    name: 'learning-products',
    component: LearningProductList,
    meta: {
      title() {
        return this.trans('base.navigation.learning_products');
      },
      breadcrumbs: () => 'learning-products'
    }
  },
  {
    path: '/:lang/admin',
    name: 'admin-dashboard',
    component: AdminDashboard,
    meta: {
      title() {
        return this.trans('base.navigation.admin_dashboard');
      },
      breadcrumbs: () => 'admin-dashboard'
    }
  },
  {
    path: '/:lang/admin/users',
    name: 'admin-user-list',
    component: UserList,
    meta: {
      title() {
        return this.trans('base.navigation.admin_user_list');
      },
      breadcrumbs: () => 'admin-dashboard/admin-user-list'
    }
  },
  {
    path: '/:lang/admin/users/create',
    name: 'admin-user-create',
    component: UserCreate,
    meta: {
      title() {
        return this.trans('base.navigation.admin_user_create');
      },
      breadcrumbs: () => 'admin-dashboard/admin-user-list/admin-user-create'
    }
  },
  {
    path: '/:lang/admin/users/edit/:userId(\\d+)',
    name: 'admin-user-edit',
    component: UserEdit,
    meta: {
      title: () => `${store.getters['users/viewing'].name}`,
      breadcrumbs: () => 'admin-dashboard/admin-user-list/admin-user-edit'
    },
    beforeEnter: async (to, from, next) => {
      try {
        // @note: corresponding hideMainLoading will be done
        // in the component itself
        store.dispatch('showMainLoading');
        await store.dispatch('users/loadUserEditInfo', to.params.userId);
        next();
      } catch (e) {
        // Exception handled by interceptor
        if (!e.response) {
          throw e;
        }
      }
    }
  },
  {
    path: '/:lang/logout'
  },
  // serves as a 404 handler
  {
    path: '*',
    name: 'not-found',
    component: NotFound,
    meta: {
      title() {
        return this.trans('errors.not_found');
      },
      breadcrumbs: () => 'not-found'
    }
  },
  // serves as 403 handler for cases where we want to keep the url
  {
    path: '*',
    name: 'forbidden',
    component: Forbidden,
    meta: {
      title() {
        return this.trans('errors.forbidden');
      },
      breadcrumbs: () => 'forbidden'
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
