<template>
  <transition name="fade" mode="in-out">
    <div v-show="isHomePage === false" class="breadcrumb">
      <el-breadcrumb separator-class="el-icon-arrow-right">
        <el-breadcrumb-item v-for="(crumb, index) in getBreadcrumbs()" :to="{ path: crumb.path }" :key="index">{{ crumb.name }}</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
  </transition>
</template>

<script>
  import _ from 'lodash';

  export default {
    name: 'breadcrumb',

    data() {
      return {
        isLoading: true,
        breadcrumbs: this.getBreadcrumbs()
      }
    },

    computed: {
      isHomePage: function() {
        return this.breadcrumbs.length <= 1;
      }
    },

    watch: {
      $route: function (to) {
        // update the internal breadcrumbs length
        // so that the isHomePage can react
        this.breadcrumbs = this.getBreadcrumbs();
      }
    },

    methods: {
      validateMeta() {
        let matched = this.$route.matched;
        if (!this.$route.matched.length || !matched[0].meta || !matched[0].meta.breadcrumbs) {
          return false;
        }

        if (matched.length > 1) {
          throw new Error('[breadcrumb] Route childrens not supported yet');
          return false;
        }
        return true;
      },

      /**
       * Takes a regex string and replace the params by the actual values
       * Example: 'projects/:id(\d+)/process/:id(\d+)'
       *       -> 'projects/1/process/1'
       * @param {String} path
       */
      resolvePath(path) {
        let that = this;
        let partialyResolvedPath;
        let resolvedPath;
        // gather regex params
        let regexParams = _.filter(path.split('/'), segment => {
                            return segment.charAt(0) === ':';
                          });
        // if there are no params, just return the path
        if (!regexParams.length) {
          return path;
        }
        _.map(regexParams, p => {
          let index = path.split('/').indexOf(p);
          if (index !== -1) {
            p = p.match(/:\w+/g)[0];
            // first iteration grab the path in params to build up the resolved path
            // afterwards, continue with the partialyResolvedPath so that we keep any previous values
            let newPath = !_.isUndefined(partialyResolvedPath) ? partialyResolvedPath.split('/') : path.split('/');
            newPath[index] = that.$route.params[p.slice(1)];
            newPath = newPath.join('/');
            partialyResolvedPath = newPath;
          }
        });
        resolvedPath = partialyResolvedPath;
        return resolvedPath;
      },

      getBreadcrumbs() {
        // build the home crumb as a starting point
        let homeRoute = _.find(this.$router.options.routes, { name: 'home' });
        let crumbs = [{
          name: homeRoute.meta.title.call(this),
          path: '/'
        }];

        if (!this.validateMeta()) {
          return crumbs;
        }

        // gather meta data and process any locale strings before moving on
        let matched = this.$route.matched;
        let meta = matched[0].meta;
        let matchedCrumbs = meta.breadcrumbs();
        let matchedCrumbsArr = _.compact(matchedCrumbs.split('/'));

        let path;
        let title;
        let outputTitle;
        let crumb;
        let resolvedPath;

        // build up the breadcrumbs data
        for (let i = 0; i < matchedCrumbsArr.length; i++) {
          crumb = _.find(this.$router.options.routes, { name: matchedCrumbsArr[i] });
          // since the meta is executed
          // before any store modules has done loading,
          // we need to catch edge cases like deep linking
          // would produce undefined as value
          outputTitle = crumb.meta.title.call(this) === 'undefined' ? '' : crumb.meta.title.call(this);
          // try to translate the title, or just take it as value
          title = outputTitle || '';
          resolvedPath = _.compact(crumb.path.split('/'));
          // remove the language since we will add a reactive value
          resolvedPath.splice(0, 1);
          path = '/' + this.resolvePath(resolvedPath.join('/'));

          // don't add any crumb that do not have a valid path
          if (path) {
            // even if the title is empty for now,
            // it will be processed by the store later on
            crumbs.push({ name: title, path: path });
          }
        }

        this.setWindowTitle(crumbs);

        return crumbs;
      },

      setWindowTitle(crumbs) {
        let windowCrumbs = Object.assign({}, crumbs);
        // remove home crumb for window title
        delete windowCrumbs[0];
        let windowTitle = _.map(windowCrumbs, 'name').join(' - ');

        window.document.title = this.trans('base.navigation.app_name') + ' - ' + windowTitle;
      }
    }
  };
</script>

<style lang="scss">
  @import '../../sass/abstracts/vars';
  @import '../../sass/base/helpers';

  .breadcrumb {
    // make sure that the breadcrumb doesn't have any space on the left-right
    margin: auto -30px 20px;
    background-color: $--color-white;
    box-shadow: $--box-shadow-base;
    .el-breadcrumb {
      padding: 20px 30px;
      font-size: 18px;

      .el-breadcrumb__item:not(:last-child) .el-breadcrumb__inner {
        @extend .fake-link;
      }
    }
  }
</style>
