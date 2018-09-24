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
