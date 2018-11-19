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
      ...mapActions({
        loadProjects: `${namespace}/loadProjects`
      }),

      onProjectRowClick(project) {
        this.scrollToTop();
        this.$router.push(`/${this.language}/projects/${project.id}`);
      },

      async loadData() {
        try {
          await this.loadProjects();
        } catch (e) {
          // Exception handled by interceptor
          if (!e.response) {
            throw e;
          }
        }
      },

      async onLanguageUpdate() {
        await this.loadData();
      }
    },

    beforeRouteEnter(to, from, next) {
      next(vm => {
        vm.loadData();
      });
    },

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      this.loadData().then(next);
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
