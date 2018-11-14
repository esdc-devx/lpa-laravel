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
          <input-wrap
            name="name"
            :data-vv-as="trans('entities.general.name')"
            v-model="form.project.name"
            maxlength="175"
            v-validate="'required'">
          </input-wrap>
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$tc('entities.general.organizational_units')"
          prop="organizational_unit"
          required>
          <el-select-wrap
            v-model="form.project.organizational_unit"
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

  import Page from '@components/page';
  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import InputWrap from '@components/forms/input-wrap';
  import FormError from '@components/forms/error.vue';

  import FormUtils from '@mixins/form/utils.js';

  const namespace = 'projects';

  const loadData = async function ({ to } = {}) {
    const { projectId } = to ? to.params : this;
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch(`${namespace}/loadProjectEditInfo`, projectId)
    );

    // Exception handled by interceptor
    try {
      await axios.all(requests);
    } catch (e) {
      if (!e.response) {
        throw e;
      }
    }
  };

  export default {
    name: 'project-edit',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElFormItemWrap, ElSelectWrap, InputWrap, FormError },

    computed: {
      ...mapGetters({
        viewingProject: `${namespace}/viewing`,
        organizationalUnits: `${namespace}/organizationalUnits`
      })
    },

    data() {
      return {
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
        // @note: no try-catch required here
        // since we already do it in the form utils
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

      async onLanguageUpdate() {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.resetErrors();
        // only reload the dropdowns, not the project
        await this.loadProjectEditInfo(this.projectId);
      }
    },

    async beforeRouteEnter(to, from, next) {
      await store.dispatch('projects/loadCanEdit', to.params.projectId);
      if (store.state.projects.permissions.canEdit) {
        await loadData({to});
        next(vm => {
          vm.form.project = _.cloneDeep(vm.viewingProject);
          // replace our internal organizational_units with only the ids
          // since ElementUI only need ids to populate the selected options
          vm.form.project.organizational_unit = vm.viewingProject.organizational_unit.id;
        });
      } else {
        router.replace({ name: 'forbidden', params: { '0': to.path } });
      }
    },

    // called when url params change, e.g: language
    async beforeRouteUpdate(to, from, next) {
      await this.$store.dispatch('projects/loadCanEdit', to.params.projectId);
      if (this.$store.state.projects.permissions.canEdit) {
        await this.onLanguageUpdate();
        this.form.project = _.cloneDeep(this.viewingProject);
        // replace our internal organizational_units with only the ids
        // since ElementUI only need ids to populate the selected options
        this.form.project.organizational_unit = this.viewingProject.organizational_unit.id;
        next();
      } else {
        this.$router.replace({ name: 'forbidden', params: { '0': to.path } });
      }
    },

     created() {
      // store the reference to the current form id
      this.projectId = this.$route.params.projectId;
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
