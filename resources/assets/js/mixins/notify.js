import Vue from 'vue';
import _ from 'lodash';
import '../locale';

export default {
  notify(message = '', type = 'info', autoClose = true) {
    Vue.prototype.$notify({
      title: Vue.prototype.trans("components.notice[" + type + "]"),
      message,
      type,
      dangerouslyUseHTMLString: true,
      offset: 60,
      duration: autoClose ? 4500 : 0
    });
  },

  notifySuccess(message, autoClose = true) {
    this.notify(message, 'success', autoClose);
  },

  notifyInfo(message, autoClose = true) {
    this.notify(message, 'info', autoClose);
  },

  notifyWarning(message, autoClose = true) {
    this.notify(message, 'warning', autoClose);
  },

  notifyError(message, autoClose = false) {
    this.notify(message, 'error', autoClose);
  }
};
