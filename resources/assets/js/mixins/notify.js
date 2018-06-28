import Vue from 'vue';
import _ from 'lodash';
import '../locale';

export default {
  _notify({ message = '', type = 'info', autoClose = true }) {
    Vue.prototype.$notify({
      title: Vue.prototype.trans("components.notice.type[" + type + "]"),
      message,
      type,
      dangerouslyUseHTMLString: true,
      offset: 60,
      duration: autoClose ? 4500 : 0
    });
  },

  notifySuccess({ message, autoClose = true }) {
    this._notify({
      message,
      type: 'success',
      autoClose
    });
  },

  notifyInfo({ message, autoClose = true }) {
    this._notify({
      message,
      type: 'info',
      autoClose
    });
  },

  notifyWarning({ message, autoClose = true }) {
    this._notify({
      message,
      type: 'warning',
      autoClose
    });
  },

  notifyError({ message, autoClose = false }) {
    this._notify({
      message,
      type: 'error',
      autoClose
    });
  }
};
