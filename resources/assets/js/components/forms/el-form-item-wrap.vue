<template>
  <el-form-item
    v-if="label || $slots.label"
    :for="labelFor"
    :class="[classes, { 'is-required': required, 'is-error': verrors.collect(labelFor).length }]">
      <slot name="label">
        <span slot="label">
          {{ label }}
          <slot name="label-addons"></slot>
        </span>
      </slot>
      <slot></slot>
  </el-form-item>
</template>

<script>
  export default {
    name: 'el-form-item-wrap',

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      label: String,
      prop: String,
      classes: Array,
      required: {
        type: Boolean,
        default: false
      },
      for: String
    },

    computed: {
      labelFor () {
        return this.for || this.prop
      }
    }
  }
</script>

<style lang="scss">

</style>
