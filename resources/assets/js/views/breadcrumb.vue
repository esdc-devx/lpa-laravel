<template>
  <transition name="fade" mode="out-in">
    <div v-show="isHomePage === false">
      <el-breadcrumb separator-class="el-icon-arrow-right" >
        <el-breadcrumb-item :to="{ path: '/' + language + crumb.path }" v-for="crumb in getBreadcrumbs()" :key="crumb.id">
          {{ crumb.name }}
        </el-breadcrumb-item>
      </el-breadcrumb>
      <hr>
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
        if (!this.$route.matched.length || !matched[0].meta) {
          return false;
        }

        if (matched.length > 1) {
          console.error('[breadcrumb] Route childrens not supported yet');
          return false;
        }
        return true;
      },

      setWindowTitle(crumbs) {
        let windowCrumbs = Object.assign({}, crumbs);
        // remove home crumb for window title
        delete windowCrumbs[0];
        let windowTitle = _.map(windowCrumbs, 'name').join(' - ');

        window.document.title = this.trans('navigation.app_name') + ' - ' + windowTitle;
      },

      getBreadcrumbs() {
        let crumbs = [];
        let matched = this.$route.matched;
        if (!this.validateMeta()) {
          return crumbs;
        }

        // gather meta data and process any locale strings before moving on
        let meta = matched[0].meta;
        let matchedCrumbs = meta.breadcrumbs();
        let matchedCrumbsArr = matchedCrumbs.split('/');
        for (let i = 0; i < matchedCrumbsArr.length; i++) {
          if (matchedCrumbsArr[i].match(/\{.*\}/)) {
            matchedCrumbsArr[i] = this.trans(matchedCrumbsArr[i].replace(/{|}/g, ''));
          }
        }

        // get the paths from the route
        let route = (this.$route.path).split('/');
        // and remove the language item
        // since we do not want to show it
        // example url: ['', 'fr', 'projects'] => ['', 'projects']
        route.splice(1, 1);

        let path = '';
        let title = '';
        // build up the breadcrumbs data
        for (let i = 0; i < route.length; i++) {
          // since the meta is executed
          // before any store modules has done loading,
          // we need to catch edge cases like deep linking
          // would produce undefined as value
          title = matchedCrumbsArr[i] === 'undefined' ? '' : matchedCrumbsArr[i];
          path = '/' + route[i];

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

<style lang="scss" scoped>
  @import '../../sass/vendors/element-variables';
  .el-breadcrumb {
    font-size: 18px;
    line-height: 2;
    margin-bottom: 10px;
    text-transform: uppercase;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
</style>
