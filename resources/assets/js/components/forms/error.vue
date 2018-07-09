<template>
  <div class="input-errors">
    <transition name="el-zoom-in-top">
      <span class="el-form-item__error" v-for="error in verrors.collect(name)" :key="error.id">
        {{ error }}
      </span>
    </transition>
  </div>
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
