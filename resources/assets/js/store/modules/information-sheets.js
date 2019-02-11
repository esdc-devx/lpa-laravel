import InformationSheetsAPI from '@api/information-sheets';

import InformationSheet from '@/store/models/Information-Sheet';

export default {
  namespaced: true,

  state: {
    //
  },

  actions: {
    async loadAll({ commit }, { entityType, entityId }) {
      const response = await InformationSheetsAPI.getAll(entityType, entityId);
      InformationSheet.create({ data: response.data.information_sheets });
    }
  },

  mutations: {
    //
  }
};
