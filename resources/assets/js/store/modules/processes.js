import ProcessAPI from '../../api/processes';

export default {
  namespaced: true,

  state: {
    definitions: [],
    viewing: {
      definition: {},
      state: {},
      created_by: {},
      updated_by: {}
    }
  },

  getters: {
    definitions(state) {
      return state.definitions;
    },

    viewing(state) {
      return state.viewing;
    }
  },

  actions: {
    async loadDefinitions({ commit }, entityType) {
      let response = await ProcessAPI.getDefinitions(entityType);
      commit('setDefinitions', response.data.data.process_definitions);
      return response.data.data;
    },

    async loadInstance({ commit }, processId) {
      let response = await ProcessAPI.getInstance(processId);
      commit('setViewing', response.data.data.process_instance);
      return response.data.data;
    },

    async start({ commit }, { nameKey, entityId }) {
      let response = await ProcessAPI.start(nameKey, entityId);
      return response.data.data;
    }
  },

  mutations: {
    setDefinitions(state, definitions) {
      state.definitions = definitions;
    },

    setViewing(state, viewing) {
      // set to default in case there is no process yet
      viewing = viewing || state.viewing;
      state.viewing = viewing;
    }
  }
};
