import InformationSheetsAPI from '@api/information-sheets';

import InformationSheet from '../models/Information-Sheet';

export default {
  namespaced: true,

  state: {
    //
  },

  actions: {
    async loadAll({ commit }, { entityType, entityId }) {
      let response = await InformationSheetsAPI.getAll(entityType, entityId);
      InformationSheet.create({ data: response.data.information_sheets });
    }
  },

  mutations: {
    //
  }
};
