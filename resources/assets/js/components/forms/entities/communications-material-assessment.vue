<template>
  <assessment v-bind="$attrs" />
</template>

<script>
  import Assessment from '../assessment';

  export default {
    name: 'communications-material-assessment',

    components: { Assessment }
  };
</script>

<style lang="scss">

</style>
