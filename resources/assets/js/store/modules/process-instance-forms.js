import ProcessInstanceFormAPI from '@api/process-instance-forms';

import ProcessInstanceForm from '@/store/models/Process-Instance-Form';

export default {
  namespaced: true,

  state: {},

  getters: {},

  actions: {
    async loadInstanceForm({ commit }, id) {
      const response = await ProcessInstanceFormAPI.getInstanceForm(id);
      ProcessInstanceForm.insertOrUpdate({ data: response.data });
    },

    async claimForm({ commit }, id) {
      const response = await ProcessInstanceFormAPI.claimForm(id);
      ProcessInstanceForm.update({
        where: id,
        data: response.data
      });
    },

    async unclaimForm({ commit }, id) {
      const response = await ProcessInstanceFormAPI.unclaimForm(id);
      ProcessInstanceForm.update({
        where: id,
        data: response.data
      });
    },

    async saveForm({ commit }, { id, form }) {
      const response = await ProcessInstanceFormAPI.saveForm(id, form);
      ProcessInstanceForm.update({
        where: id,
        data: response.data
      });
    },

    async submitForm({ commit }, { id, form }) {
      const response = await ProcessInstanceFormAPI.submitForm(id, form);
      ProcessInstanceForm.update({
        where: id,
        data: response.data
      });
    },

    async releaseForm({ commit }, { id, username }) {
      await ProcessInstanceFormAPI.releaseForm(id, username);
    }
  },

  mutations: {}
};
