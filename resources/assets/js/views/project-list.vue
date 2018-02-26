<template>
  <div class="project-list content" v-loading="isLoading">
    <el-button @click="showCreateModal = true">Create a project</el-button>
    <el-table
      empty-text="Nothing to show here mate"
      :default-sort="{prop: 'id', order: 'ascending'}"
      :data="projects"
      @row-click="viewProject">
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
        class-name="orgunit-filter"
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
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../components/event-bus.js';

  import ProjectCreate from '../views/project-create';
  import ProjectsAPI from '../api/projects';

  let namespace = 'projects';

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
      ...mapGetters({
        'projects': `${namespace}/all`,
        'pagination': `${namespace}/pagination`
      }),
      orgUnit() {
        return _.chain(this.projects)
                .mapValues('organization_unit.name')
                .toArray().uniq()
                .map((val, key) => { return { text: val, value: val } })
                .value();
      }
    },

    methods: {
      ...mapActions({
        loadProjects: `${namespace}/loadProjects`
      }),

      viewProject(project) {
        this.$store.commit(`${namespace}/setProject`, project);
        this.$router.push(`${namespace}/${project.id}`);
      },

      // Pagination
      handleCurrentChange(newCurrentPage) {
        this.isLoading = true;
        this.$parent.$el.scrollTop = 0;
        this.loadProjects(newCurrentPage).then(() => {
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
      EventBus.$emit('App:ready');
      this.$store.dispatch(`${namespace}/loadProjects`)
        .then(() => {
          this.isLoading = false;
        });
    }
  };
</script>

<style lang="scss">
  .project-list {
    margin-bottom: 40px;
    .el-table__row {
      cursor: pointer;
    }
  }

  .orgunit-filter:hover {
    cursor: pointer;
  }
</style>
