<template>
  <div class="el-select-wrap">
    <el-select
      v-bind="$attrs"
      v-loading="isLoading"
      element-loading-spinner="el-icon-loading"
      :value="innerValue"
      :disabled="disabled || isLoading"
      :name="name"
      :class="{ 'is-error': verrors.has(name) }"
      @change="updateValue"
    >
      <el-option
        v-for="item in innerOptions"
        :key="item.id"
        :label="item[optionLabel]"
        :value="item.id"
      >
        <slot :item="item" name="option-text"></slot>
      </el-option>
    </el-select>
    <form-error :name="name"></form-error>
  </div>
</template>

<script>
  import FormError from './error.vue';

  export default {
    name: 'el-select-wrap',

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    components: { FormError },

    props: {
      // @todo: remove when Element UI will add an options attribute to the select
      options: {
        type: Array
      },
      name: {
        type: String,
        required: true
      },
      optionLabel: {
        type: String,
        default: 'name'
      },
      isLoading: {
        type: Boolean,
        default: false
      },
      disabled: {
        type: Boolean,
        default: false
      },
      sorted: {
        type: Boolean,
        default: false
      },
      value: {
        type: Array | Number
      }
    },

    data() {
      return {
        isUndefined: _.isUndefined
      };
    },

    computed: {
      // make sure to create a copy of the options
      // *before* sorting the array
      // since sorting on the options directly will cause a re-render of the select
      // causing an infinite loop.
      // https://stackoverflow.com/questions/47384480/you-may-have-an-infinite-update-loop-in-a-component-render-function-warning-in
      innerOptions() {
        return this.sorted ?
               this.options.slice().sort((a, b) => this.$helpers.localeSort(a, b, this.optionLabel)) :
               this.options.slice();
      },

      innerValue() {
        if (!_.isArray(this.value) && (this.$attrs.multiple || this.$attrs.multiple === '')) {
          this.$log.error(`Attribute 'multiple' was provided but v-model is not an array for ${this.name}.`);
        }
        if (_.isArray(this.value) && _.isUndefined(this.$attrs.multiple)) {
          this.$log.error(`Attribute 'multiple' was not provided but v-model is an array for ${this.name}.`);
        }
        return this.value;
      }
    },

    methods: {
      updateValue(value) {
        this.$emit('input', value);
      }
    }
  };
</script>

<style lang="scss">
  .el-select {
    width: 100%;
    // make sure the select tags do not overflow
    .el-select__tags-text {
      max-width: 225px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      display: inline-block;
      vertical-align: middle;
    }
  }
  .el-select-dropdown__item span {
    padding-right: 30px;
  }
</style>
