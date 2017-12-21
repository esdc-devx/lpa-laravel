<template>
  <div class="content">
    <h2>Projects</h2>
    <el-button type="primary" @click="showCreateModal = true"><b>+</b></el-button>
    <el-table
      empty-text="Nothing to show here mate"
      :default-sort="{prop: 'id', order: 'ascending'}"
      :data="learningProjects">
      <el-table-column
        sortable
        prop="id"
        label="Index"
        width="180">
      </el-table-column>
      <el-table-column
        sortable
        prop="name"
        label="Name">
      </el-table-column>
      <el-table-column
        :filters="[{ text: 'Home', value: 'Home' }, { text: 'Office', value: 'Office' }]"
        :filter-method="filterGroup"
        filter-placement="bottom-start"
        prop="group"
        label="Organizational group">
      </el-table-column>
      <el-table-column
        label="Operations">
        <template slot-scope="scope">
          <el-button
            size="mini"
            @click="editProject(scope.row.id)">Edit</el-button>
          <el-button
            size="mini"
            type="danger"
            @click="deleteProjectModal(scope.row.id)">Delete</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog
      :visible.sync="showDeleteModal"
      width="30%">
      <span slot="title" class="el-dialog__title">Delete project {{project.name}} ?</span>
      <span>This action cannot be undone.</span>
      <span slot="footer" class="dialog-footer">
        <el-button type="danger" @click="deleteProject()">Delete</el-button>
        <el-button @click="showDeleteModal = false">Cancel</el-button>
      </span>
    </el-dialog>

    <learning-project-create :show="showCreateModal" @close="showCreateModal = false"></learning-project-create>
  </div>
</template>

<script>
  import axios from 'axios';
  import { EventBus } from '../components/event-bus.js';
  import LearningProjectCreate from '../views/learning-project-create';

  export default {
    name: 'ProjectList',

    data() {
      return {
        project: {
          name: null
        },
        projects: [],
        isLoading: false,
        showCreateModal: false,
        showDeleteModal: false
      }
    },

    components: { LearningProjectCreate },

    computed: {
      learningProjectsLoadStatus(){
        return this.$store.getters.getlearningProjectsLoadStatus;
      },
      learningProjects() {
        return this.$store.getters.getLearningProjects;
      },
    },

    methods: {
      editProject(id) {
        alert('Edit project popup?')
        //this.$router.push(`/projects/edit/${id}`);
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
      filterGroup(value, row) {
        return row.group === value;
      }
    },

    created() {
      EventBus.$on('ProjectList:create', (project) => {
        this.saveProject(project);
      })
      .$on('ProjectList:save', project => {
        this.saveProject(project);
      });
      this.$store.dispatch('loadLearningProjects');
    }
  };
</script>

<style scoped lang="scss">
  table {
    width: 100%;
    tbody {
      tr {
        background-color: #fafafa;
        .delete-project {
          position: relative;
          &:hover {
            background-color: #d1143a;
          }
        }
      }
    }
  }
</style>
