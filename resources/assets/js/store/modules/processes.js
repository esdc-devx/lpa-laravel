import ProcessAPI from '@api/processes';

export default {
  namespaced: true,

  state: {
    definitions: [],
    viewing: {
      definition: {
        name: ''
      },
      state: {},
      created_by: {},
      updated_by: {},
      steps: []
    },
    viewingFormInfo: {
      state: {},
      organizational_unit: {},
      definition: {
        name: ''
      },
      current_editor: {}
    },
    viewingHistory: []
  },

  getters: {
    definitions(state) {
      return state.definitions;
    },

    viewing(state) {
      return state.viewing;
    },

    viewingFormInfo(state) {
      return state.viewingFormInfo;
    },

    viewingHistory(state) {
      return state.viewingHistory;
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

    async loadHistory({ commit }, { entityType, entityId }) {
      let response = await ProcessAPI.getHistory(entityType, entityId);
      commit('setViewingHistory', response.data.data.process_instances);
      return response.data.data.process_instances;
    },

    async loadInstanceForm({ commit }, formId) {
      let response = await ProcessAPI.getInstanceForm(formId);
      commit('setViewingFormInfo', response.data.data);
      return response.data.data.form_data;
    },

    async start({ commit }, { nameKey, entityId }) {
      let response = await ProcessAPI.start(nameKey, entityId);
      return response.data.data;
    },

    async cancelInstance({ commit }, processId) {
      let response = await ProcessAPI.cancelInstance(processId);
      return response.data.data;
    },

    async claimForm({ commit }, formId) {
      let response = await ProcessAPI.claimForm(formId);
      commit('setCurrentEditor', response.data.data.current_editor);
      return response.data.data;
    },

    async unclaimForm({ commit }, formId) {
      let response = await ProcessAPI.unclaimForm(formId);
      commit('setCurrentEditor', response.data.data.current_editor);
      return response.data.data;
    },

    async saveForm({ commit }, { formId, form }) {
      let response = await ProcessAPI.saveForm(formId, form);
      commit('setViewingFormInfo', response.data.data);
      return response.data.data.form_data;
    },

    async submitForm({ commit }, { formId, form }) {
      let response = await ProcessAPI.submitForm(formId, form);
      commit('setViewingFormInfo', response.data.data);
      return response.data.data.form_data;
    },

    async releaseForm({ commit }, { formId, username }) {
      await ProcessAPI.releaseForm(formId, username);
    },

    async canCancelProcess({ commit }, processId) {
      let response = await ProcessAPI.canCancelProcess(processId);
      return response.data.data.allowed;
    },

    async canEditForm({ commit }, formId) {
      let response = await ProcessAPI.canEditForm(formId);
      return response.data.data.allowed;
    },

    async canClaimForm({ commit }, formId) {
      let response = await ProcessAPI.canClaimForm(formId);
      return response.data.data.allowed;
    },

    async canUnclaimForm({ commit }, formId) {
      let response = await ProcessAPI.canUnclaimForm(formId);
      return response.data.data.allowed;
    },

    async canSubmitForm({ commit }, formId) {
      let response = await ProcessAPI.canSubmitForm(formId);
      return response.data.data.allowed;
    },

    async canReleaseForm({ commit }, { formId, username }) {
      let response = await ProcessAPI.canReleaseForm(formId, username);
      return response.data.data.allowed;
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
    },

    setViewingFormInfo(state, viewingFormInfo) {
      viewingFormInfo = viewingFormInfo || state.viewingFormInfo;
      state.viewingFormInfo = viewingFormInfo;
    },

    setViewingHistory(state, viewingHistory) {
      viewingHistory = viewingHistory || state.viewingHistory;
      state.viewingHistory = viewingHistory;
    },

    setCurrentEditor(state, currentEditor) {
      state.viewingFormInfo.current_editor = currentEditor;
    }
  }
};
