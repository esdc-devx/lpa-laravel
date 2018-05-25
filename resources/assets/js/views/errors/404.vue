<template>
  <div class="content not-found">
    <h2>404 - {{ trans('errors.not_found') }}</h2>
    <router-link :to="'/' + language">{{ trans('base.navigation.back_to_home') }}</router-link>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../../event-bus.js';

  export default {
    name: 'not-found',

    computed: {
      ...mapGetters([
        'language'
      ])
    },

    methods: {
      ...mapActions([
        'hideMainLoading'
      ])
    },

    mounted() {
      EventBus.$emit('App:ready');
      this.hideMainLoading();
    }
  };
</script>

<style lang="scss">
</style>
