<template>
  <div class="el-select-other-wrap">
    <el-select-wrap
      :value.sync="modelSelect"
      :isLoading="isLoading"
      :name="nameSelect"
      v-validate="validateSelect"
      :data-vv-as="dataVVasSelect"
      :options="options"
      :disabled="checked && !multiple"
      @input="updateSelectValue($event)"
      :multiple="multiple"
      :sorted="sorted"
    />
    <div class="el-input-other-wrap">
      <el-checkbox
        v-model="checked"
        :value="isChecked"
        @change="onCheckboxChange">
          <span v-if="multiple">{{ trans('entities.form.others') }}</span>
          <span v-else>{{ trans('entities.form.other') }}</span>
      </el-checkbox>
      <input-wrap
        ref="input"
        :name="nameOther"
        v-validate="validateOther"
        :data-vv-as="dataVVasOther"
        :disabled="!isChecked"
        :value="modelOther"
        :maxlength="maxlength"
        :type="type"
        v-on="inputListeners">
          <slot></slot>
      </input-wrap>
    </div>
  </div>
</template>

<script>
  import FormError from './error.vue';

  import ElSelectWrap from './el-select-wrap';
  import InputWrap from './input-wrap';

  export default {
    name: 'el-select-other-wrap',

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    components: { FormError, ElSelectWrap, InputWrap },

    props: {
      modelSelect: {
        type: Array | Number,
        required: true
      },
      options: {
        type: Array,
        required: true
      },
      modelOther: {
        type: String | undefined | null,
        required: true
      },
      nameSelect: {
        type: String,
        required: true
      },
      nameOther: {
        type: String,
        required: true
      },
      dataVVasSelect: {
        type: String,
        default: this.nameSelect
      },
      dataVVasOther: {
        type: String,
        default: function () {
          return this.trans('entities.form.other');
        }
      },
      validateSelect: {
        type: String | Object,
        default: ''
      },
      validateOther: {
        type: String | Object,
        default: ''
      },
      isLoading: {
        type: Boolean,
        default: false
      },
      isChecked: {
        type: Boolean,
        default: false
      },
      sorted: {
        type: Boolean,
        default: false
      },
      multiple: {
        type: Boolean,
        default: false
      },
      type: {
        type: String,
        default: 'input',
        validator: function (value) {
          // The value must match one of these strings
          return ['input', 'textarea'].indexOf(value) !== -1
        }
      },
      maxlength: {
        type: String,
        default: null
      }
    },

    computed: {
      checked: {
        get() {
          return this.isChecked;
        },
        set(val) {
          this.$emit('update:isChecked', val);
        }
      },
      // taken from: https://vuejs.org/v2/guide/components-custom-events.html#Binding-Native-Events-to-Components
      inputListeners: function () {
        var that = this;
        return Object.assign({},
          // We add all the listeners from the parent
          this.$listeners,
          // Then we can add custom listeners or override the
          // behavior of some listeners.
          {
            // This ensures that the component works with v-model
            input: function (value) {
              that.updateInputValue(value);
            }
          }
        )
      }
    },

    watch: {
      isChecked: function (val) {
        // if the other is checked and can only select 1 value
        // the select should be disabled and remove its selected value
        if (val && !this.multiple) {
          this.updateSelectValue(null);
        }
      }
    },

    methods: {
      updateSelectValue(value) {
        // update parent data so that we can still v-model on the parent
        this.$emit('update:modelSelect', value);
      },

      updateInputValue(value) {
        // update parent data so that we can still v-model on the parent
        this.$emit('update:modelOther', value);
      },

      onCheckboxChange(checked) {
        // update parent property
        if (checked) {
          this.$nextTick(() => {
            let inputEl = this.$refs.input.$el.querySelector('input');
            let textareaEl = this.$refs.input.$el.querySelector('textarea');
            let el = inputEl || textareaEl;
            el.focus();
          });
        } else {
          // empty the input so that we send null
          // when the checkbox is unchecked
          this.updateInputValue(null);
        }
      }
    },

    mounted() {
      this.checked = !!this.modelOther;
    }
  };
</script>

<style lang="scss">
  .el-select-other-wrap {
    display: flex;
    .el-input-other-wrap {
      flex: 1;
      display: flex;
      .el-checkbox {
        margin-left: 20px;
        margin-right: 20px;
        display: inline-table;
        // make sure the line-height is constant here
        // as it needs to be vertically centered with its folowing input
        line-height: 40px !important;
      }
      .input-wrap {
        flex: 1;
      }
    }
  }
</style>
