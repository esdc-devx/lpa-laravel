import ProjectsAPI from '../../api/projects';

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
    async loadProcess({ commit }, { entityId, processId }) {
      let response = await ProjectsAPI.getProcess(entityId, processId);
      commit('setViewing', response.data.data.process_instance);
      return response.data.data;
    },

    async canStartProcess({ commit }, { entityId, processNameKey }) {
      let response = await ProjectsAPI.canStartProcess(entityId, processNameKey);
      return response.data.data.allowed;
    },

    async startProcess({ commit }, { entityId, processNameKey }) {
      let response = await ProjectsAPI.startProcess(entityId, processNameKey);
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
