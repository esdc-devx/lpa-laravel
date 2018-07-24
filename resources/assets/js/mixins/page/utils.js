export default {
  methods: {
    goToPage(routeName) {
      this.$helpers.throttleAction(() => {
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

      this.$router.push({
        name: routeName,
        params: currentParams
      });
    }
  }
};
