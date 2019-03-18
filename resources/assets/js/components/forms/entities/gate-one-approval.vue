<template>
  <assessment v-bind="$attrs" />
</template>

<script>
  import Assessment from '../assessment';

  export default {
    name: 'gate-one-approval',

    components: { Assessment }
  };
</script>

<style lang="scss">

</style>
