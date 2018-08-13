<template>
  <div class="input-wrap">
    <div :class="[`el-${type}`, { 'is-disabled': isFormDisabled }]">
      <component
        v-bind="$attrs"
        :is="type"
        :class="[`el-${type}__inner`, { 'is-error': verrors.has(name) }]"
        v-text="innerValue"
        :name="name"
        :maxlength="charsLimit"
        @keyup="onKeyDown"
        @input="onInput">
      </component>
    </div>
    <div class="input-infos">
      <form-error :name="name"></form-error>
      <span v-if="maxlength" class="char-count">
        {{ charCount }} / {{ charsLimit }}
      </span>
    </div>
  </div>
</template>

<script>
  import FormError from './error.vue';

  export default {
    name: 'input-wrap',

    inheritAttrs: false,

    components: { FormError },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection

    // Also, inject the form so that we are able to restrict user from selecting an option
    // while it is disabled
    inject: ['$validator', 'elForm'],

    props: {
      maxlength: String,
      name: {
        type: String,
        required: true
      },
      type: {
        type: String,
        default: 'input',
        validator: function (value) {
          // The value must match one of these strings
          return ['input', 'textarea'].indexOf(value) !== -1
        }
      },
      value: String
    },

    data() {
      return {
        charCount: 0,
        innerValue: this.value
      };
    },

    computed: {
      charsLimit() {
        return this.maxlength;
      },

      isFormDisabled() {
        return this.$attrs.disabled || this.elForm.disabled;
      }
    },

    methods: {
      updateValue(value) {
        // update parent data so that we can still v-model on the parent
        this.$helpers.debounceAction(() => {
          this.$emit('input', value);
        });
      },

      onKeyDown(e) {
        let value = e.target.value;
        this.charCount = value.length;
        this.updateValue(value);
      },

      onInput(e) {
        let value = e.target.value;
        // if is not a keyboard event
        if (!(e instanceof KeyboardEvent)) {
          // might be mouse event (copy-paste, cut, drag), update the model
          this.updateValue(value);
        }
      }
    },

    mounted() {
      this.charCount = this.value ? this.value.length : 0;
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  .input-wrap {
    display: flex;
    flex-direction: column;
    .char-count {
      color: $--color-text-secondary;
    }
    .el-form-item__error {
      position: relative;
      top: 0;
    }
    .input-infos {
      display: flex;
      justify-content: flex-end;
    }
  }
</style>
