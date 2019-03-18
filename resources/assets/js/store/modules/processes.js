import ProcessAPI from '@api/processes';

import Process from '@/store/models/Process';

export default {
  namespaced: true,

  state: {
    definitions: [],
    viewingHistory: []
  },

  getters: {},

  actions: {
    async loadDefinitions({ commit }, entityType) {
      let response = await ProcessAPI.getDefinitions(entityType);
      commit('setDefinitions', response.data.process_definitions);
    },

    async loadInstance({ commit }, id) {
      let response = await ProcessAPI.getInstance(id);
      Process.insertOrUpdate({ data: response.data });
    },

    async loadHistory({ commit }, { entityType, entityId }) {
      let response = await ProcessAPI.getHistory(entityType, entityId);
      commit('setViewingHistory', response.data.process_instances);
    },

    async start({ commit }, { nameKey, entityId, entityType }) {
      let response = await ProcessAPI.start(nameKey, entityId, entityType);
      Process.insertOrUpdate({ data: response.data.process_instance });
      return response.data.process_instance;
    },

    async cancelInstance({ commit }, id) {
      await ProcessAPI.cancelInstance(id);
    }
  },

  mutations: {
    setDefinitions(state, definitions) {
      state.definitions = definitions;
    },

    setViewingHistory(state, viewingHistory) {
      state.viewingHistory = viewingHistory || state.viewingHistory;
    }
  }
};
