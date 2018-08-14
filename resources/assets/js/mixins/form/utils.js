import _ from 'lodash';

import EventBus from '@/event-bus.js';

import HttpStatusCodes from '@axios/http-status-codes';

const swipeTransitionDuration = 500;
let errorNotif;

export default {
  computed: {
    isFormDirty() {
      let formHasErrors = Object.keys(this.vfields).some(key => this.vfields[key].dirty);

      return formHasErrors || this.fieldsAddedOrRemoved;
    },

    isFormPristine() {
      return Object.keys(this.vfields).every(key => this.vfields[key].pristine);
    }
  },

  data() {
    return {
      // this controls whether or not to handle validation on tab-panels
      options: {
        hasTabsToValidate: false
      },
      fieldsAddedOrRemoved: false,
      errorTabs: [],
      isSaving: false,
      isSubmitting: false,
      isFormDisabled: false
    };
  },

  watch: {
    'verrors.items.length': function(val) {
      // this allows to reflect error styling on tabs
      // when the errors are added/removed
      if (this.options.hasTabsToValidate) {
        this.$nextTick(() => {
          this.checkInvalidTabs();
        });
      }
    }
  },

  beforeRouteLeave(to, from, next) {
    this.isFormDisabled = true;
    next();
  },

  methods: {
    autofocus(id = '') {
      _.delay(() => {
        this.$nextTick(() => {
          let input = document.getElementById(id) || document.querySelectorAll('input')[0];
          if (input) {
            input.focus();
          }
        });
      }, swipeTransitionDuration);
    },

    submit(callback) {
      this.isSubmitting = true;
      // clear the error messages right away
      this.verrors.clear();
      this.$validator.validateAll().then(result => {
        if (result) {
          // all is good, proceed
          if (_.isFunction(callback)) {
            // no need to set isSubmitting to false as there will be a call to the backend afterwards
            this.handleCallback(callback);
          } else {
            this.isSubmitting = false;
          }
          return;
        }

        // there are errors
        if (this.options.hasTabsToValidate) {
          this.$nextTick(() => {
            this.checkInvalidTabs();
          });
          this.showRefreshErrorNotif();
        }

        this.isSubmitting = false;
        this.focusOnError();
      });
    },

    showRefreshErrorNotif() {
      if (errorNotif) {
        errorNotif.close();
      }
      errorNotif = this.notifyError({
        message: this.trans('components.notice.message.validation_failure', { num: this.verrors.items.length })
      });
    },

    async handleCallback(callback) {
      try {
        await callback();
        if (errorNotif) {
          errorNotif.close();
        }
      } catch({ response }) {
        if (response.status === HttpStatusCodes.UNPROCESSABLE_ENTITY) {
          this.manageBackendErrors(response.data.errors);
          if (this.options.hasTabsToValidate) {
            this.showRefreshErrorNotif();
          }
        } else if (response.status === HttpStatusCodes.BAD_REQUEST) {
          this.notifyError({
            message: Vue.prototype.trans('errors.bad_request')
          });
        } else if (response.status === HttpStatusCodes.SERVER_ERROR) {
          this.notifyError({
            message: Vue.prototype.trans('errors.server_error')
          });
        }
        this.isSubmitting = false;
      }
    },

    checkInvalidTabs() {
      if (document.querySelector('form .el-tabs__nav') !== null) {
        const tabs = Array.from(document.querySelectorAll('form .el-tab-pane'));
        tabs.forEach(tab => {
          this.checkTabHasErrors(tab);
        });
      }
    },

    checkTabHasErrors(tab) {
      const tabName = tab.getAttribute('data-name');
      // cast NodeList to Array
      let tabErrors = Array.from(tab.querySelectorAll('.el-form-item__error'));

      // there are errors in the pane
      // check if there at least one error that is not transitioning
      let errorsFound = tabErrors.some(error => {
        if (!error.classList.contains('el-zoom-in-top-leave-active')) {
          // if not already present in list, add it
          if (!this.errorTabs.includes(tabName)) {
            this.errorTabs.push(tabName);
          }
          return true;
        }
      });

      // if there are no errors, return false
      if (!tabErrors.length || !errorsFound) {
        const index = this.errorTabs.indexOf(tabName);
        // remove tab from error list
        if (index !== -1) {
          this.errorTabs.splice(index, 1);
        }
        return false;
      }
    },

    manageBackendErrors(errors) {
      let fieldBag;
      for (let fieldName in errors) {
        fieldBag = errors[fieldName];
        for (let j = 0; j < fieldBag.length; j++) {
          this.verrors.add({field: fieldName, msg: fieldBag[j]});
        }
      }
      this.focusOnError();
    },

    focusOnError() {
      this.$nextTick(() => {
        let errorInput = document.querySelectorAll('.is-error input')[0];
        if (errorInput) {
          errorInput.focus();
        }
      });
    },

    resetForm(formName = 'form') {
      this.$refs[formName].resetFields();
      this.resetErrors();
    },

    resetErrors() {
      this.$nextTick(() => {
        this.verrors.clear();
      });
    },

    resetFieldsState() {
      this.$nextTick(() => {
        _.forEach(this.$validator.fields.items, field => {
          field.reset();
        });
      });
    },

    onFieldAddedRemoved(isAddedRemoved) {
      this.fieldsAddedOrRemoved = !_.isUndefined(isAddedRemoved) ? isAddedRemoved : true;
    }
  },

  beforeDestroy() {
    EventBus.$off('FormUtils:fieldsAddedOrRemoved', this.onFieldAddedRemoved);
    if (errorNotif) {
      errorNotif.close();
    }
  },

  mounted() {
    EventBus.$on('FormUtils:fieldsAddedOrRemoved', this.onFieldAddedRemoved);
  }
};
