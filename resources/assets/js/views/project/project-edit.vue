<template>
  <div class="project-edit content">
    <h2>{{ trans('pages.project_edit.title') }}</h2>

    <el-form label-width="30%" :disabled="isFormDisabled">
      <el-form-item :label="trans('entities.general.name')" for="name" :class="['is-required', {'is-error': verrors.collect('name').length }]" prop="name">
        <el-input
          id="name"
          name="name"
          :data-vv-as="trans('entities.general.name')"
          v-model="form.project.name"
          v-validate="'required'">
        </el-input>
        <form-error v-for="error in verrors.collect('name')" :key="error.id">{{ error }}</form-error>
      </el-form-item>

      <el-form-item :label="$tc('entities.general.organizational_units')" for="organizationalUnit" :class="['is-required', {'is-error': verrors.collect('organizationalUnit').length }]" prop="organizationalUnit">
        <el-select
          id="organizationalUnit"
          name="organizationalUnit"
          :data-vv-as="$tc('entities.general.organizational_units')"
          v-loading="isProjectInfoLoading"
          element-loading-spinner="el-icon-loading"
          v-validate="'required'"
          v-model="form.project.organizational_unit"
          valueKey="name">
          <el-option
            v-for="item in organizationalUnits"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
        <form-error v-for="error in verrors.collect('organizationalUnit')" :key="error.id">{{ error }}</form-error>
      </el-form-item>

      <el-form-item>
        <el-button :disabled="!isFormDirty || isFormDisabled" :loading="isSaving" type="primary" @click="onSubmit()">{{ trans('base.actions.save') }}</el-button>
        <el-button :disabled="isFormDisabled" @click="go(`/${language}/projects/${form.project.id}`)">{{ trans('base.actions.cancel') }}</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../../event-bus.js';
  import FormError from '../../components/forms/error.vue';
  import FormUtils from '../../mixins/form/utils.js';
  import PageUtils from '../../mixins/page/utils.js';

  let namespace = 'projects';

  export default {
    name: 'project-edit',

    mixins: [ FormUtils, PageUtils ],

    components: { FormError },

    computed: {
      ...mapGetters({
        language: 'language',
        viewingProject: `${namespace}/viewing`,
        organizationalUnits: `${namespace}/organizationalUnits`
      })
    },

    data() {
      return {
        isProjectInfoLoading: false,
        form: {
          project: {}
        }
      };
    },

    methods: {
      ...mapActions({
        updateProject: `${namespace}/update`,
        loadProjectEditInfo: `${namespace}/loadProjectEditInfo`
      }),

      onSubmit() {
        this.submit(this.update);
      },

      async update() {
        try {
          await this.updateProject({
            id: this.form.project.id,
            name: this.form.project.name,
            organizational_unit: this.form.project.organizational_unit
          });
          this.isSaving = false;
          this.notifySuccess(this.trans('components.notice.updated', { name: this.form.project.name }));
          this.go(`/${this.language}/projects/${this.form.project.id}`);
        } catch({ response }) {
          this.isSaving = false;
          this.manageBackendErrors(response.data.errors);
        }
      },

      async triggerLoadProjectEditInfo() {
        this.isProjectInfoLoading = true;
        let projectId = this.$route.params.projectId;
        try {
          await this.loadProjectEditInfo(projectId);
          EventBus.$emit('App:ready');
          this.form.project = Object.assign({}, this.viewingProject);
          // replace our internal organizational_units with only the ids
          // since ElementUI only need ids to populate the selected options
          this.form.project.organizational_unit = this.viewingProject.organizational_unit.id;
          this.isProjectInfoLoading = false;
        } catch(e) {
          this.$router.replace(`/${this.language}/${HttpStatusCodes.NOT_FOUND}`);
        }
      },

      async onLanguageUpdate() {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.resetErrors();
        // only reload the dropdowns, not the project
        this.isProjectInfoLoading = true;
        let projectId = this.$route.params.projectId;
        await this.loadProjectEditInfo(projectId);
        this.isProjectInfoLoading = false;
      }
    },

    beforeRouteLeave(to, from, next) {
      // Destroy any events we might be listening
      // so that they do not get called while being on another page
      EventBus.$off('Store:languageUpdate', this.onLanguageUpdate);
      next();
    },

    beforeRouteEnter(to, from, next) {
      next(vm => {
        vm.triggerLoadProjectEditInfo();
      });
    },

    mounted() {
      EventBus.$on('Store:languageUpdate', this.onLanguageUpdate);
    }
  };
</script>

<style lang="scss">
  @import '../../../sass/abstracts/vars';
  .project-edit {
    margin: 0 auto;
    h2 {
      text-align: center;
    }

    .el-form {
      width: 800px;
      margin: 0 auto;
      .el-select {
        width: 75%;
      }
    }
  }
</style>
