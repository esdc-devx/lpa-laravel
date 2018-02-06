<template>
  <div id='project-view' class="content" v-loading="isLoading">
    <h2>{{ project.name }}</h2>

  </div>
</template>

<script>
  import { mapActions } from 'vuex';

  import LoadStatus from '../store/load-status-constants';

  export default {
    name: 'ProjectView',

    computed: {
      project() {
        return this.getProject();
      }
    },

    data() {
      return {
        isLoading: true
      }
    },

    methods: {
      ...mapActions([
        'loadProject'
      ]),

      getProject() {
        // look up the project in the store first
        if (this.$store.getters.projectLoadStatus === LoadStatus.LOADING_SUCCESS) {
          this.isLoading = false;
          return this.$store.getters.project;
        }

        // project not found, means we might be coming from a deep link
        let id = this.$route.params.id;
        return this.loadProject(id)
          .then(() => {
            this.isLoading = false;
            return this.getProject();
          })
          .catch(({ response }, e) => {
            if (response.data && response.data.errors) {
              // @todo: save error message in Store
            }
            console.error('[project-view][loadProject]: ' + e);
            alert('[project-view][loadProject]: ' + e);
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
