<template>
  <el-popover
    popper-class="el-popover-wrap"
    placement="bottom-start"
    width="auto"
    trigger="hover">
      <span slot="reference"><i class="el-icon-lpa-popover"></i></span>
      <p v-if="description">
        <b>{{ trans('entities.general.description') }}</b>
        <br>
        <span v-html="description"></span>
      </p>
      <p v-if="help">
        <b>{{ trans('entities.general.help') }}</b>
        <br>
        <span v-html="help"></span>
      </p>
  </el-popover>
</template>

<script>
  export default {
    name: 'el-popover-wrap',

    inheritAttrs: false,

    props: {
      description: {
        type: String,
        default: ''
      },
      help: {
        type: String,
        default: ''
      }
    }
  };
</script>

<style lang="scss">
  .el-popover-wrap {
    text-align: left;
    &.el-popover {
      max-width: 400px;
    }
    p {
      margin-top: 0;
      &:last-of-type {
        margin-bottom: 0;
      }
    }
    ul {
      margin: 0;
      li span {
        font-weight: 500;
      }
    }
  }
</style>
