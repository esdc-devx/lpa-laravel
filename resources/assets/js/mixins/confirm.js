import Vue from 'vue';
import _ from 'lodash';
import '../locale';

export default {
  _confirm(
    title,
    message = '',
    action = 'delete',
    confirmButtonClass = '',
    thenCallback = _.noop,
    catchCallback = _.noop
  ) {
    let type = 'warning';
    title = title || Vue.prototype.trans('components.notice[' + type + ']');
    return Vue.prototype
      .$confirm(
        message,
        title,
        {
          type,
          confirmButtonText: this.trans('base.actions[' + action + ']'),
          confirmButtonClass: confirmButtonClass,
          cancelButtonText: this.trans('base.actions.cancel'),
          dangerouslyUseHTMLString: true
        }
      )
      .then(thenCallback)
      .catch(catchCallback);
  },

  confirm(title, message, action = 'create', confirmButtonClass = '', thenCallback = _.noop, catchCallback = _.noop) {
    return this._confirm(
      title,
      message,
      action,
      confirmButtonClass,
      thenCallback,
      catchCallback
    );
  },

  confirmStart(title, message, thenCallback = _.noop, catchCallback = _.noop) {
    return this._confirm(
      title,
      message,
      'start',
      '',
      thenCallback,
      catchCallback
    );
  },

  confirmDelete(title, message, thenCallback = _.noop, catchCallback = _.noop) {
    return this._confirm(
      title,
      message,
      'delete',
      'el-button--danger',
      thenCallback,
      catchCallback
    );
  }
};
