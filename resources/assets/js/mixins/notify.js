import Vue from 'vue';
import '@/locale';

export default {
  _notify({ message = '', type = 'info', autoClose = true }) {
    return Vue.prototype.$notify({
      title: Vue.prototype.trans(`components.notice.type.${type}`),
      message,
      type,
      dangerouslyUseHTMLString: true,
      offset: 60,
      duration: autoClose ? 4500 : 0
    });
  },

  notifySuccess({ message, autoClose = true }) {
    return this._notify({
      message,
      type: 'success',
      autoClose
    });
  },

  notifyInfo({ message, autoClose = true }) {
    return this._notify({
      message,
      type: 'info',
      autoClose
    });
  },

  notifyWarning({ message, autoClose = true }) {
    return this._notify({
      message,
      type: 'warning',
      autoClose
    });
  },

  notifyError({ message, autoClose = false }) {
    return this._notify({
      message,
      type: 'error',
      autoClose
    });
  }
};
