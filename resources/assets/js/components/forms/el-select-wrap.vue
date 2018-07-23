<template>
  <el-select
    remote
    v-bind="$attrs"
    v-loading="isLoading"
    :value="value"
    :disabled="isLoading"
    element-loading-spinner="el-icon-loading"
    :name="name"
    value-key="name"
    :class="{ 'is-error': verrors.has(name) }"
    @change="updateValue">
    <el-option
      v-for="item in options"
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
      value: Array | Number
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
