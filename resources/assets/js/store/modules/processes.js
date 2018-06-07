import ProcessAPI from '../../api/processes';

export default {
  namespaced: true,

  state: {
    all: [],
    viewing: {
      definition: {},
      state: {},
      created_by: {},
      updated_by: {}
    }
  },

  getters: {
    all(state) {
      return state.all;
    },

    viewing(state) {
      return state.viewing;
    }
  },

  actions: {
    async loadProcesses({ commit }, entityType) {
      let response = await ProcessAPI.getProcesses(entityType);
      commit('setProcesses', response.data.data.process_definitions);
      return response.data.data;
    },

    async loadProcess({ commit }, processId) {
      let response = await ProcessAPI.getProcess(processId);
      commit('setViewing', response.data.data.process_instance);
      return response.data.data;
    },

    async startProcess({ commit }, { nameKey, entityId }) {
      let response = await ProcessAPI.startProcess(nameKey, entityId);
      return response.data.data;
    }
  },

  mutations: {
    setProcesses(state, processes) {
      state.all = processes;
    },

    setViewing(state, viewing) {
      // set to default in case there is no process yet
      viewing = viewing || state.viewing;
      state.viewing = viewing;
    }
  }
};
