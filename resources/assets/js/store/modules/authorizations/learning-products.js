import Vue from 'vue';

import LearningProductAPI from '@api/learning-products';

import LearningProduct from '@/store/models/Learning-Product';

export default {
  namespaced: true,

  state: {
    permissions: {},
    processPermissions: {}
  },

  getters: {},

  actions: {
    async loadCanCreate({ commit }) {
      let response = await LearningProductAPI.canCreate();
      // set this one locally
      // since it does not refer directly to a project model
      commit('setPermission', {
        name: 'canCreate',
        isAllowed: response.data.allowed
      });
    },

    async loadCanEdit({ commit }, id) {
      let response = await LearningProductAPI.canEdit(id);
      commit('setModelPermission', {
        id,
        name: 'canEdit',
        isAllowed: response.data.allowed
      });
    },

    async loadCanDelete({ commit }, id) {
      let response = await LearningProductAPI.canDelete(id);
      commit('setModelPermission', {
        id,
        name: 'canDelete',
        isAllowed: response.data.allowed
      });
    },

    async loadCanStartProcess({ commit }, { learningProductId, processDefinitionNameKey }) {
      let response = await LearningProductAPI.canStartProcess(learningProductId, processDefinitionNameKey);
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
      if (LearningProduct.find(id)) {
        LearningProduct.update({
          where: id,
          data(learningProduct) {
            Vue.set(learningProduct.permissions, name, isAllowed);
          }
        });
      } else {
        LearningProduct.insert({
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
