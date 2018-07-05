import _ from 'lodash';

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
      swipeTransitionDuration: 500,
      isSaving: false,
      isSubmitting: false,
      isFormDisabled: false
    };
  },

  watch: {
    'verrors.items': function(val) {
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
      }, this.swipeTransitionDuration);
    },

    submit(callback) {
      this.isSubmitting = true;
      // clear the error messages right away
      this.verrors.clear();
      this.$validator.validateAll().then(result => {
        if (result) {
          // all is good, proceed
          if (_.isFunction(callback)) {
            callback();
          }
          this.isSubmitting = false;
          return;
        }

        // there are errors
        if (this.options.hasTabsToValidate) {
          this.$nextTick(() => {
            this.checkInvalidTabs();
          });
          this.notifyError({
            message: this.trans('components.notice.message.validation_failure', { num: this.verrors.items.length })
          });
        }

        this.isSubmitting = false;
        this.focusOnError();
      });
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
      }, 400);
    },

    manageBackendErrors(errors) {
      let fieldBag;
      for (let fieldName in errors) {
        fieldBag = errors[fieldName];
        for (let j = 0; j < fieldBag.length; j++) {
          // since we are dealing with a username while sending to the backend,
          // we need to map the username to the name so that the name field has the error
          // and not the username field which doesn't exist
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
