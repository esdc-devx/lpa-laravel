import _ from 'lodash';
import Vue from 'vue';

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
      swipeTransitionDuration: 500,
      isSaving: false,
      isFormDisabled: false
    };
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
      this.isSaving = true;
      this.resetErrors();
      this.$validator.validateAll().then(result => {
        if (result) {
          if (_.isFunction(callback)) {
            callback();
          }
          return;
        }
        this.isSaving = false;
        this.focusOnError();
      });
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
    }
  }
};
