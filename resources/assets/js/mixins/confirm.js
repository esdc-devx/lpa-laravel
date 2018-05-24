import Vue from 'vue';
import _ from 'lodash';
import '../locale';

export default {
  _confirm(
    message = '',
    action = 'delete',
    confirmButtonClass = '',
    thenCallback = _.noop,
    catchCallback = _.noop
  ) {
    let type = 'warning';
    return Vue.prototype
      .$confirm(
        message,
        Vue.prototype.trans('components.notice[' + type + ']'),
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

  confirm(message, action = 'create', confirmButtonClass = '', thenCallback = _.noop, catchCallback = _.noop) {
    return this._confirm(
      message,
      action,
      confirmButtonClass,
      thenCallback,
      catchCallback
    );
  },

  confirmStart(message, thenCallback = _.noop, catchCallback = _.noop) {
    return this._confirm(
      message,
      'start',
      '',
      thenCallback,
      catchCallback
    );
  },

  confirmDelete(message, thenCallback = _.noop, catchCallback = _.noop) {
    return this._confirm(
      message,
      'delete',
      'el-button--danger',
      thenCallback,
      catchCallback
    );
  }
};
