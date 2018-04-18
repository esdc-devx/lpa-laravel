<template>
  <div class="project-list content">
    <div class="controls">
      <el-button @click="$router.push('projects/create')">{{ trans('pages.project_list.create_project') }}</el-button>
    </div>

    <el-table
      :default-sort="{prop: 'id', order: 'ascending'}"
      :data="projects"
      @row-click="viewProject">
      <el-table-column
        sortable
        prop="id"
        :label="trans('entities.project.lpa_num')"
        width="180">
      </el-table-column>
      <el-table-column
        sortable
        prop="name"
        :label="trans('entities.project.name')">
      </el-table-column>
      <el-table-column
        :filters="orgUnit"
        :filter-method="filterOrgUnit"
        filter-placement="bottom-start"
        prop="organizational_unit.name"
        :label="$tc('base.entities.organizational_units')">
        <template slot-scope="scope">
          <el-tag type="info" size="small" :title="scope.row.organizational_unit.name">{{scope.row.organizational_unit.name}}</el-tag>
        </template>
      </el-table-column>
    </el-table>

    <el-pagination
      background
      @current-change="handleCurrentChange"
      :current-page.sync="currentPage"
      :page-size="pagination.per_page"
      layout="total, prev, pager, next"
      :total="pagination.total">
    </el-pagination>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../event-bus.js';

  import ProjectsAPI from '../api/projects';

  let namespace = 'projects';

  export default {
    name: 'project-list',

    data() {
      return {
        showDeleteModal: false,
        currentPage: 1
      }
    },

    computed: {
      ...mapGetters({
        'projects': `${namespace}/all`,
        'pagination': `${namespace}/pagination`
      }),

      orgUnit() {
        return _.chain(this.projects)
                .mapValues('organizational_unit.name')
                .toArray().uniq()
                .map((val, key) => { return { text: val, value: val } })
                .value();
      }
    },

    methods: {
      ...mapActions({
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        loadProjects: `${namespace}/loadProjects`
      }),

      viewProject(project) {
        this.scrollToTop();
        this.$store.commit(`${namespace}/setProject`, project);
        this.$router.push(`${namespace}/${project.id}`);
      },

      // Pagination
      async handleCurrentChange(newCurrentPage) {
        this.showMainLoading();
        this.scrollToTop();
        await this.loadProjects(newCurrentPage);
        this.hideMainLoading();
      },

      scrollToTop() {
        document.querySelectorAll('.el-main')[0].scrollTop = 0;
        // IE11 scroll to top
        document.querySelectorAll('.content-wrap')[0].scrollTop = 0
      },

      // Filters
      findProject(id) {
        return this.projects[this.findProjectIndex(id)];
      },

      findProjectIndex(id) {
        for (var i = 0; i < this.projects.length; i++) {
          if (this.projects[i].id == id) {
            return i;
          }
        }
      },

      filterOrgUnit(value, row) {
        return row.organizational_unit.name === value;
      },

      async triggerLoadProjects() {
        this.showMainLoading();
        await this.$store.dispatch(`${namespace}/loadProjects`);
        this.hideMainLoading();
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
  @import '../../sass/abstracts/vars';
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
  }
</style>
