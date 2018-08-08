<template>
  <div class="content forbidden">
    <h2>403 - {{ trans('errors.forbidden') }}</h2>
    <router-link :to="'/' + language">{{ trans('base.navigation.back_to_home') }}</router-link>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '@/event-bus.js';

  export default {
    name: 'forbidden',

    computed: {
      ...mapGetters([
        'language'
      ])
    },

    mounted() {
      EventBus.$emit('App:ready');
    }
  };
</script>

<style lang="scss">
</style>
