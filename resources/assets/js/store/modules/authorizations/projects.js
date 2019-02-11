import Vue from 'vue';

import ProjectsAPI from '@api/projects';

import Project from '@/store/models/Project';

export default {
  namespaced: true,

  state: {
    permissions: {},
    processPermissions: {}
  },

  getters: {},

  actions: {
    async loadCanCreate({ commit }) {
      let response = await ProjectsAPI.canCreate();
      // set this one locally
      // since it does not refer directly to a project model
      commit('setPermission', {
        name: 'canCreate',
        isAllowed: response.data.allowed
      });
    },

    async loadCanEdit({ commit }, id) {
      let response = await ProjectsAPI.canEdit(id);
      commit('setModelPermission', {
        id,
        name: 'canEdit',
        isAllowed: response.data.allowed
      });
    },

    async loadCanDelete({ commit }, id) {
      let response = await ProjectsAPI.canDelete(id);
      commit('setModelPermission', {
        id,
        name: 'canDelete',
        isAllowed: response.data.allowed
      });
    },

    async loadCanStartProcess({ commit }, { projectId, processDefinitionNameKey }) {
      let response = await ProjectsAPI.canStartProcess(projectId, processDefinitionNameKey);
      commit('setProcessPermission', {
        name: processDefinitionNameKey,
        isAllowed: response.data.allowed
      });
    }
  },

  mutations: {
    setPermission(state, { name, isAllowed }) {
      Vue.set(state.permissions, name, isAllowed);
    },
    setModelPermission(state, { id, name, isAllowed }) {
      // we cannot use insertOrUpdate since it doesn't support a data function yet
      if (Project.find(id)) {
        Project.update({
          where: id,
          data(project) {
            Vue.set(project.permissions, name, isAllowed);
          }
        });
      } else {
        Project.insert({
          data: {
            id,
            permissions: {
              [name]: isAllowed
            }
          }
        });
      }
    },
    setProcessPermission(state, { name, isAllowed }) {
      state.processPermissions[name] = isAllowed;
    }
  }
};
