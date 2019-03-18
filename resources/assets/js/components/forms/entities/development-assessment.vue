<template>
  <assessment v-bind="$attrs" />
</template>

<script>
  import Assessment from '../assessment';

  export default {
    name: 'development-assessment',

    components: { Assessment }
  };
</script>

<style lang="scss">

</style>
