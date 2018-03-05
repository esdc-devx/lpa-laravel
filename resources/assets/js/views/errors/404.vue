<template>
  <div class="content not-found">
    <h2>404 - {{ trans('navigation.not_found') }}</h2>
    <router-link :to="'/' + language">{{ trans('navigation.back_to_home') }}</router-link>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import EventBus from '../../helpers/event-bus.js';

  export default {
    name: 'not-found',
    computed: {
      ...mapGetters([
        'language'
      ])
    },
    created() {
      EventBus.$emit('App:ready');
    }
  };
</script>

<style lang="scss">
</style>
