<template>
  <transition name="fade" mode="out-in">
    <div v-if="breadcrumbs.length > 1">
      <el-breadcrumb separator-class="el-icon-arrow-right">
        <el-breadcrumb-item :to="{ path: '/' + lang + crumb.path }" v-for="crumb in breadcrumbs" :key="crumb.id">
          {{ crumb.name }}
        </el-breadcrumb-item>
      </el-breadcrumb>
      <hr>
    </div>
  </transition>
</template>

<script>
  import EventBus from '../components/event-bus';

  export default {
    name: 'breadcrumb',

    computed: {
      lang() {
        return this.$store.getters.language;
      },
      breadcrumbs() {
        // debugger;
        let trans = this.trans;

        let path = '';
        let title = 'Home';

        let crumbs = [ { name: title, path: '/'} ];
        if (!this.$route.matched.length) {
          return [];
        }

        // get the paths from the route
        let route = (this.$route.path).split('/');
        // and remove the language item
        // since we do not want to show it
        route.splice(1, 1);

        let currentPageTitle = trans(this.$route.meta.title);
        let matched = (currentPageTitle || "").split('/');

        for (let i = 1; i < route.length; i++) {
          let name = (matched[i] || _.capitalize(route[i]));
          if (route[i] === '') {
            continue;
          }

          title += ' : ' + name;
          path  += '/' + route[i];

          crumbs.push({ name: name, path: path });
        }

        window.document.title = trans('navigation.app_name') + ' - ' + title;

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
