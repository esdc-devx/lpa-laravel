import ProcessAPI from '@api/processes';

export default {
  namespaced: true,

  state: {
    definitions: [],
    permissions: {
      canEditForm: false,
      canClaimForm: false,
      canUnclaimForm: false,
      canSubmitForm: false,
      canReleaseForm: false
    },
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
      commit('setDefinitions', response.data.process_definitions);
      return response.data;
    },

    async loadInstance({ commit }, processId) {
      let response = await ProcessAPI.getInstance(processId);
      commit('setViewing', response.data.process_instance);
      return response.data;
    },

    async loadHistory({ commit }, { entityType, entityId }) {
      let response = await ProcessAPI.getHistory(entityType, entityId);
      commit('setViewingHistory', response.data.process_instances);
      return response.data.process_instances;
    },

    async loadInstanceForm({ commit }, formId) {
      let response = await ProcessAPI.getInstanceForm(formId);
      commit('setViewingFormInfo', response.data);
      return response.data.form_data;
    },

    async start({ commit }, { nameKey, entityId }) {
      let response = await ProcessAPI.start(nameKey, entityId);
      return response.data;
    },

    async cancelInstance({ commit }, processId) {
      let response = await ProcessAPI.cancelInstance(processId);
      return response.data;
    },

    async claimForm({ commit }, formId) {
      let response = await ProcessAPI.claimForm(formId);
      commit('setCurrentEditor', response.data.current_editor);
      return response.data;
    },

    async unclaimForm({ commit }, formId) {
      let response = await ProcessAPI.unclaimForm(formId);
      commit('setCurrentEditor', response.data.current_editor);
      return response.data;
    },

    async saveForm({ commit }, { formId, form }) {
      let response = await ProcessAPI.saveForm(formId, form);
      commit('setViewingFormInfo', response.data);
      return response.data.form_data;
    },

    async submitForm({ commit }, { formId, form }) {
      let response = await ProcessAPI.submitForm(formId, form);
      commit('setViewingFormInfo', response.data);
      return response.data.form_data;
    },

    async releaseForm({ commit }, { formId, username }) {
      await ProcessAPI.releaseForm(formId, username);
    },

    async loadCanCancel({ commit }, processId) {
      let response = await ProcessAPI.canCancel(processId);
      commit('setPermission', {
        name: 'canCancel',
        isAllowed: response.data.allowed
      });
    },

    async loadCanEditForm({ commit }, formId) {
      let response = await ProcessAPI.canEditForm(formId);
      commit('setPermission', {
        name: 'canEditForm',
        isAllowed: response.data.allowed
      });
    },

    async loadCanClaimForm({ commit }, formId) {
      let response = await ProcessAPI.canClaimForm(formId);
      commit('setPermission', {
        name: 'canClaimForm',
        isAllowed: response.data.allowed
      });
    },

    async loadCanUnclaimForm({ commit }, formId) {
      let response = await ProcessAPI.canUnclaimForm(formId);
      commit('setPermission', {
        name: 'canUnclaimForm',
        isAllowed: response.data.allowed
      });
    },

    async loadCanSubmitForm({ commit }, formId) {
      let response = await ProcessAPI.canSubmitForm(formId);
      commit('setPermission', {
        name: 'canSubmitForm',
        isAllowed: response.data.allowed
      });
    },

    async loadCanReleaseForm({ commit }, formId) {
      let response = await ProcessAPI.canReleaseForm(formId);
      commit('setPermission', {
        name: 'canReleaseForm',
        isAllowed: response.data.allowed
      });
    }
  },

  mutations: {
    setDefinitions(state, definitions) {
      state.definitions = definitions;
    },

    setPermission(state, { name, isAllowed }) {
      state.permissions[name] = isAllowed;
    },

    setCanEditForm(state, allowed) {
      state.canEditForm = allowed;
    },

    setCanClaimForm(state, allowed) {
      state.canClaimForm = allowed;
    },

    setCanUnclaimForm(state, allowed) {
      state.canUnclaimForm = allowed;
    },

    setCanSubmitForm(state, allowed) {
      state.canSubmitForm = allowed;
    },

    setCanReleaseForm(state, allowed) {
      state.canReleaseForm = allowed;
    },

    setCanCancel(state, allowed) {
      state.canCancel = allowed;
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
      state.viewingHistory = viewingHistory || state.viewingHistory;
    },

    setCurrentEditor(state, currentEditor) {
      state.viewingFormInfo.current_editor = currentEditor;
    }
  }
};
