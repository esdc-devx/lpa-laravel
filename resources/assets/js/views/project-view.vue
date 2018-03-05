<template>
  <div class="project-view content" v-loading="isLoading">
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

    data() {
      return {
        isLoading: true
      }
    },

    methods: {
      ...mapActions({
        loadProject: `${namespace}/loadProject`
      }),

      async getProject() {
        let id = this.$route.params.id;
        await this.loadProject(id)
        EventBus.$emit('App:ready');
        this.isLoading = false;
      }
    },

    created() {
      this.getProject();
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
