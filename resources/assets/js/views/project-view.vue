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

      getProject() {
        // look up the project in the store first
        if (this.$store.getters[`${namespace}/projectLoadStatus`] === LoadStatus.LOADING_SUCCESS) {
          this.isLoading = false;
          return this.$store.getters.project;
        }

        // project not found, means we might be coming from a deep link
        let id = this.$route.params.id;
        return this.loadProject(id)
          .then(() => {
            EventBus.$emit('App:ready');
            this.isLoading = false;
            return this.getProject();
          });
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
