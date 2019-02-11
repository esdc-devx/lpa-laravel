<template>
  <el-card
    shadow="never"
    :class="['el-card-wrap', { 'no-body': !$slots.default }]"
  >
    <div slot="header">
      <h2>
        <template v-if="icon">
          <i :class="icon"></i>
        </template>
        {{ headerTitle }}
      </h2>
      <div v-if="$slots.controls" class="controls">
        <slot name="controls"></slot>
      </div>
    </div>
    <slot></slot>
  </el-card>
</template>

<script>
  export default {
    name: 'el-card-wrap',

    props: {
      icon: {
        type: String
      },
      headerTitle: {
        type: String,
        required: true
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .el-card-wrap {
    &.no-body {
      .el-card__header {
        border-bottom: 0;
      }
      .el-card__body {
        display: none;
      }
    }
    h2 {
      display: flex;
      align-items: center;
      flex: 1;
      margin: 0;
      i {
        @include size($svg-icons-size-medium);
        margin-right: 10px;
      }
    }

    .el-card__header > div {
      display: flex;
      justify-content: space-between;
      .controls {
        display: flex;
        align-items: center;
        float: right;
        margin-bottom: 0;
        // allow space before the controls
        // so that when the h2 text is long enough,
        // it doesn't interfere with the controls
        margin-left: 20px;
      }
    }

    .el-card__body {
      display: flex;
      flex-flow: wrap;
      padding: 15px 10px;
      align-content: space-between;
      > * {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding: 5px 10px;
        margin: 0px;
        box-sizing: border-box;
      }
      dl {
        flex-basis: 25%;
        max-width: 25%; // Patch for IE11. See https://github.com/philipwalton/flexbugs/issues/3#issuecomment-69036362
        dt {
          font-weight: bold;
        }
        dd {
          margin: 0;
          line-height: 1.2;
        }
        dd + dd {
          margin-top: 0;
        }
      }
    }
  }

</style>
