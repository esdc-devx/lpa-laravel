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

  import { mapState, mapGetters, mapMutations } from 'vuex';

  export default {
    name: 'page',

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    $_veeValidate: {
      validator: 'new'
    },

    data() {
      return {};
    },

    computed: {
      ...mapState([
        'shouldConfirmBeforeLeaving',
        'language',
        'isMainLoading',
        'waitForLogout'
      ]),

      ...mapGetters({
        hasRole: 'entities/users/hasRole',
        isCurrentUser: 'entities/users/isCurrentUser'
      })
    },

    watch: {
      '$store.state.language': function (to, from) {
        this.onLanguageUpdate();
      },

      waitForLogout: function (val) {
        if (val) {
          this.beforeLogout();
        }
      }
    },

    methods: {
      ...mapMutations([
        'setShouldConfirmBeforeLeaving',
        'setWaitForLogout'
      ]),

      async loadData() {},

      onLanguageUpdate() {
        if (this.resetErrors) {
          // since on submit the backend returns already translated error messages,
          // we need to reset the validator messages so that on next submit
          // the messages are in the correct language
          this.resetErrors();
        }
        if (this.loadData) {
          this.loadData.apply(this);
        }
      },

      // Utils
      scrollToTop() {
        document.querySelectorAll('.el-main')[0].scrollTop = 0;
        // IE11 scroll to top
        document.querySelectorAll('.content-wrap')[0].scrollTop = 0
      },

      goToPage(routeName) {
        this.$helpers.debounceAction(() => {
          let currentRoute = this.$router.currentRoute
          let currentParams = currentRoute.params;

          this.scrollToTop();
          // if current params belong to a 404 or 403 route
          // then just redirect to home page
          if (['forbidden', 'not-found'].some(s => currentRoute.name.includes(s))) {
            this.$router.push({
              name: routeName,
              params: {
                lang: this.language
              }
            });
          } else {
            this.$router.push({
              name: routeName,
              params: currentParams
            });
          }
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
      },

      beforeLogout() {
        this.setWaitForLogout(false);
      }
    },


    mounted() {
      this.$emit('app:ready');
    }
  };
</script>

<style lang="scss">

</style>
