export default {
  methods: {
    // This method will loop through the menu items and check whether or not
    // that the current route is a child of one of its items.
    setActiveIndex(to, menu) {
      // because of the $refs that cannot be reactive, we need to compare its items omitting the language
      let items = _.mapKeys(menu.items, (value, key) => {
        return this.removeLanguageFromString(key);
      });

      // remove the language from the route's fullPath as well
      let route = Object.assign({}, to);
      route.fullPath = this.removeLanguageFromString(route.fullPath);

      // check if the current page is found in the items paths
      if (items[route.fullPath]) {
        menu.activeIndex = items[route.fullPath].index;
      } else {
        // if not, just pop last index of path and try again
        let pathArray = route.fullPath.split('/');
        pathArray.pop();
        route.fullPath = pathArray.join('/');
        // fail safe in case we get a path that the sidebar doesn't recognize
        if (route.fullPath === '' || route.fullPath === '/') {
          return;
        }
        this.setActiveIndex(route, menu);
      }
    },

    removeLanguageFromString(str) {
      if (str.match(/\/(en|fr)\//)) {
        let strParsed = str.split('/');
        // remove the language
        strParsed.splice(1, 1);
        // make sure that no empty strings are returned
        return strParsed.join('/') || '/';
      }
      return str;
    }
  }
};
