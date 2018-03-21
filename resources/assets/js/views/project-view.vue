<template>
  <div class="project-view content">
    <h2>{{ project.name }}</h2>

  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../helpers/event-bus.js';

  import LoadStatus from '../store/load-status-constants';

  let namespace = 'projects';

  export default {
    name: 'project-view',

    computed: {
      ...mapGetters({
        project: `${namespace}/viewing`
      })
    },

    methods: {
      ...mapActions({
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        loadProject: `${namespace}/loadProject`
      }),

      async triggerLoadProject() {
        this.showMainLoading();
        let id = this.$route.params.id;
        await this.loadProject(id);
        this.hideMainLoading();
      }
    },

    mounted() {
      EventBus.$emit('App:ready');
      this.triggerLoadProject();
    }
  };
</script>

<style lang="scss">
  .project-view {
    width: 100%;
    margin: 0 auto;
    h2 {
      text-align: center;
    }
  }
</style>
