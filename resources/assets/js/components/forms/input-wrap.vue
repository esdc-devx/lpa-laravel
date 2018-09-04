<template>
  <div class="input-wrap">
    <div :class="[`el-${type}`, { 'is-disabled': isInputDisabled }]">
      <!--
        due to the fact that v-model and :value are equivalents,
        we need to specify that value here will be a .prop on the element itself
      -->
      <component
        ref="input"
        v-if="!typeIsNumber"
        v-bind="$attrs"
        :is="type"
        :class="[`el-${type}__inner`, { 'is-error': verrors.has(name) }]"
        :value.prop="currentTextValue"
        :disabled="isInputDisabled"
        :name="name"
        :maxlength="charsLimit"
        @input="currentTextValue = $event.target.value"
        v-autosize>
      </component>
      <div v-else class="el-input-number">
        <span
          role="button"
          :class="['el-input-number__decrease', {'is-disabled': isMinDisabled}]"
          @keydown.enter="decrease"
          @click="decrease">
            <i class="el-icon-minus"></i>
        </span>
        <span
          role="button"
          :class="['el-input-number__increase', {'is-disabled': isMaxDisabled}]"
          @keydown.enter="increase"
          @click="increase">
            <i class="el-icon-plus"></i>
        </span>
        <div :class="['el-input', {'is-disabled': isInputDisabled}]">
          <input
            ref="inputNumber"
            type="text"
            autocomplete="off"
            class="el-input__inner"
            role="spinbutton"
            :value="currentNumberValue"
            :min="min"
            :max="max"
            :disabled="isInputDisabled"
            @keydown.up="increase"
            @keydown.down="decrease"
            @input="handleInputChange">
        </div>
      </div>
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
  // Inspiration from:
  // https://github.com/ElemeFE/element/blob/dev/packages/input-number/src/input-number.vue
  //
  // The part on input-number is based on the actual ElementUI el-input-number
  // which was slightly modified to allow editing a value
  // without always sending the updated value to the parent
  // which causes major lag on big forms.

  import EventBus from '@/event-bus.js';

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
          return ['input', 'textarea', 'number'].indexOf(value) !== -1
        }
      },
      step: {
        type: Number,
        default: 1
      },
      max: {
        type: Number,
        default: Infinity
      },
      min: {
        type: Number,
        default: -Infinity
      },
      value: String | Number,
      precision: {
        type: Number,
        validator(val) {
          return val >= 0 && val === parseInt(val, 10);
        }
      }
    },

    data() {
      return {
        // this allows us to keep the count internally
        // without relying on the parent to get it
        currentNumberValue: this.value,
        innerTextValue: this.value,
        isDiscarding: false
      };
    },

    computed: {
      // We do not want to be refering to the value passed as prop
      // for the get here since doing so will only update the value of the input
      // once the event is emitted.
      // Thus, we create another variable (innerTextValue) which will be evaluated when
      // the value changed (see watch), which gives us the ability to modify a local value (innerTextValue)
      // without relying explicetly on the value prop itself.
      // Also, this allows us to see the counter change as we type.
      currentTextValue: {
        get() {
          return this.innerTextValue;
        },
        set(val) {
          // update our internal value so that the charCount can keep track of the count
          this.innerTextValue = val;
          this.onInput(val);
        }
      },
      charCount() {
        // based on our internal text value and not the value instead
        // so that we get an instant feedback on the UI
        return (this.innerTextValue || '').length;
      },
      charsLimit() {
        return this.maxlength;
      },
      isInputDisabled() {
        return this.$attrs.disabled || this.elForm.disabled;
      },

      // Input Number Methods
      typeIsNumber() {
        return this.type === 'number';
      },
      isMinDisabled() {
        return this.isInputDisabled || this._decrease(this.currentNumberValue, this.step) < this.min;
      },
      isMaxDisabled() {
        return this.isInputDisabled || this._increase(this.currentNumberValue, this.step) > this.max;
      },
      numPrecision() {
        const { currentNumberValue, step, getPrecision, precision } = this;
        const stepPrecision = getPrecision(step);
        if (precision !== undefined) {
          if (stepPrecision > precision) {
            console.warn('[Warn][InputNumber]precision should not be less than the decimal places of step');
          }
          return precision;
        } else {
          return Math.max(getPrecision(currentNumberValue), stepPrecision);
        }
      }
    },

    watch: {
      // this controls what we do when the input gets disabled by its parent
      '$attrs.disabled': function (isDisabled) {
        if (isDisabled && !this.isDiscarding) {
          // check if we are dealing with an input tag,
          // and reset input value when it is disabled
          // as we cannot make a <component> tag reactive
          if (this.type === 'input') {
            this.$refs.input.value = null;
            // nullify our internal text value
            this.currentTextValue = null;
            // notify the parent
            this.updateValue(null);
          }
        }
      },
      // watch the value and set it to our internal variable because we get it async
      value: function (val) {
        if (this.type === 'input' || this.type === 'textarea') {
          this.innerTextValue = val;
        } else if (this.type === 'number') {
          this.currentNumberValue = val;
        }
      }
    },

    methods: {
      updateValue(value) {
        // update parent data so that we can still v-model on the parent
        this.$helpers.debounceAction(() => {
          this.$emit('input', value);
        });
      },

      onInput(val) {
        this.updateValue(val);
      },

      // Input Number methods
      toPrecision(num, precision) {
        if (precision === undefined) precision = this.numPrecision;
        return parseFloat(parseFloat(Number(num).toFixed(precision)));
      },
      getPrecision(value) {
        if (value === undefined || value === null) return 0;
        const valueString = value.toString();
        const dotPosition = valueString.indexOf('.');
        let precision = 0;
        if (dotPosition !== -1) {
          precision = valueString.length - dotPosition - 1;
        }
        return precision;
      },
      _increase(val, step) {
        if (typeof val !== 'number' && val !== undefined) return this.currentNumberValue;
        const precisionFactor = Math.pow(10, this.numPrecision);
        // Solve the accuracy problem of JS decimal calculation by converting the value to integer.
        return this.toPrecision((precisionFactor * val + precisionFactor * step) / precisionFactor);
      },
      _decrease(val, step) {
        if (typeof val !== 'number' && val !== undefined) return this.currentNumberValue;
        const precisionFactor = Math.pow(10, this.numPrecision);
        return this.toPrecision((precisionFactor * val - precisionFactor * step) / precisionFactor);
      },
      increase() {
        if (this.isMaxDisabled) return;
        const value = this.currentNumberValue || 0;
        const newVal = this._increase(value, this.step);
        this.setCurrentValue(newVal);
      },
      decrease() {
        if (this.isMinDisabled) return;
        const value = this.currentNumberValue || 0;
        const newVal = this._decrease(value, this.step);
        this.setCurrentValue(newVal);
      },
      handleInputChange(e) {
        const value = e.target.value;
        const newVal = value === '' ? null : Number(value);
        if (!isNaN(newVal) || value === '') {
          this.setCurrentValue(newVal);
        } else {
          // if characters other than numbers were entered,
          // just reassign the current value
          this.$refs.inputNumber.value = this.currentNumberValue;
        }
      },
      setCurrentValue(newVal) {
        if (typeof newVal === 'number' && this.precision !== undefined) {
          newVal = this.toPrecision(newVal, this.precision);
        }
        if (newVal >= this.max) newVal = this.max;
        if (newVal <= this.min) newVal = this.min;

        this.currentNumberValue = newVal;
        this.$refs.inputNumber.value = this.currentNumberValue;
        this.updateValue(newVal);
      },
      // Because of a limitation of Vuejs regarding custom components that have DOM elements inside,
      // we cannot make their values reactives
      // so we have to manually reassign them
      onDiscardChanges() {
        if (this.$refs.inputNumber) {
          this.currentNumberValue = this.value;
          this.$refs.inputNumber.value = this.currentNumberValue;
        }
        this.isDiscarding = true;
      }
    },

    beforeDestroy() {
      EventBus.$off('FormEntity:discardChanges', this.onDiscardChanges);
    },

    mounted() {
      EventBus.$on('FormEntity:discardChanges', this.onDiscardChanges);
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  .input-wrap {
    display: flex;
    flex-direction: column;
    .el-input {
      // fixes IE11 display underneath for errors
      display: flex;
    }
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

    // avoid being able to squish the textarea when resizing
    textarea {
      min-height: 50px;
      max-height: 300px;
    }

    // Hide the up and down arrows from the input number
    // Mozilla - IE
    input[type=number] {
      appearance: textfield;
      margin: 0;
    }
    // Chrome
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
  }
</style>
