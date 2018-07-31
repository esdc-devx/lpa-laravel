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
      innerOptions() {
        return this.sorted ?
               this.options.sort((a, b) => this.$helpers.localeSort(a, b, 'name')) :
               this.options;
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

</style>
