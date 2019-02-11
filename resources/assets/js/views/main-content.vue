<template>
  <keep-alive>
    <transition :name="transitionName">
      <router-view :class="['content', $route.name]"/>
    </transition>
  </keep-alive>
</template>

<script>
  import { mapState } from 'vuex';

  export default {
    name: 'main-content',

    data() {
      return {
        transitionName: 'fade'
      }
    },

    computed: {
      ...mapState([
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
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  $el-main-padding: 30px;

  .content {
    margin: 0 auto;
    position: absolute;
    // allocate space under breadcrumb
    top: 20px;
    // remove padding-left and right
    width: calc(100% - (#{$el-main-padding} * 2));
    // allow transition between contents
    // to keep their position while transitioning
    padding: 0 30px;
    padding-bottom: 20px;

    h2 {
      display: flex;
      align-items: center;
      flex: 1;
      margin: 0;
      i {
        @include size($svg-icons-size-medium);
        margin-right: 10px;
      }
    }
  }
</style>
