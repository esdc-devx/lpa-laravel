<template>
  <div id='project-view' class="content" v-loading="isLoading">
    <h2>{{ project.name }}</h2>

  </div>
</template>

<script>
  import LoadStatus from '../store/load-status-constants';
  import EventBus from '../components/event-bus.js';

  export default {
    name: 'ProjectView',

    computed: {
      project() {
        return this.getProject();
      }
    },
    data() {
      return {
        isLoading: true,
        activeStep: 0
      }
    },

    methods: {
      getProject() {
        // look up the project in the store first
        if (this.$store.getters.projectLoadStatus === LoadStatus.LOADING_SUCCESS) {
          this.isLoading = false;
          return this.$store.getters.project;
        } else {
          // if not found, we might be coming from a deep link
          return this.loadProject();
        }
      },

      loadProject() {
        let id = this.$route.params.id;
        return this.$store.dispatch('loadProject', id).then(() => {
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

<style lang="scss" scoped>
  #project-view {
    width: 100%;
    margin: 0 auto;
    h2 {
      text-align: center;
    }
  }
</style>
