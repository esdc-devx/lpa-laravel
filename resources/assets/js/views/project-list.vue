<template>
  <div class="content">
    <el-button @click="showCreateModal = true">Create a new project</el-button>
    <el-table
      class="projectList"
      empty-text="Nothing to show here mate"
      :default-sort="{prop: 'id', order: 'ascending'}"
      :data="projects"
      @row-click="viewProject"
      v-loading="isLoading"
      >
      <el-table-column
        sortable
        prop="id"
        label="Id"
        class-name="numFilter"
        width="180">
      </el-table-column>
      <el-table-column
        sortable
        class-name="nameFilter"
        prop="name"
        label="Name">
      </el-table-column>
      <el-table-column
        :filters="orgUnit"
        :filter-method="filterOrgUnit"
        filter-placement="bottom-start"
        class-name="orgUnitFilter"
        prop="organization_unit.name"
        label="Organizational unit">
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

    <project-create
      v-if="showCreateModal"
      :show.sync="showCreateModal"
      @close="showCreateModal = false"/>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters } from 'vuex';

  import EventBus from '../components/event-bus.js';
  import ProjectCreate from '../views/project-create';
  import ProjectsAPI from '../api/projects';

  export default {
    name: 'ProjectList',

    data() {
      return {
        isLoading: true,
        showCreateModal: false,
        showDeleteModal: false,
        currentPage: 1
      }
    },

    components: { ProjectCreate },

    computed: {
      orgUnit() {
        return _.chain(this.projects)
                .mapValues('organization_unit.name')
                .toArray().uniq()
                .map((val, key) => { return { text: val, value: val } })
                .value();
      },
      ...mapGetters([
        'projects',
        'pagination'
      ])
    },

    methods: {
      viewProject(project) {
        this.$store.commit('setProject', project);
        this.$router.push(`projects/${project.id}`);
      },

      // Pagination
      handleCurrentChange(newCurrentPage) {
        this.isLoading = true;
        this.$parent.$el.scrollTop = 0;
        this.$store.dispatch('loadProjects', newCurrentPage).then(data => {
          this.isLoading = false;
        });
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
        return row.organization_unit.name === value;
      }
    },

    created() {
      this.$store.dispatch('loadProjects')
        .then(() => {
          this.isLoading = false;
        })
        .catch(e => {
          console.error('[project-list][loadProjects]: ' + e);
          alert('[project-list][loadProjects]: ' + e);
        });
    }
  };
</script>

<style lang="scss">
  .projectList {
    margin-bottom: 40px;
    .el-table__row {
      cursor: pointer;
      .name .cell {
        display: inline;
        border-bottom: 1px solid;
        margin: 0 10px;
        padding: 0;
      }
    }
  }

  .orgUnitFilter:hover {
    cursor: pointer;
  }
</style>
