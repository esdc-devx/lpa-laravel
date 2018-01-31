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
        :filters="groups"
        :filter-method="filterGroups"
        filter-placement="bottom-start"
        class-name="groupFilter"
        prop="organization_unit.name"
        label="Organizational group">
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

    <project-create :show="showCreateModal" @close="showCreateModal = false"></project-create>
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
      groups() {
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

      deleteProjectModal(id) {
        this.project = this.findProject(id);
        this.showDeleteModal = true;
      },

      deleteProject() {
        let id = this.project.id;
        let index = this.findProjectIndex(id);
        this.projects.splice(index, 1);
        this.showDeleteModal = false;
        this.$notify({
          title: 'Success',
          dangerouslyUseHTMLString: true,
          message: `<b>${this.project.name}</b> has been deleted.`,
          type: 'success',
        });
      },

      // This method is used by the edit and create
      saveProject(project) {
        let i = this.findProjectIndex(project.id);
        if (!i) {
          i = this.projects.length + 1;
          // if no key was found, this is a new project
          project.id = i;
          this.projects.push(project);
        } else {
          this.projects[i] = project;
        }
        this.$notify({
          title: 'Success',
          dangerouslyUseHTMLString: true,
          message: `<b>${project.name}</b> has been created.`,
          type: 'success'
        });
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
      filterGroups(value, row) {
        return row.organization_unit.name === value;
      }
    },

    created() {
      EventBus.$on('ProjectList:create', project => {
        this.saveProject(project);
      })
      .$on('ProjectList:save', project => {
        this.saveProject(project);
      });

      this.$store.dispatch('loadProjects').then(() => {
        this.isLoading = false;
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

  .groupFilter:hover {
    cursor: pointer;
  }
</style>
