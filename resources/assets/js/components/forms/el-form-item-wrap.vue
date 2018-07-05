<template>
  <el-form-item
    v-if="label || $slots.label"
    :for="labelFor"
    :class="[classes, { 'is-required': required }]">
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
      errors: {
        type: Object | Boolean,
        default: false
      },
      required: {
        type: Boolean,
        default: undefined
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
