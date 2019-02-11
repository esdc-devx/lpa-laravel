<template>
  <div>
    <el-row>
      <el-col>
        <el-card-wrap
          icon="el-icon-lpa-projects"
          :headerTitle="trans('base.navigation.projects')"
        >
          <template slot="controls" v-if="hasRole('owner') || hasRole('admin')">
            <el-control-wrap
              componentName="el-button"
              :tooltip="trans('pages.project_list.create_project')"
              size="mini"
              icon="el-icon-lpa-add"
              @click.native="goToPage('project-create')"
            />
          </template>
        </el-card-wrap>
      </el-col>
    </el-row>
    <el-row>
      <el-col>
        <entity-data-table
          :data="all"
          :attributes="dataTableAttributes.projects"
          @rowClick="onProjectRowClick"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapState, mapActions } from 'vuex';

  import Page from '@components/page';
  import EntityDataTable from '@components/entity-data-table.vue';
  import ElCardWrap from '@components/el-card-wrap';
  import ElControlWrap from '@components/el-control-wrap';

  import Project from '@/store/models/Project';

  const loadData = async () => {
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch('entities/projects/loadAll')
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

    components: { EntityDataTable, ElCardWrap, ElControlWrap },

    computed: {
      all() {
        return Project.all();
      },

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
              updated_by: {
                isColumn: false
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
  @import '~@sass/abstracts/mixins/helpers';

  .project-list {
    i.el-icon-lpa-projects {
      @include svg(projects, $--color-primary);
    }
  }
</style>
