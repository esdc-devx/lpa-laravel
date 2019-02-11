import _ from 'lodash';

import EventBus from '@/event-bus.js';

import HttpStatusCodes from '@axios/http-status-codes';

// transition is 500ms but add a 250ms buffer
// so that we can be sure that the transition is really completed
const swipeTransitionDuration = 750;
let errorNotif;

export default {
  computed: {
    isFormDirty() {
      let formHasDirtyFields = Object.keys(this.vfields).some(key => this.vfields[key].dirty);

      return formHasDirtyFields || this.fieldsAddedOrRemoved;
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

    async showRefreshErrorNotif() {
      if (errorNotif) {
        errorNotif.close();
      }
      errorNotif = await this.notifyError({
        message: this.trans('components.notice.message.validation_failure', { num: this.verrors.items.length })
      });
    },

    async handleCallback(callback) {
      try {
        await callback();
        if (errorNotif) {
          errorNotif.close();
        }
      } catch (e) {
        if (e.response && e.response.status === HttpStatusCodes.UNPROCESSABLE_ENTITY) {
          this.manageBackendErrors(e.response.data.errors);
          if (this.options.hasTabsToValidate) {
            this.showRefreshErrorNotif();
          }
        } else {
          throw e;
        }
      } finally {
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
      for (let fieldName in errors) {
        // gives us an error in the ErrorBag-item format
        const fieldErrors = errors[fieldName].map(msg => {
          return { field: fieldName, msg };
        });
        // get me the first field that has this name.
        const field = this.$validator.fields.find({ name: fieldName });

        // Make sure that field returned by the backend can be mapped to an existing field in the form.
        if (!field) {
          console.warn('Cannot map error field [' + fieldName + ']');
          continue;
        }

        // then for that field, make a correlation on the id of the error and the field
        // and add the error to our Error Bag
        fieldErrors.forEach(error => {
          // we can access the id using field.id
          error.id = field.id;
          this.verrors.add(error);
        });

        // finally update the flags, which also updates the opposite flags
        // (i.e: valid-invalid, dirty-pristine)
        field.setFlags({
          valid: !! fieldErrors.length
        });
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

    regenerateErrors() {
      this.$nextTick(() => {
        this.verrors.items.forEach(error => {
          if (error.regenerate) {
            error.msg = error.regenerate();
          }
        });
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
    EventBus.$off('Store:languageUpdate', this.regenerateErrors);
    EventBus.$off('FormUtils:fieldsAddedOrRemoved', this.onFieldAddedRemoved);
    if (errorNotif) {
      errorNotif.close();
    }
  },

  mounted() {
    EventBus.$on('Store:languageUpdate', this.regenerateErrors);
    EventBus.$on('FormUtils:fieldsAddedOrRemoved', this.onFieldAddedRemoved);
  }
};
