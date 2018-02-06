<template>
  <el-dialog
    center
    :visible.sync="isVisible"
    @close="close">
    <span slot="title" class="el-dialog__title">Create a New Project</span>
    <hr>
    <el-form :model="modal.form" :rules="rules()" ref="form" label-width="20%" @submit.native.prevent>
      <el-form-item label="Project name" for="name" prop="name">
        <el-input v-model="modal.form.name" id="name" name="name" auto-complete="off" autofocus></el-input>
      </el-form-item>
      <el-form-item label="Organizational Unit" for="organization_unit" prop="organization_unit">
        <el-select
          v-if="modal.form.orgUnitOptions.length > 1"
          v-model="modal.form.organization_unit"
          id="organization_unit"
          name="organization_unit">
          <el-option
            v-for="item in modal.form.orgUnitOptions"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
        <el-input v-else v-model="modal.form.orgUnitOptions" id="organization_unit" name="organization_unit" disabled></el-input>
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button type="primary" @click="validateBeforeSubmit()">Confirm</el-button>
      <el-button @click="close()">Cancel</el-button>
    </span>
  </el-dialog>
</template>

<script>
  import EventBus from '../components/event-bus.js';

  export default {
    name: 'ProjectCreate',

    props: ['show'],

    watch: {
      '$route': function(to) {
        // since we have translations in messages,
        // we need to rebuild the rules when the route changes
        this.rules();
      }
    },

    data() {
      return {
        isVisible: this.show,
        isLoading: false,
        // @todo: add dynamic attribute values from backend
        nameRequired: this.trans('validation.required', { attribute: 'name' }),
        orgUnitRequired: this.trans('validation.required', { attribute: 'organization_unit' }),
        modal: {
          form: {
            name: '',
            organization_unit: '',
            // @todo: (backend data)
            orgUnitOptions: [{
              value: 'CS',
              label: 'Computer Systems (CS)'
            }, {
              value: 'GT',
              label: 'General Technical (GT)'
            }],
            // @todo: (backend data)
            orgUnitFields: [
              'CS',
              'GT'
            ]
          }
        }
      }
    },

    methods: {
      rules() {
        return {
          name: [
            { required: true, message: this.nameRequired, trigger: 'blur' },
          ],
          organization_unit: [
            { required: true, message: this.orgUnitRequired, trigger: 'blur' }
          ]
        }
      },

      createProject() {
        let project = {
          name: this.modal.form.name,
          organization_unit: this.modal.form.organization_unit
        }
        // @todo: might want to show a loading spinner on modal after hitting create
        this.$store.dispatch('createProject', project)
          .then(response => {
            // @todo: grab the newly created project
            // and pluck its name for the notification
            this.close();
            this.$notify({
              title: 'Success',
              dangerouslyUseHTMLString: true,
              message: `{something} has been created.`,
              type: 'success'
            });
          })
          .catch(e => {
            // @refactor: Make a notification component
            console.error('[project-create][createProject]: ' + e);
            alert('[project-create][createProject]: ' + e);
          });
      },

      close() {
        // tell ElementUI's dialog to close,
        // reset the form
        // and wait until the animation of ElementUI is done
        this.isVisible = false;
        setTimeout(() => {
          this.$emit('close');
        }, 500);
        this.resetForm();
      },

      validateBeforeSubmit() {
        this.$refs.form.validate(valid => {
          if (valid) {
            this.createProject();
            return true
          }
          // form has errors
          // @todo: might want to focus on the error fields
          return false;
        });
      },

      resetForm() {
        this.$refs.form.resetFields();
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
