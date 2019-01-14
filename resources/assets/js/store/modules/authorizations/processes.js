import Vue from 'vue';

import ProcessesAPI from '@api/processes';

import Process from '@/store/models/Process';

export default {
  namespaced: true,

  state: {},

  getters: {},

  actions: {
    async loadCanCancel({ commit }, id) {
      let response = await ProcessesAPI.canCancel(id);
      commit('setModelPermission', {
        id,
        name: 'canCancel',
        isAllowed: response.data.allowed
      });
    }
  },

  mutations: {
    setModelPermission(state, { id, name, isAllowed }) {
      // we cannot use insertOrUpdate since it doesn't support a data function yet
      if (Process.find(id)) {
        Process.update({
          where: id,
          data(process) {
            Vue.set(process.permissions, name, isAllowed);
          }
        });
      } else {
        Process.insert({
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
