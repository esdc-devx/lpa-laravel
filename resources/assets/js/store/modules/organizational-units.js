import OrganizationalUnitsAPI from '@api/organizational-units';

import OrganizationalUnit from '../models/Organizational-Unit';

export default {
  namespaced: true,

  state: {
    //
  },

  actions: {
    async $$update({ commit }, organizationalUnit) {
      await OrganizationalUnitsAPI.update(organizationalUnit);
    },

    async load({ commit }, id) {
      let response = await OrganizationalUnitsAPI.get(id);
      OrganizationalUnit.insertOrUpdate({ data: response.data.organizational_unit });
    },

    async loadAll({ commit }) {
      let response = await OrganizationalUnitsAPI.getAll();
      OrganizationalUnit.create({ data: response.data.organizational_units });
    },

    async loadEditInfo({ commit }, id) {
      let response = await OrganizationalUnitsAPI.getEditInfo(id);
      OrganizationalUnit.insertOrUpdate({ data: response.data.organizational_unit });
    }
  },

  mutations: {
    //
  }
};
