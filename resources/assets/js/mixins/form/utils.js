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

  methods: {
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
        this.$validator.reset();
      });
    }
  }
};
