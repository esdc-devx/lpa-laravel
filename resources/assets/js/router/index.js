import Vue from 'vue';
import Router from 'vue-router';

import Home                     from '@/views/home.vue';
import ProjectList              from '@/views/project/project-list.vue';
import ProjectView              from '@/views/project/project.vue';
import ProjectEdit              from '@/views/project/project-edit.vue';
import ProjectCreate            from '@/views/project/project-create.vue';
import ProcessForm              from '@/views/process/process-form.vue';
import Process                  from '@/views/process/process.vue';
import LearningProductList      from '@/views/learning-product/learning-product-list.vue';
import LearningProductView      from '@/views/learning-product/learning-product.vue';
import LearningProductCreate    from '@/views/learning-product/learning-product-create.vue';
import LearningProductEdit      from '@/views/learning-product/learning-product-edit.vue';
import Administration           from '@/views/admin/administration.vue';
import UserList                 from '@/views/admin/user-list.vue';
import UserCreate               from '@/views/admin/user-create.vue';
import UserEdit                 from '@/views/admin/user-edit.vue';
import OrganizationalUnitList   from '@/views/admin/organizational-unit-list.vue';
import OrganizationalUnitEdit   from '@/views/admin/organizational-unit-edit.vue';
import NotFound                 from '@/views/errors/404.vue';
import Forbidden                from '@/views/errors/403.vue';

import ProjectModel             from '@/store/models/Project';
import ProcessModel             from '@/store/models/Process';
import ProcessInstanceFormModel from '@/store/models/Process-Instance-Form';
import OrganizationalUnitModel  from '@/store/models/Organizational-Unit';

import LoadStatus               from '@/store/load-status-constants';
import store                    from '@/store/';

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
  try {
    await store.dispatch('users/load');
    if (store.state.users.currentUserLoadStatus === LoadStatus.LOADING_SUCCESS) {
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

  // url do not need any modifications and the user do have access to it
  next();
}

function setLanguage(to) {
  let lang = to.params.lang;
  if (lang && lang !== store.state.language) {
    store.commit('setLanguage', lang);
  }
}

function prefixRoute(newPath) {
  if (!newPath.match(/\/en|fr/)) {
    newPath = `/${store.state.language}newPath`;
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

// @note: Vue-router needs a name property when using history mode
// so that it can differ route changes. e.g.: language change
// @note: the meta.breadcrumbs property need to follow this convention:
//            'routeName/someOtherRouteName/currentRouteName'
//        And for the title, it can only be either a value or a a translatable property
//            'base.navigation.admin_user_edit' or
//            `${store.state.users.viewing.name}`
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
    }
  },
  {
    path: '/:lang/projects/:entityId(\\d+)',
    name: 'project',
    component: ProjectView,
    meta: {
      title() {
        let project = ProjectModel.find(this.$route.params.entityId);
        return project ? project.name : '';
      },
      breadcrumbs: () => 'projects/project'
    },
    props: true
  },
  {
    path: '/:lang/projects/:entityId(\\d+)/edit',
    name: 'project-edit',
    component: ProjectEdit,
    meta: {
      title() {
        return this.trans('base.navigation.edit');
      },
      breadcrumbs: () => 'projects/project/project-edit'
    },
    props: true
  },
  {
    path: '/:lang/:entityName(projects)/:entityId(\\d+)/process/:processId(\\d+)',
    name: 'project-process',
    component: Process,
    meta: {
      title() {
        let process = ProcessModel.find(this.$route.params.processId);
        return process ? process.definition.name : '';
      },
      breadcrumbs: () => 'projects/project/project-process'
    },
    props: true
  },
  {
    path: '/:lang/:entityName(projects)/:entityId(\\d+)/process/:processId(\\d+)/form/:formId(\\d+)',
    name: 'project-process-form',
    component: ProcessForm,
    meta: {
      title() {
        let form = ProcessInstanceFormModel.find(this.$route.params.formId);
        return form ? form.definition.name : '';
      },
      breadcrumbs: () => 'projects/project/project-process/project-process-form'
    },
    props: true
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
    path: '/:lang/learning-products/:entityId(\\d+)',
    name: 'learning-product',
    component: LearningProductView,
    meta: {
      title: () => `${store.state.learningProducts.viewing.name}`,
      breadcrumbs: () => 'learning-products/learning-product'
    },
    props: true
  },
  {
    path: '/:lang/learning-products/create',
    name: 'learning-product-create',
    component: LearningProductCreate,
    meta: {
      title() {
        return this.trans('base.navigation.create');
      },
      breadcrumbs: () => 'learning-products/learning-product-create'
    }
  },
  {
    path: '/:lang/learning-products/:entityId(\\d+)/edit',
    name: 'learning-product-edit',
    component: LearningProductEdit,
    meta: {
      title() {
        return this.trans('base.navigation.edit');
      },
      breadcrumbs: () => 'learning-products/learning-product/learning-product-edit'
    },
    props: true
  },
  {
    path: '/:lang/admin',
    name: 'administration',
    component: Administration,
    meta: {
      title() {
        return this.trans('base.navigation.administration');
      },
      breadcrumbs: () => 'administration'
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
      breadcrumbs: () => 'administration/admin-user-list'
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
      breadcrumbs: () => 'administration/admin-user-list/admin-user-create'
    }
  },
  {
    path: '/:lang/admin/users/:userId(\\d+)/edit',
    name: 'admin-user-edit',
    component: UserEdit,
    meta: {
      title: () => `${store.state.users.viewing.name}`,
      breadcrumbs: () => 'administration/admin-user-list/admin-user-edit'
    },
    props: true
  },
  {
    path: '/:lang/admin/organizational-units',
    name: 'admin-organizational-unit-list',
    component: OrganizationalUnitList,
    meta: {
      title() {
        return this.trans('base.navigation.admin_organizational_unit_list');
      },
      breadcrumbs: () => 'administration/admin-organizational-unit-list'
    }
  },
  {
    path: '/:lang/admin/organizational-units/:entityId(\\d+)/edit',
    name: 'admin-organizational-unit-edit',
    component: OrganizationalUnitEdit,
    meta: {
      title() {
        let organizationalUnit = OrganizationalUnitModel.find(this.$route.params.entityId);
        return organizationalUnit ? organizationalUnit.name : '';
      },
      breadcrumbs: () => 'administration/admin-organizational-unit-list/admin-organizational-unit-edit'
    },
    props: true
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

window.router = new Router({
  routes,
  mode: 'history',
  saveScrollPosition: 'true'
});

// Router Guards
router.beforeEach(beforeProceed);

export default router;
