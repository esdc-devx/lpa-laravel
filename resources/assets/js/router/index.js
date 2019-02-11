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
import ProcessNotificationList  from '@/views/admin/process-notification-list.vue';
import ProcessNotificationEdit  from '@/views/admin/process-notification-edit.vue';
import NotFound                 from '@/views/errors/404.vue';
import Forbidden                from '@/views/errors/403.vue';

import ProjectModel             from '@/store/models/Project';
import LearningProductModel     from '@/store/models/Learning-Product';
import ProcessModel             from '@/store/models/Process';
import ProcessInstanceFormModel from '@/store/models/Process-Instance-Form';
import OrganizationalUnitModel  from '@/store/models/Organizational-Unit';
import ProcessNotificationModel from '@/store/models/Process-Notification';
import UserModel                from '@/store/models/User';

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
    await store.dispatch('entities/users/load');
    return true;
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
    newPath = `/${store.state.language}${newPath}`;
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
    return !store.getters['entities/users/hasRole']('admin');
  }
  return false;
}

// @note: Vue-router needs a name property when using history mode
// so that it can differ route changes. e.g.: language change
// @note: the meta.breadcrumbs property need to follow this convention:
//            'routeName/someOtherRouteName/currentRouteName'
//        And for the title, it can only be either a value or a a translatable property
//            'base.navigation.admin_user_edit' or
//            `${some.path.to.name}`
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
    name: 'project-list',
    component: ProjectList,
    meta: {
      title() {
        return this.trans('base.navigation.projects');
      },
      breadcrumbs: () => 'project-list'
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
      breadcrumbs: () => 'project-list/project-create'
    }
  },
  {
    path: '/:lang/projects/:entityId(\\d+)',
    name: 'project',
    component: ProjectView,
    meta: {
      title() {
        const project = ProjectModel.find(this.$route.params.entityId);
        return project ? project.name : '';
      },
      breadcrumbs: () => 'project-list/project'
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
      breadcrumbs: () => 'project-list/project/project-edit'
    },
    props: true
  },
  {
    path: '/:lang/:entityName(projects)/:entityId(\\d+)/process/:processId(\\d+)',
    name: 'project-process',
    component: Process,
    meta: {
      title() {
        const process = ProcessModel.find(this.$route.params.processId);
        return process ? process.definition.name : '';
      },
      breadcrumbs: () => 'project-list/project/project-process'
    },
    props: true
  },
  {
    path: '/:lang/:entityName(projects)/:entityId(\\d+)/process/:processId(\\d+)/form/:formId(\\d+)',
    name: 'project-process-form',
    component: ProcessForm,
    meta: {
      title() {
        const form = ProcessInstanceFormModel.find(this.$route.params.formId);
        return form ? form.definition.name : '';
      },
      breadcrumbs: () => 'project-list/project/project-process/project-process-form'
    },
    props: true
  },
  {
    path: '/:lang/learning-products',
    name: 'learning-product-list',
    component: LearningProductList,
    meta: {
      title() {
        return this.trans('base.navigation.learning_products');
      },
      breadcrumbs: () => 'learning-product-list'
    }
  },
  {
    path: '/:lang/learning-products/:entityId(\\d+)',
    name: 'learning-product',
    component: LearningProductView,
    meta: {
      title() {
        const learningProduct = LearningProductModel.find(this.$route.params.entityId);
        return learningProduct ? learningProduct.name : '';
      },
      breadcrumbs: () => 'learning-product-list/learning-product'
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
      breadcrumbs: () => 'learning-product-list/learning-product-create'
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
      breadcrumbs: () => 'learning-product-list/learning-product/learning-product-edit'
    },
    props: true
  },
  {
    path: '/:lang/:entityName(learning-products)/:entityId(\\d+)/process/:processId(\\d+)',
    name: 'learning-product-process',
    component: Process,
    meta: {
      title() {
        const process = ProcessModel.find(this.$route.params.processId);
        return process ? process.definition.name : '';
      },
      breadcrumbs: () => 'learning-product-list/learning-product/learning-product-process'
    },
    props: true
  },
  {
    path: '/:lang/:entityName(learning-products)/:entityId(\\d+)/process/:processId(\\d+)/form/:formId(\\d+)',
    name: 'learning-product-process-form',
    component: ProcessForm,
    meta: {
      title() {
        const form = ProcessInstanceFormModel.find(this.$route.params.formId);
        return form ? form.definition.name : '';
      },
      breadcrumbs: () => 'learning-product-list/learning-product/learning-product-process/learning-product-process-form'
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
    name: 'user-list',
    component: UserList,
    meta: {
      title() {
        return this.trans('base.navigation.admin_user_list');
      },
      breadcrumbs: () => 'administration/user-list'
    }
  },
  {
    path: '/:lang/admin/users/create',
    name: 'user-create',
    component: UserCreate,
    meta: {
      title() {
        return this.trans('base.navigation.create');
      },
      breadcrumbs: () => 'administration/user-list/user-create'
    }
  },
  {
    path: '/:lang/admin/users/:userId(\\d+)/edit',
    name: 'user-edit',
    component: UserEdit,
    meta: {
      title() {
        const user = UserModel.find(this.$route.params.userId);
        return user ? user.name : '';
      },
      breadcrumbs: () => 'administration/user-list/user-edit'
    },
    props: true
  },
  {
    path: '/:lang/admin/organizational-units',
    name: 'organizational-unit-list',
    component: OrganizationalUnitList,
    meta: {
      title() {
        return this.trans('base.navigation.admin_organizational_unit_list');
      },
      breadcrumbs: () => 'administration/organizational-unit-list'
    }
  },
  {
    path: '/:lang/admin/organizational-units/:entityId(\\d+)/edit',
    name: 'organizational-unit-edit',
    component: OrganizationalUnitEdit,
    meta: {
      title() {
        const organizationalUnit = OrganizationalUnitModel.find(this.$route.params.entityId);
        return organizationalUnit ? organizationalUnit.name : '';
      },
      breadcrumbs: () => 'administration/organizational-unit-list/organizational-unit-edit'
    },
    props: true
  },
  {
    path: '/:lang/admin/process-notifications',
    name: 'process-notification-list',
    component: ProcessNotificationList,
    meta: {
      title() {
        return this.trans('base.navigation.admin_process_notification_list');
      },
      breadcrumbs: () => 'administration/process-notification-list'
    }
  },
  {
    path: '/:lang/admin/process-notifications/:entityId(\\d+)/edit',
    name: 'process-notification-edit',
    component: ProcessNotificationEdit,
    meta: {
      title() {
        const processNotification = ProcessNotificationModel.find(this.$route.params.entityId);
        return processNotification ? processNotification.name : '';
      },
      breadcrumbs: () => 'administration/process-notification-list/process-notification-edit'
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
  scrollBehavior (to, from, savedPosition) {
    return { x: 0, y: 0 }
  }
});

// Router Guards
router.beforeEach(beforeProceed);

export default router;
