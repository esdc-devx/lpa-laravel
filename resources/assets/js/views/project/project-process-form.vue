<template>
  <div class="project-process-form content">
    Form
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../../event-bus.js';

  import HttpStatusCodes from '../../axios/http-status-codes';

  let namespace = 'projects';

  export default {
    name: 'project-process-form',

    data() {
      return {}
    },

    computed: {
      ...mapGetters({
        language: 'language',
      }),
    },

    methods: {
      ...mapActions({
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
      }),
    },

    // beforeRouteLeave(to, from, next) {
    //   // Destroy any events we might be listening
    //   // so that they do not get called while being on another page
    //   EventBus.$off('Store:languageUpdate', this.fetch);
    //   next();
    // },

    mounted() {
      EventBus.$emit('App:ready');
      // EventBus.$on('Store:languageUpdate', this.fetch);
      // this.fetch();
    }
  };
</script>

<style lang="scss">
  @import '../../../sass/abstracts/vars';
  @import '../../../sass/abstracts/functions';
  @import '../../../sass/abstracts/mixins/helpers';
  @import '../../../sass/base/helpers';

  .project-process-form {
    margin: 0 auto;
  }
</style>
