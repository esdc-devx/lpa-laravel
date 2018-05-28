<template>
  <div class="project-list content">
    <div class="controls" v-if="hasRole('owner') || hasRole('admin')">
      <el-button @click="$router.push('projects/create')">{{ trans('pages.project_list.create_project') }}</el-button>
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
      :sort-method="onSort">
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
        column-key="orgUnit"
        :filters="getColumnFilters(this.projects, 'organizational_unit.name')"
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
        :filters="getColumnFilters(this.projects, 'state.name')"
        prop="state"
        :label="trans('entities.general.status')">
      </el-table-column>
      <el-table-column
        sortable="custom"
        :filters="getColumnFilters(this.projects, 'process')"
        prop="process"
        :label="trans('entities.general.current_process')">
      </el-table-column>
    </data-tables>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../../event-bus.js';
  import Constants from '../../constants.js';

  import TableUtils from '../../mixins/table/utils.js';

  import ProjectsAPI from '../../api/projects';

  let namespace = 'projects';

  export default {
    name: 'project-list',

    mixins: [ TableUtils ],

    data() {
      return {
        paginationDef: {
          layout: 'total, prev, pager, next, sizes',
          // @todo: ideally get values from localstorage
          pageSize: Constants.PAGE_SIZE_DEFAULT,
          pageSizes: Constants.PAGE_SIZES,
          currentPage: 1
        },
        // used in order to sync the pagination with the filters
        customFilters: [{
          vals: []
        }],
        normalizedList: [],
        normalizedListAttrs: ['id', 'name', 'organizational_unit.name', 'updated_at', 'state.name']
      }
    },

    computed: {
      ...mapGetters({
        language: 'language',
        hasRole: 'users/hasRole',
        projects: `${namespace}/all`,
        pagination: `${namespace}/pagination`
      })
    },

    methods: {
      ...mapActions({
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        loadProjects: `${namespace}/loadProjects`
      }),

      viewProject(project) {
        this.scrollToTop();
        this.$store.commit(`${namespace}/setViewingProject`, project);
        this.$router.push(`${namespace}/${project.id}`);
      },

      onFilterChange(filters) {
        this.customFilters[0].vals = filters.orgUnit;
      },

      onHeaderClick(col, e) {
        this.headerClick(col, e);
      },

      scrollToTop() {
        document.querySelectorAll('.el-main')[0].scrollTop = 0;
        // IE11 scroll to top
        document.querySelectorAll('.content-wrap')[0].scrollTop = 0
      },

      async triggerLoadProjects() {
        this.showMainLoading();
        await this.$store.dispatch(`${namespace}/loadProjects`);
        this.normalizedList = _.map(this.projects, project => {
          let normProject = _.pick(project, this.normalizedListAttrs);
          normProject.organizational_unit = normProject.organizational_unit.name;
          normProject.state = normProject.state.name;
          // @todo: change to real property instead
          normProject.process = '';
          return normProject;
        });

        this.$nextTick(() => {
          this.hideMainLoading();
        });
      },

      async onLanguageUpdate() {
        await this.triggerLoadProjects();
      }
    },

    beforeRouteLeave(to, from, next) {
      // Destroy any events we might be listening
      // so that they do not get called while being on another page
      EventBus.$off('Store:languageUpdate', this.onLanguageUpdate);
      next();
    },

    mounted() {
      EventBus.$emit('App:ready');
      EventBus.$on('Store:languageUpdate', this.onLanguageUpdate);
      this.triggerLoadProjects();
    }
  };
</script>

<style lang="scss">
  @import '../../../sass/abstracts/vars';
  .project-list {
    .el-table__row {
      cursor: pointer;
    }

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
