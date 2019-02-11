import OrganizationalUnitsAPI from '@api/organizational-units';

import OrganizationalUnit from '@/store/models/Organizational-Unit';

export default {
  namespaced: true,

  state: {
    //
  },

  actions: {
    async $$update({ commit }, organizationalUnit) {
      const response = await OrganizationalUnitsAPI.update(organizationalUnit);
      OrganizationalUnit.insertOrUpdate({ data: response.data });
    },

    async load({ commit }, id) {
      const response = await OrganizationalUnitsAPI.get(id);
      OrganizationalUnit.insertOrUpdate({ data: response.data.organizational_unit });
    },

    async loadAll({ commit }) {
      const response = await OrganizationalUnitsAPI.getAll();
      OrganizationalUnit.create({ data: response.data.organizational_units });
    },

    async loadEditInfo({ commit }, id) {
      const response = await OrganizationalUnitsAPI.getEditInfo(id);
      OrganizationalUnit.insertOrUpdate({ data: response.data.organizational_unit });
    }
  },

  mutations: {
    //
  }
};
