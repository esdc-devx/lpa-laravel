<template>
  <div class="el-select-other-wrap">
    <el-select-wrap
      :value.sync="modelSelect"
      :isLoading="isLoading"
      :name="nameSelect"
      v-validate="validateSelect"
      :data-vv-as="dataVvAsSelect"
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
        @change="onCheckboxChange"
      >
        <span v-if="multiple">{{ trans('entities.form.others') }}</span>
        <span v-else>{{ trans('entities.form.other') }}</span>
      </el-checkbox>
      <input-wrap
        v-if="!localizable"
        ref="input"
        :name="nameOther"
        v-validate="validateOther"
        :data-vv-as="dataVvAsOther"
        :disabled="!isChecked"
        :value="modelOther"
        :maxlength="maxlength"
        :type="innerType"
        v-on="inputListeners"
      >
        <slot></slot>
      </input-wrap>
      <input-localized
        v-else
        ref="input"
        :nameEn="nameOtherEn"
        :nameFr="nameOtherFr"
        :modelEn.sync="innerModelOtherEn"
        :modelFr.sync="innerModelOtherFr"
        :validate="validateOther"
        :dataVvAsEn="dataVvAsOtherEn"
        :dataVvAsFr="dataVvAsOtherFr"
        :disabled="!isChecked"
        :maxlength="maxlength"
        :type="innerType"
      />
    </div>
  </div>
</template>

<script>
  import FormError from './error.vue';

  import ElSelectWrap from './el-select-wrap';
  import InputWrap from './input-wrap';
  import InputLocalized from './input-localized';

  export default {
    name: 'el-select-other-wrap',

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    components: { FormError, ElSelectWrap, InputWrap, InputLocalized },

    props: {
      modelSelect: {
        type: Array | Number,
        required: true
      },
      options: {
        type: Array
      },
      modelOther: {
        type: String | undefined | null
      },
      modelOtherEn: {
        type: String | undefined | null
      },
      modelOtherFr: {
        type: String | undefined | null
      },
      nameSelect: {
        type: String,
        required: true
      },
      nameOther: {
        type: String
      },
      nameOtherEn: {
        type: String
      },
      nameOtherFr: {
        type: String
      },
      dataVvAsSelect: {
        type: String,
        default() {
          return this.nameSelect;
        }
      },
      dataVvAsOther: {
        type: String,
        default() {
          return this.trans('entities.form.other');
        }
      },
      dataVvAsOtherEn: {
        type: String,
        default() {
          return this.nameOtherEn;
        }
      },
      dataVvAsOtherFr: {
        type: String,
        default() {
          return this.nameOtherFr;
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
      },
      localizable: {
        type: Boolean,
        default: false
      }
    },

    data() {
      return {
        // arbitrary number considering the facts that:
        //    - the select is on the same line as the inputs
        //    - that the inputs may be prepended and appended
        // this shorten the space for character input,
        // which results in this safe value
        maxCharartersThreshold: 50
      };
    },

    computed: {
      innerType() {
        // force a textarea when the threshold is hit
        return this.maxlength > this.maxCharartersThreshold ? 'textarea' : this.type;
      },
      innerModelOtherEn: {
        get() {
          return this.modelOtherEn;
        },
        set(val) {
          // affect the computed value so that our field is in sync
          this.$emit('update:modelOtherEn', val);
        }
      },
      innerModelOtherFr: {
        get() {
          return this.modelOtherFr;
        },
        set(val) {
          // affect the computed value so that our field is in sync
          this.$emit('update:modelOtherFr', val);
        }
      },
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
      },

      validatePropsWithDynamicRules() {
        if (this.localizable) {
          if (!this.$props.hasOwnProperty('modelOtherEn')) {
            this.$log.error('Missing required prop: "modelOtherEn"');
          }
          if (!this.$props.hasOwnProperty('modelOtherFr')) {
            this.$log.error('Missing required prop: "modelOtherFr"');
          }
          if (!this.$props.hasOwnProperty('nameOtherEn')) {
            this.$log.error('Missing required prop: "nameOtherEn"');
          }
          if (!this.$props.hasOwnProperty('modelOtherEn')) {
            this.$log.error('Missing required prop: "modelOtherEn"');
          }
          if (!this.$props.hasOwnProperty('modelOtherFr')) {
            this.$log.error('Missing required prop: "modelOtherFr"');
          }
        } else {
          if (!this.$props.hasOwnProperty('modelOther')) {
            this.$log.error('Missing required prop: "modelOther"');
          }
          if (!this.$props.hasOwnProperty('nameOther')) {
            this.$log.error('Missing required prop: "nameOther"');
          }
        }
      }
    },

    created() {
      // manually validate props
      // that contains dynamic validation
      this.validatePropsWithDynamicRules();
    },

    mounted() {
      if (this.localizable) {
        this.checked = !!this.modelOtherEn || !!this.modelOtherFr;
      } else {
        this.checked = !!this.modelOther;
      }
    }
  };
</script>

<style lang="scss">
  .el-select-other-wrap {
    display: flex;
    .el-select {
      width: 300px;
    }
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
      .input-wrap, .input-localized {
        flex: 1;
      }
    }
  }
</style>
