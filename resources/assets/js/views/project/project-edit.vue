<template>
  <div class="project-edit content">
    <el-card shadow="never">
      <span slot="header">
        <h2><i class="el-icon-lpa-edit"></i>{{ trans('pages.project_edit.title') }}</h2>
      </span>

      <el-form label-position="top" :disabled="isFormDisabled">
        <el-form-item-wrap
          :label="trans('entities.general.name')"
          prop="name"
          required>
          <el-input-wrap
            name="name"
            :data-vv-as="trans('entities.general.name')"
            v-model="form.project.name"
            maxlength="175"
            v-validate="'required'">
          </el-input-wrap>
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$tc('entities.general.organizational_units')"
          prop="organizational_unit"
          required>
          <el-select-wrap
            v-model="form.project.organizational_unit"
            :isLoading="isProjectInfoLoading"
            name="organizational_unit"
            :data-vv-as="$tc('entities.general.organizational_units')"
            v-validate="'required'"
            :options="organizationalUnits"
          />
        </el-form-item-wrap>

        <el-form-item>
          <el-button :disabled="isFormDisabled" @click="goToParentPage()">{{ trans('base.actions.cancel') }}</el-button>
          <el-button :disabled="!isFormDirty || isFormDisabled" :loading="isSubmitting" type="primary" @click="onSubmit()">{{ trans('base.actions.save') }}</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '@/event-bus.js';

  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import ElInputWrap from '@components/forms/el-input-wrap';
  import FormError from '@components/forms/error.vue';

  import FormUtils from '@mixins/form/utils.js';
  import PageUtils from '@mixins/page/utils.js';

  let namespace = 'projects';

  export default {
    name: 'project-edit',

    inject: ['$validator'],

    mixins: [ FormUtils, PageUtils ],

    components: { ElFormItemWrap, ElSelectWrap, ElInputWrap, FormError },

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
        await this.updateProject({
          id: this.form.project.id,
          name: this.form.project.name,
          organizational_unit: this.form.project.organizational_unit
        });
        this.isSubmitting = false;
        this.notifySuccess({
          message: this.trans('components.notice.message.project_updated')
        });
        this.goToParentPage();
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
  @import '~@sass/abstracts/vars';

  .project-edit {
    margin: 0 auto;
    h2 {
      position: relative;
      margin: 0;
      padding-left: 34px;
      display: inline-block;
      i {
        width: 24px;
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
      }
    }

    .el-form {
      .el-form-item:last-of-type {
        float: right;
      }
      label {
        padding: 0;
        font-weight: bold;
        color: $--color-primary;
      }
      .el-select {
        width: 100%;
      }
    }
  }
</style>
