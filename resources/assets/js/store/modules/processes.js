import ProcessAPI from '@api/processes';

import Process from '../models/Process';

export default {
  namespaced: true,

  state: {
    activeStep: 0,
    definitions: [],
    viewingHistory: []
  },

  getters: {
    activeStep(state) {
      return function (id) {
        let viewing = Process.find(id);
        if (viewing.state.name_key === 'completed') {
          return viewing.steps.length - 1;
        }

        let active = 0;
        // grab the last step that is not locked
        _.forEach(viewing.steps, (step, i) => {
          if (step.state.name_key !== 'locked') {
            active = i;
          }
        });
        return active;
      }
    }
  },

  actions: {
    async loadDefinitions({ commit }, entityType) {
      let response = await ProcessAPI.getDefinitions(entityType);
      commit('setDefinitions', response.data.process_definitions);
    },

    async loadInstance({ commit }, id) {
      let response = await ProcessAPI.getInstance(id);
      Process.insertOrUpdate({ data: response.data.process_instance });
    },

    async loadHistory({ commit }, { entityType, entityId }) {
      let response = await ProcessAPI.getHistory(entityType, entityId);
      commit('setViewingHistory', response.data.process_instances);
    },

    async start({ commit }, { nameKey, entityId }) {
      let response = await ProcessAPI.start(nameKey, entityId);
      Process.insertOrUpdate({ data: response.data.process_instance });
      return response.data.process_instance;
    },

    async cancelInstance({ commit }, id) {
      await ProcessAPI.cancelInstance(id);
    }
  },

  mutations: {
    setActiveStep(state, activeStep) {
      state.activeStep = activeStep;
    },

    setDefinitions(state, definitions) {
      state.definitions = definitions;
    },

    setViewingHistory(state, viewingHistory) {
      state.viewingHistory = viewingHistory || state.viewingHistory;
    }
  }
};
