<template>
  <transition name="zoomin-top">
    <span class="el-form-item__error">
      <slot></slot>
    </span>
  </transition>
</template>

<script>
  export default {
    name: 'form-error'
  };
</script>

<style lang="scss">

</style>
