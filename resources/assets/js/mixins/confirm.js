import Vue from 'vue';
import _ from 'lodash';
import '@/locale';

export default {
  _confirm({
    title,
    message = '',
    action = 'delete',
    confirmButtonClass = '',
    thenCallback,
    catchCallback
  }) {
    let type = 'warning';
    title = title || Vue.prototype.trans('components.notice.type[' + type + ']');
    Vue.prototype
      .$confirm(
        message,
        title,
        {
          type,
          confirmButtonText: this.trans('base.actions[' + action + ']'),
          confirmButtonClass,
          cancelButtonText: this.trans('base.actions.cancel'),
          dangerouslyUseHTMLString: true
        }
      )
      .then(thenCallback)
      .catch(catchCallback);
  },

  confirm({ title, message, action = 'create', confirmButtonClass = '' }) {
    return new Promise((resolve, reject) => {
      this._confirm({
        title,
        message,
        action,
        confirmButtonClass,
        thenCallback: resolve,
        catchCallback: reject
      });
    });
  },

  confirmStart({ title, message }) {
    return new Promise((resolve, reject) => {
      this._confirm({
        title,
        message,
        action: 'start',
        thenCallback: resolve,
        catchCallback: reject
      });
    });
  },

  confirmDelete({ title, message }) {
    return new Promise((resolve, reject) => {
      this._confirm({
        title,
        message,
        action: 'delete',
        confirmButtonClass: 'el-button--danger',
        thenCallback: resolve,
        catchCallback: reject
      });
    });
  },

  confirmLoseChanges() {
    return new Promise((resolve, reject) => {
      this._confirm({
        title: this.trans('components.notice.title.pending_changes'),
        message: this.trans('components.notice.message.pending_changes'),
        action: 'discard',
        confirmButtonClass: 'el-button--danger',
        thenCallback: resolve,
        catchCallback: reject
      });
    });
  }
};
