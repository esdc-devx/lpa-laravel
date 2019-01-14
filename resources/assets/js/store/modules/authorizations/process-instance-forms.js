import Vue from 'vue';

import ProcessInstanceFormAPI from '@api/process-instance-forms';

import ProcessInstanceForm from '@/store/models/Process-Instance-Form';

export default {
  namespaced: true,

  state: {},

  getters: {},

  actions: {
    async loadCanEditForm({ commit }, formId) {
      let response = await ProcessInstanceFormAPI.canEditForm(formId);
      commit('setModelPermission', {
        id: formId,
        name: 'canEditForm',
        isAllowed: response.data.allowed
      });
    },
    async loadCanClaimForm({ commit }, formId) {
      let response = await ProcessInstanceFormAPI.canClaimForm(formId);
      commit('setModelPermission', {
        id: formId,
        name: 'canClaimForm',
        isAllowed: response.data.allowed
      });
    },
    async loadCanUnclaimForm({ commit }, formId) {
      let response = await ProcessInstanceFormAPI.canUnclaimForm(formId);
      commit('setModelPermission', {
        id: formId,
        name: 'canUnclaimForm',
        isAllowed: response.data.allowed
      });
    },
    async loadCanSubmitForm({ commit }, formId) {
      let response = await ProcessInstanceFormAPI.canSubmitForm(formId);
      commit('setModelPermission', {
        id: formId,
        name: 'canSubmitForm',
        isAllowed: response.data.allowed
      });
    },
    async loadCanReleaseForm({ commit }, formId) {
      let response = await ProcessInstanceFormAPI.canReleaseForm(formId);
      commit('setModelPermission', {
        id: formId,
        name: 'canReleaseForm',
        isAllowed: response.data.allowed
      });
    }
  },

  mutations: {
    setModelPermission(state, { id, name, isAllowed }) {
      // we cannot use insertOrUpdate since it doesn't support a data function yet
      if (ProcessInstanceForm.find(id)) {
        ProcessInstanceForm.update({
          where: id,
          data(form) {
            Vue.set(form.permissions, name, isAllowed);
          }
        });
      } else {
        ProcessInstanceForm.insert({
          data: {
            id,
            permissions: {
              [name]: isAllowed
            }
          }
        });
      }
    }
  }
};
