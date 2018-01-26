<template>
  <el-dialog
    center
    :visible.sync="show"
    @close="close">
    <span slot="title" class="el-dialog__title">Create a New Project</span>
    <hr>
    <el-form :model="modal.form" :rules="modal.rules" ref="modal.form" label-width="20%">
      <el-form-item label="Project name" for="createProjectName" prop="name">
        <el-input v-model="modal.form.name" id="createProjectName" auto-complete="off"></el-input>
      </el-form-item>
      <el-form-item label="Organizational Group" for="createProjectGroup" prop="group">
        <el-select v-model="modal.form.group" id="createProjectGroup" placeholder="Please select a group">
          <el-option
            v-for="item in modal.form.groupOptions"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button type="primary" @click="submit('modal.form')">Confirm</el-button>
      <el-button @click="closeCreateProjectModal()">Cancel</el-button>
    </span>
  </el-dialog>
</template>

<script>
  import EventBus from '../components/event-bus.js';

  export default {
    name: 'ProjectCreate',
    props: {
      show: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        isLoading: false,
        name: null,
        description: null,
        modal: {
          form: {
            name: '',
            group: '',
            groupOptions: [{
              value: 'CS',
              label: 'Computer Systems (CS)'
            }, {
              value: 'GT',
              label: 'General Technical (GT)'
            }]
          },
          rules: {
            name: [
              { required: true, message: 'Please input Project name', trigger: 'change' },
            ],
            group: [
              { required: true, message: 'Please select an Organizational Group', trigger: 'change' }
            ]
          }
        }
      }
    },

    methods: {
      createProject() {
        let project = {
          name: this.name,
          description: this.description
        }
        EventBus.$emit('ProjectList:create', project);
      },

      createProject() {
        let project = {
          name: this.modal.form.name,
          group: this.modal.form.group
        }
        EventBus.$emit('ProjectList:create', project);
        this.closeCreateProjectModal();
      },

      closeCreateProjectModal() {
        this.$emit('close');
        this.resetForm('modal.form');
      },

      submit(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            this.createProject();
            this.closeCreateProjectModal();
          } else {
            console.log('error submit!!');
            return false;
          }
        });
      },

      resetForm(formName) {
        this.$refs[formName].resetFields();
      },

      close: function () {
        this.$emit('close');
      },

      goBack() {
        this.$router.go(-1);
      }
    }
  };
</script>

<style scoped lang="scss">
  .el-dialog__title {
    text-transform: uppercase;
    font-weight: bold;
  }

  .el-select {
    // fixes issue in IE11
    float: left;
  }
</style>
