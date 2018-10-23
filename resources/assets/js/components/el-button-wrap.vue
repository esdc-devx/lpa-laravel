<template>
  <el-tooltip v-if="tooltip" :content="tooltip" class="item" effect="dark" placement="top-start">
    <el-button v-bind="$attrs">
      <slot></slot>
    </el-button>
  </el-tooltip>

  <el-button v-else v-bind="$attrs">
    <slot></slot>
  </el-button>
</template>

<script>

export default {
  name: 'el-button-wrap',
  inheritAttrs: false,
  props: {
    tooltip: {
      type: String
    }
  }
};
</script>
