<template>
  <transition name="el-zoom-in-top">
    <el-card shadow="never" class="info-box">
      <slot name="header" slot="header"></slot>
      <slot></slot>
    </el-card>
  </transition>
</template>

<script>
  export default {
    name: 'info-box'
  };
</script>

<style lang="scss">
  .info-box {
    h2 {
      margin-right: 20px;
      flex: 1;
    }
    .el-card__body {
      display: flex;
      flex-flow: wrap;
      > * {
        display: flex;
        flex-direction: column;
        flex: 1;
        justify-content: flex-start;
        margin-bottom: 0;
      }
      dl {
        padding-right: 20px;
        margin-top: 0px;
        box-sizing: border-box;
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
