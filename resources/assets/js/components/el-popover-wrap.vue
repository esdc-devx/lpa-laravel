<template>
  <el-popover
    popper-class="el-popover-wrap"
    placement="bottom-start"
    width="200"
    trigger="hover">
      <span slot="reference"><i class="el-icon-lpa-popover"></i></span>
      <p v-if="description">
        <b>{{ trans('components.popover.description') }}</b>
        <br>
        {{ description }}
      </p>
      <p v-if="help">
        <b>{{ trans('components.popover.help') }}</b>
        <br>
        {{ help }}
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
  @import '../../sass/abstracts/vars';

  .el-popover-wrap {
    text-align: left;
    p {
      margin-top: 0;
      &:last-of-type {
        margin-bottom: 0;
      }
    }
  }
</style>
