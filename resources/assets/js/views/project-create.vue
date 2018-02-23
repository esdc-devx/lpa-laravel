<template>
  <el-dialog
    center
    :visible.sync="isVisible"
    @close="close">
    <span slot="title" class="el-dialog__title">Create a New Project</span>
    <hr>
    <el-form :model="modal.form" ref="form" label-width="20%" @submit.native.prevent>
      <el-form-item label="Project name" for="name" :class="['is-required', {'is-error': verrors.has('name') }]">
        <el-input
          v-model="modal.form.name"
          v-validate="'required'"
          id="name"
          name="name"
          auto-complete="off"
          autofocus>
        </el-input>
        <span v-show="verrors.has('name')" class="el-form-item__error">{{ verrors.first('name') }}</span>
      </el-form-item>
      <el-form-item label="Organizational Unit" for="organizationUnits" :class="['is-required', {'is-error': verrors.has('organizationUnits') }]">
        <el-select
          :disabled="modal.form.orgUnitOptions.length <= 1"
          v-validate="'required'"
          v-model="modal.form.organization_unit"
          id="organizationUnits"
          name="organizationUnits">
          <el-option
            v-for="item in modal.form.orgUnitOptions"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
        <span v-show="verrors.has('organizationUnits')" class="el-form-item__error">{{ verrors.first('organizationUnits') }}</span>
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button type="primary" @click="submit()">Confirm</el-button>
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
            }]
          }
        }
      }
    },

    methods: {
      create() {
        let project = {
          name: this.modal.form.name,
          organization_unit: this.modal.form.organization_unit
        };
        // @todo: might want to show a loading spinner on modal after hitting create
        // this.$store.dispatch('create', project)
        //   .then(response => {
        //     // @todo: grab the newly created project
        //     // and pluck its name for the notification
        //     this.close();

        //     this.notifySuccess(`{something} has been created.`);
        //   })
        //   .catch(e => {
        //     this.notifyError('[project-create][create]: ' + e);
        //     console.error('[project-create][create]: ' + e);
        //   });
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

      // Form handlers
      submit() {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.createProject();
            this.notifySuccess(`${this.form.name} has been created.`);
            this.resetForm();
            return;
          }
          document.querySelectorAll('.is-error input')[0].focus();
        });
      },

      resetForm() {
        this.$refs.form.resetFields();
        this.$nextTick(() => {
          this.$validator.reset();
        });
      },
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
