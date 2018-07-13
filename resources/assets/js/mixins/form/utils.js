import _ from 'lodash';

import HttpStatusCodes from "@axios/http-status-codes";

const swipeTransitionDuration = 500;
const errorSlideOutTransitionDuration = 400;
let errorNotif;

export default {
  computed: {
    isFormDirty() {
      return Object.keys(this.vfields).some(key => this.vfields[key].dirty);
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
          if (errorNotif) {
            errorNotif.close();
          }
          errorNotif = this.notifyError({
            message: this.trans('components.notice.message.validation_failure', { num: this.verrors.items.length })
          });
        }

        this.isSubmitting = false;
        this.focusOnError();
      });
    },

    async handleCallback(callback) {
      try {
        await callback();
      } catch({ response }) {
        if (response.status === HttpStatusCodes.FORBIDDEN) {
          this.notifyWarning({
            message: response.data.errors
          });
        } else if (response.status === HttpStatusCodes.SERVER_ERROR) {
          this.notifyError({
            message: this.trans('errors.server_error')
          });
        } else if (response.status === HttpStatusCodes.UNPROCESSABLE_ENTITY) {
          this.manageBackendErrors(response.data.errors);
          this.notifyError({
            message: this.trans('components.notice.message.validation_failure', { num: this.verrors.items.length })
          });
        }
        this.isSubmitting = false;
      }
    },

    checkInvalidTabs() {
      if (document.querySelector("form .el-tabs__nav") !== null) {
        const tabs = document.querySelectorAll("form .el-tab-pane");
        tabs.forEach((tab) => {
          this.checkTabHasErrors(tab);
        });
      }
    },

    checkTabHasErrors(tab) {
      // make sure to wait until errors are transitioning to leave
      // this is only valid when user edit the form directly, without submitting
      _.delay(() => {
        const tabName = tab.getAttribute("data-name");
        // make sure there is no error present in the form
        // and that no errors are currently being removed from the page
        if (tab.querySelector(".el-form-item__error") !== null) {
          if (!this.errorTabs.includes(tabName)) {
            this.errorTabs.push(tabName);
          }
          return true;
        } else {
          const index = this.errorTabs.indexOf(tabName);
          if (index !== -1) {
            this.errorTabs.splice(index, 1);
          }
          return false;
        }
      }, errorSlideOutTransitionDuration);
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
    }
  }
};
