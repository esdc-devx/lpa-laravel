<template>
  <div class="project-list content">
    <div class="controls" v-if="hasRole('owner') || hasRole('admin')">
      <el-button @click="goToPage('project-create')">{{ trans('pages.project_list.create_project') }}</el-button>
    </div>
    <entity-data-table
      entityType="project"
      :data="projects"
      :attributes="dataTableAttributes.projects"
      @rowClick="onProjectRowClick"
    />
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';

  import Page from '@components/page';

  import EntityDataTable from '@components/entity-data-table.vue';

  let namespace = 'projects';

  const loadData = async () => {
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch(`${namespace}/loadProjects`)
    );

    // Exception handled by interceptor
    try {
      await axios.all(requests);
    } catch (e) {
      if (!e.response) {
        throw e;
      }
    }
  };

  export default {
    name: 'project-list',

    extends: Page,

    components: { EntityDataTable },

    computed: {
      ...mapGetters({
        projects: `${namespace}/all`
      }),

      dataTableAttributes: {
        get() {
          return {
            projects: {
              id: {
                label: this.trans('entities.general.lpa_num'),
                minWidth: 36
              },
              name: {
                label: this.trans('entities.general.name')
              },
              organizational_unit: {
                label: this.$tc('entities.general.organizational_units'),
                areFiltersSorted: true,
                isFilterable: true
              },
              updated_at: {
                label: this.trans('entities.general.updated')
              },
              state: {
                label: this.trans('entities.general.status'),
                areFiltersSorted: true,
                isFilterable: true
              },
              'current_process.definition': {
                label: this.trans('entities.process.current'),
                isFilterable: true
              }
            }
          }
        }
      }
    },

    methods: {
      onProjectRowClick(project) {
        this.scrollToTop();
        this.$router.push(`/${this.language}/projects/${project.id}`);
      }
    },

    async beforeRouteEnter(to, from, next) {
      await loadData();
      next();
    },

    // called when url params change, e.g: language
    async beforeRouteUpdate(to, from, next) {
      await loadData();
      next();
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  .project-list {
    .el-tag {
      height: auto;
      white-space: normal;
      margin-right: 4px;
      margin-top: 2px;
      margin-bottom: 2px;
    }
    .controls {
      margin-bottom: 0;
    }
  }
</style>
