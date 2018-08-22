import Vue from 'vue';
import '@/locale';

export default {
  _notify({ title = '', message = '', type = 'info', autoClose = true }) {
    return Vue.prototype.$notify({
      title: title || Vue.prototype.trans(`components.notice.type.${type}`),
      message,
      type,
      dangerouslyUseHTMLString: true,
      offset: 60,
      duration: autoClose ? 4500 : 0
    });
  },

  notifySuccess({ title, message, autoClose = true }) {
    return this._notify({
      title,
      message,
      type: 'success',
      autoClose
    });
  },

  notifyInfo({ title, message, autoClose = true }) {
    return this._notify({
      title,
      message,
      type: 'info',
      autoClose
    });
  },

  notifyWarning({ title, message, autoClose = true }) {
    return this._notify({
      title,
      message,
      type: 'warning',
      autoClose
    });
  },

  notifyError({ title, message, autoClose = false }) {
    return this._notify({
      title,
      message,
      type: 'error',
      autoClose
    });
  }
};
