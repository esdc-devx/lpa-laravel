<template>
  <div class="project-list content">
    <div class="controls" v-if="hasRole('owner') || hasRole('admin')">
      <el-button @click="goToPage('project-create')">{{ trans('pages.project_list.create_project') }}</el-button>
    </div>

    <data-tables
      ref="table"
      :search-def="{show: false}"
      :custom-filters="customFilters"
      :pagination-def="paginationDef"
      :data="normalizedList"
      @filter-change="onFilterChange"
      @current-page-change="scrollToTop"
      @row-click="viewProject"
      @header-click="onHeaderClick"
      :sort-method="$helpers.localeSort">
      <el-table-column
        sortable="custom"
        prop="id"
        width="180"
        :label="trans('entities.general.lpa_num')">
        <template slot-scope="scope">
          {{ scope.row.id | LPANumFilter }}
        </template>
      </el-table-column>
      <el-table-column
        sortable="custom"
        prop="name"
        :label="trans('entities.general.name')">
      </el-table-column>
      <el-table-column
        sortable="custom"
        column-key="organizational_unit"
        :filters="orgUnitFilters"
        prop="organizational_unit"
        :label="$tc('entities.general.organizational_units')">
      </el-table-column>
      <el-table-column
        sortable="custom"
        prop="updated_at"
        :label="trans('entities.general.updated')">
      </el-table-column>
      <el-table-column
        sortable="custom"
        column-key="state"
        :filters="getColumnFilters(this.normalizedList, 'state')"
        prop="state"
        :label="trans('entities.general.status')">
      </el-table-column>
      <el-table-column
        sortable="custom"
        column-key="current_process"
        :filters="getColumnFilters(this.normalizedList, 'current_process')"
        prop="current_process"
        :label="trans('entities.process.current')">
      </el-table-column>
    </data-tables>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';

  import Page from '@components/page';

  import TableUtils from '@mixins/table/utils.js';

  let namespace = 'projects';

  export default {
    name: 'project-list',

    extends: Page,

    mixins: [ TableUtils ],

    data() {
      return {
        normalizedList: [],
        normalizedListAttrs: ['id', 'name', 'organizational_unit.name', 'updated_at', 'state.name', 'current_process']
      }
    },

    computed: {
      ...mapGetters({
        projects: `${namespace}/all`
      }),

      orgUnitFilters() {
        return this.getColumnFilters(this.normalizedList, 'organizational_unit')
                   .sort((a, b) => this.$helpers.localeSort(a, b, 'text'));
      }
    },

    methods: {
      ...mapActions({
        loadProjects: `${namespace}/loadProjects`
      }),

      viewProject(project) {
        this.scrollToTop();
        this.$router.push(`${namespace}/${project.id}`);
      },

      onHeaderClick(col, e) {
        this.headerClick(col, e);
      },

      async loadData() {
        try {
          await this.loadProjects();
          this.normalizedList = _.map(this.projects, project => {
            let normProject = _.pick(project, this.normalizedListAttrs);
            normProject.organizational_unit = normProject.organizational_unit.name;
            normProject.state = normProject.state.name;
            normProject.current_process = normProject.current_process && normProject.current_process.definition ? normProject.current_process.definition.name : this.trans('entities.general.na');
            return normProject;
          });
        } catch (e) {
          // Exception handled by interceptor
          if (!e.response) {
            throw e;
          }
        }
      }
    },

    beforeRouteEnter(to, from, next) {
      next(vm => {
        vm.loadData();
      });
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
