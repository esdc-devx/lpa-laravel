import _ from 'lodash';

import Vue from 'vue';

import autosize from 'autosize';

/**
 * This allows us to resize textareas in IE11 to custom sizes
 */
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

/**
 * This will replace the innerHTML of binded element
 * with the value of `created_at`\n`created_by` or `updated_at`\n`updated_by`
 * while keeping the original tag
 */
const auditHandler = (el, binding, vnode, isBinding = false) => {
  let propAt = binding.value[`${binding.arg}_at`];
  let propBy = binding.value[`${binding.arg}_by`];
  propBy = _.isObject(propBy) ?
           propBy.name : (propBy || Vue.prototype.trans('entities.general.none'));
  const updateInnerHTML = () => {
    let html = '';

    if (propAt) {
      html += `<${vnode.tag}>${propAt}`;
    }
    if (propAt && propBy) {
      html += `<br>${propBy}</${vnode.tag}>`;
    } else {
      html += `</${vnode.tag}>`;
    }
    el.innerHTML = html;
  };

  if (isBinding) {
    updateInnerHTML();
  } else {
    let oldPropAt = binding.oldValue[`${binding.arg}_at`];
    let oldPropBy = binding.oldValue[`${binding.arg}_by`];
    // do not re-render the element if it has not changed
    if (oldPropAt !== binding.value[`${binding.arg}_at`] ||
        oldPropBy !== binding.value[`${binding.arg}_by`]) {
      updateInnerHTML();
    }
  }
};
Vue.directive('audit', {
  bind(el, binding, vnode) {
    auditHandler(el, binding, vnode, true);
  },
  update(el, binding, vnode) {
    auditHandler(el, binding, vnode);
  }
});
