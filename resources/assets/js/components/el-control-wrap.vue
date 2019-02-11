<template>
  <el-tooltip
    v-if="tooltip"
    :content="tooltip"
    effect="dark"
    placement="top-start"
  >
    <component
      :is="componentName"
      v-bind="$attrs"
      :icon="icon"
      :value="value"
      @change="updateValue"
    >
      <i v-if="icon && componentName === 'el-checkbox-button'" :class="icon"></i>
      <slot></slot>
    </component>
  </el-tooltip>

  <component
    v-else
    :is="componentName"
    v-bind="$attrs"
    :icon="icon"
    :value="value"
    @change="updateValue"
  >
    <i v-if="icon && componentName === 'el-checkbox-button'" :class="icon"></i>
    <slot></slot>
  </component>
</template>

<script>

export default {
  name: 'el-control-wrap',
  inheritAttrs: false,
  props: {
    tooltip: {
      type: String
    },
    componentName: {
      type: String,
      required: true
    },
    icon: {
      type: String
    },
    value: {
      // this is for checkbox-button component
      type: Boolean
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
  .el-checkbox-button:hover .el-checkbox-button__inner {
    border-color: #cbcad5;
    background-color: #eeedf1;
    transition: .1s;
  }
</style>
