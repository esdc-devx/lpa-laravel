import Vue from 'vue';
import '@/locale';

const ERROR_TYPES = {
  'App\\Exceptions\\InsufficientPrivilegesException' : 'components.notice.title.insufficient_privileges',
  'App\\Exceptions\\OperationDeniedException'        : 'components.notice.title.operation_denied'
};

const TRANSITION_DELAY = 1000;
let count = 0;

export default {
  _notify({ title = '', message = '', type = 'info', autoClose = true }) {
    title = ERROR_TYPES[title] ? Vue.prototype.trans(ERROR_TYPES[title]) : Vue.prototype.trans(`components.notice.type.${type}`);
    let promise = new Promise((resolve, reject) => {
      _.delay(() => {
        resolve(Vue.prototype.$notify({
          title,
          message,
          type,
          dangerouslyUseHTMLString: true,
          offset: 60,
          duration: autoClose ? 4500 : 0
        }));
        --count;
      }, count * TRANSITION_DELAY);
    });
    ++count;
    return promise;
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
