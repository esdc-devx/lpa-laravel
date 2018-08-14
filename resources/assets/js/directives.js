import Vue from 'vue';

import autosize from 'autosize';

Vue.directive('autosize', {
	bind(el) {
    autosize(el);
	},
  inserted(el) {
    autosize.update(el);
  },
  update(el) {
    autosize.update(el);
  },
  unbind(el) {
    autosize.destroy(el);
  }
});
