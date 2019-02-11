import ProcessNotificationsAPI from '@api/process-notifications';

import ProcessNotification from '../models/Process-Notification';

export default {
  namespaced: true,

  state: {
    //
  },

  actions: {
    async $$update({ commit }, processNotification) {
      const response = await ProcessNotificationsAPI.update(processNotification);
      ProcessNotification.insertOrUpdate({ data: response.data.process_notification });
    },

    async load({ commit }, id) {
      const response = await ProcessNotificationsAPI.get(id);
      ProcessNotification.insertOrUpdate({ data: response.data.process_notification });
    },

    async loadAll({ commit }) {
      const response = await ProcessNotificationsAPI.getAll();
      ProcessNotification.create({ data: response.data.process_notifications });
    },

    async loadEditInfo({ commit }, id) {
      const response = await ProcessNotificationsAPI.getEditInfo(id);
      ProcessNotification.insertOrUpdate({ data: response.data.process_notification });
    }
  },

  mutations: {
    //
  }
};
