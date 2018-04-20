<template>
  <transition name="fade" mode="in-out">
    <div v-show="isHomePage === false" class="breadcrumb">
      <el-breadcrumb separator-class="el-icon-arrow-right" >
        <el-breadcrumb-item v-for="crumb in getBreadcrumbs()" :to="{ path: crumb.path }" :key="crumb.id">{{ crumb.name }}</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
  </transition>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters } from 'vuex';

  export default {
    name: 'breadcrumb',

    data() {
      return {
        isLoading: true,
        breadcrumbs: this.getBreadcrumbs(),
        isHomePage: this.getBreadcrumbs().length <= 1
      }
    },

    watch: {
      '$route': function(to) {
        this.breadcrumbs = this.getBreadcrumbs();
        this.isHomePage = this.breadcrumbs.length <= 1;
      }
    },

    computed: {
      ...mapGetters([
        'language'
      ])
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

      setWindowTitle(crumbs) {
        let windowCrumbs = Object.assign({}, crumbs);
        // remove home crumb for window title
        delete windowCrumbs[0];
        let windowTitle = _.map(windowCrumbs, 'name').join(' - ');

        window.document.title = this.trans('base.navigation.app_name') + ' - ' + windowTitle;
      },

      getBreadcrumbs() {
        let homeRoute = _.find(this.$router.options.routes, { name: 'home' });
        let crumbs = [{
          name: homeRoute.meta.title.call(this),
          path: '/' + this.language
        }];
        let matched = this.$route.matched;
        if (!this.validateMeta()) {
          return crumbs;
        }

        // gather meta data and process any locale strings before moving on
        let meta = matched[0].meta;
        let matchedCrumbs = meta.breadcrumbs();
        let matchedCrumbsArr = _.compact(matchedCrumbs.split('/'));

        // get the paths from the route
        let route = _.compact(this.$route.path.split('/'));
        // and remove the language item
        // since we do not want to show it
        // example url: ['fr', 'projects'] => ['projects']
        route.splice(0, 1);

        let path = '/' + this.language;
        let title = '';
        let outputTitle = '';
        let routeName = '';
        let crumb;

        // build up the breadcrumbs data
        for (let i = 0; i < matchedCrumbsArr.length; i++) {
          routeName = matchedCrumbsArr[i];
          crumb = _.find(this.$router.options.routes, { name: routeName });
          // since the meta is executed
          // before any store modules has done loading,
          // we need to catch edge cases like deep linking
          // would produce undefined as value
          outputTitle = crumb.meta.title.call(this) === 'undefined' ? '' : crumb.meta.title.call(this);
          // try to translate the title, or just take it as value
          title = outputTitle || '';
          path += '/' + route[i];

          // don't add any crumb that do not have a valid title or path
          if (title && path) {
            crumbs.push({ name: title, path: path });
          }
        }

        this.setWindowTitle(crumbs);

        return crumbs;
      }
    }
  };
</script>

<style lang="scss">
  @import '../../sass/abstracts/vars';
  .breadcrumb {
    // make sure that the breadcrumb doesn't have any space on the left-right
    margin: auto -30px 20px;
    background-color: $--color-white;
    .el-breadcrumb {
      padding: 10px 30px;
      font-size: 18px;
      line-height: 2;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
  }
</style>
