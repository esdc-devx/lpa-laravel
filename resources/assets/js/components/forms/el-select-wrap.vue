<template>
  <el-select
    v-bind="$attrs"
    v-loading="isLoading"
    element-loading-spinner="el-icon-loading"
    :value="value"
    :disabled="disabled || isLoading"
    :name="name"
    value-key="name"
    :class="{ 'is-error': verrors.has(name) }"
    @change="updateValue">
    <el-option
      v-for="item in innerOptions"
      :key="item.id"
      :label="item.name"
      :value="item.id">
    </el-option>
  </el-select>
</template>

<script>
  export default {
    name: 'el-select-wrap',

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      // @todo: remove when Element UI will add an options attribute to the select
      options: {
        type: Array,
        required: true
      },
      name: {
        type: String,
        required: true
      },
      isLoading: {
        type: Boolean,
        default: false
      },
      disabled: {
        type: Boolean,
        default: false
      },
      sorted: Boolean,
      value: Array | Number
    },

    computed: {
      // make sure to create a copy of the options
      // *before* sorting the array
      // since sorting on the options directly will cause a re-render of the select
      // causing an infinite loop.
      // https://stackoverflow.com/questions/47384480/you-may-have-an-infinite-update-loop-in-a-component-render-function-warning-in
      innerOptions() {
        return this.sorted ?
               this.options.slice().sort((a, b) => this.$helpers.localeSort(a, b, 'name')) :
               this.options.slice();
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
    width: 300px;
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
</style>
