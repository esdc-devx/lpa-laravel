<template>
  <div class="content">
    <h1>My Profile</h1>
  </div>
</template>

<script>
  import EventBus from '../event-bus.js';

  export default {
    name: 'my-profile',

    mounted() {
      EventBus.$emit('App:ready');
    }
  };
</script>

<style>

</style>
