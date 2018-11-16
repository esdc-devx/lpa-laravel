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

  export default {
    name: 'page',

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    $_veeValidate: {
      validator: 'new'
    },

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
      async loadData() {},

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
      },

      // Utils
      scrollToTop() {
        this.$parent.$el.scrollTop = 0;
        document.querySelectorAll('.el-main')[0].scrollTop = 0;
        // IE11 scroll to top
        document.querySelectorAll('.content-wrap')[0].scrollTop = 0
      },

      goToPage(routeName) {
        this.$helpers.debounceAction(() => {
          let currentParams = this.$router.currentRoute.params;
          this.$router.push({
            name: routeName,
            params: currentParams
          });
        });
      },

      goToParentPage() {
        let crumbs = this.$router.currentRoute.meta.breadcrumbs().split('/');
        let currentParams = this.$router.currentRoute.params;
        // remove last crumb (which is current one)
        crumbs.pop();
        // and then grab the second last one (which is now the last one)
        let routeName = crumbs.pop();

        // if there is no parent to go to
        if (_.isUndefined(routeName)) {
          this.$log.error('Cannot go to parent page as there are no parent.')
        }

        // if route cannot be found
        if (!this.$router.match({name: routeName, params: currentParams}).matched.length) {
          this.$log.error(`Route name: ${routeName}, cannot be found.`);
        }

        this.goToPage(routeName);
      }
    },


    mounted() {
      EventBus.$emit('App:ready');
      this.bindEvents();
    }
  };
</script>

<style lang="scss">

</style>
