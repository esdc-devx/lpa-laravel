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
      steps: {}
    },
    viewingForm: {},
    viewingFormInfo: {
      state: {},
      organizational_unit: {},
      definition: {
        name: ''
      },
      current_editor: {}
    }
  },

  getters: {
    definitions(state) {
      return state.definitions;
    },

    viewing(state) {
      return state.viewing;
    },

    viewingForm(state) {
      return state.viewingForm;
    },

    viewingFormInfo(state) {
      return state.viewingFormInfo;
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

    async loadInstanceForm({ commit }, formId) {
      let response = await ProcessAPI.getInstanceForm(formId);
      commit('setViewingForm', _.omit(response.data.data, 'form_data'));
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

    setViewingForm(state, viewingForm) {
      state.viewingForm = viewingForm;
    },

    setViewingFormInfo(state, viewingFormInfo) {
      viewingFormInfo = viewingFormInfo || state.viewingFormInfo;
      state.viewingFormInfo = viewingFormInfo;
    },

    setCurrentEditor(state, currentEditor) {
      state.viewingFormInfo.current_editor = currentEditor;
    }
  }
};
