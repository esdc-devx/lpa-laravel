<template>

</template>

<script>
  /**
   * Application flow (until rendering occurs):
   *
   * beforeRouteUpdate (vue-router)
   * beforeEnter (vue-router)
   * beforeRouteEnter (vue-router)
   * beforeResolve (vue-router)
   * ** Navigation confirmed **
   * afterEach (vue-router)
   * beforeCreate
   * beforeRouteEnter next() called (vue-router)
   * created
   * beforeMount
   * mounted
   */

  // @note: when extending in Vuejs, every objects passed in will be overriden
  // but not vuejs hooks (e.g.: created, mounted)

  import { mapGetters, mapActions, mapState } from 'vuex';

  import EventBus from '@/event-bus.js';

  import PageUtils from '@mixins/page/utils.js';

  export default {
    name: 'page',

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    $_veeValidate: {
      validator: 'new'
    },

    mixins: [ PageUtils ],

    // events to bind
    events: {},

    data() {
      return {};
    },

    computed: {
      ...mapState([
        'shouldConfirmBeforeLeaving'
      ]),
      ...mapGetters({
        language: 'language',
        isMainLoading: 'isMainLoading',
        hasRole: 'users/hasRole',
        isCurrentUser: 'users/isCurrentUser'
      })
    },

    methods: {
      ...mapActions([
        'confirmBeforeLeaving'
      ]),

      /**
       * Async fetching function that provides a switch
       * to handle initial load duty
       */

      async loadData(isInitialLoad = true) {},

      async loadPermissions() {},

      onLanguageUpdate() {
        this.loadData();
      },

      bindEvents() {
        // loop through listeners of the current page,
        // and turn them on
        if (!_.isEmpty(this.$options.events)) {
          for (const eventName in this.$options.events) {
            const eventListener = this.$options.events[eventName];
            EventBus.$on(eventName, this[eventListener]);
          }
        }
      },

      /**
       * Destroy any events we might be listening
       * so that they do not get called while being on another page
      */
      destroyEvents() {
        // loop through listeners of the current page,
        // and turn them off
        if (!_.isEmpty(this.$options.events)) {
          for (const eventName in this.$options.events) {
            const eventListener = this.$options.events[eventName];
            EventBus.$off(eventName, this[eventListener]);
          }
        }
      }
    },

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      this.onLanguageUpdate();
      next();
    },

    mounted() {
      EventBus.$emit('App:ready');
      this.bindEvents();
    }
  };
</script>

<style lang="scss">

</style>
