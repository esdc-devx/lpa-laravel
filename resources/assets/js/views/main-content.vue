<template>
  <keep-alive>
    <transition :name="transitionName">
      <router-view/>
    </transition>
  </keep-alive>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    name: 'main-content',

    data() {
      return {
        transitionName: 'fade'
      }
    },

    computed: {
      ...mapGetters([
        'language'
      ])
    },

    watch: {
      // handles the transition when changing pages
      $route(to, from) {
        // example url transitions:
        //    /en/projects => /en/projects/1:       swipe
        //    /en/projects/1 => /en/projects:       swipe
        //    /en/projects/1 => /en/admin/users:    fade
        //    /en/admin/users => /en:               fade

        const toDepth = to.path.split('/').length;
        const fromDepth = from.path.split('/').length;
        let isSameLevel = toDepth === fromDepth;
        let isFromOrToHome = from.path === '/' || from.path === `/${this.language}` || to.path === `/${this.language}`;

        // check for direct hierarchy
        let isParentChild = this.isParentChild(to, from);
        // when same level, => fade
        // when parent-child, => swipe
        if (isSameLevel || !isParentChild || isFromOrToHome) {
          this.transitionName = 'fade';
        } else {
          this.transitionName = toDepth < fromDepth ? 'swipe-right' : 'swipe-left';
        }
      }
    },

    methods: {
      isParentChild(to, from) {
        // make sure paths are loaded
        if (to.meta.breadcrumbs && from.meta.breadcrumbs) {
          // as the path can contain inaccessible path,
          // we have to base the logic on the breadcrumb instead
          const toSplit = to.meta.breadcrumbs().split('/');
          const fromSplit = from.meta.breadcrumbs().split('/');
          const toDepth = toSplit.length;
          const fromDepth = fromSplit.length;

          // this basically checks for when we are going back and forward
          // for backwards actions: if the FROM last slug is equal to the TO last - 1 slug
          // for forwards actions:  if the TO last slug is equal to the FROM last - 1 slug
          // then this logic is duplicated
          if (fromSplit[fromDepth - 1] === toSplit[toDepth - 2] || toSplit[toDepth - 1] === fromSplit[fromDepth - 2]) {
            return true;
          }
          return false;
        }
        return false;
      }
    }
  };
</script>

<style lang="scss">

</style>
