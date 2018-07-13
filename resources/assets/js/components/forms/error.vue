<template>
  <transition-group name="el-zoom-in-top" class="input-errors" tag="div">
    <span class="el-form-item__error" v-for="(error, index) in verrors.collect(name)" :key="index">
      {{ error }}
    </span>
  </transition-group>
</template>

<script>
  export default {
    name: 'form-error',

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      name: String
    }
  };
</script>

<style lang="scss">
  .input-errors {
    display: flex;
    flex-direction: column;
    flex: 1;
  }
</style>
