import Vue from 'vue';
import _ from 'lodash';
import '../locale';

export default {
  confirm(message = '', type = 'warning', action = 'delete', confirmButtonClass = '', thenCallback = _.noop, catchCallback = _.noop) {
    return Vue.prototype.$confirm(
      message,
      Vue.prototype.trans("components.notice[" + type + "]"),
      {
        type,
        confirmButtonText: this.trans('base.actions[' + action + ']'),
        confirmButtonClass: confirmButtonClass,
        cancelButtonText: this.trans('base.actions.cancel'),
        dangerouslyUseHTMLString: true
      }
    ).then(thenCallback).catch(catchCallback);
  },

  confirmDelete(message, thenCallback = _.noop, catchCallback = _.noop) {
    return this.confirm(message, 'warning', 'delete', 'el-button--danger', thenCallback, catchCallback);
  }
};
