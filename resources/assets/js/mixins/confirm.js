import Vue from 'vue';
import _ from 'lodash';
import '../locale';

export default {
  confirm(message = '', type = 'warning') {
    return Vue.prototype.$confirm(
      message,
      Vue.prototype.trans("entities.general[" + type + "]"),
      {
        type,
        confirmButtonText: this.trans('base.actions.delete'),
        confirmButtonClass: 'el-button--danger',
        cancelButtonText: this.trans('base.actions.cancel'),
        dangerouslyUseHTMLString: true
      }
    );
  },

  confirmDelete(message) {
    return this.confirm(message, 'warning');
  }
};
