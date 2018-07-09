<template>
  <div class="project-create content">
    <h2>{{ trans('pages.project_create.title') }}</h2>
    <el-form :model="form" ref="form" label-width="30%" @submit.native.prevent :disabled="isFormDisabled">
      <el-form-item :label="trans('entities.general.name')" for="name" :class="['is-required', {'is-error': verrors.collect('name').length }]" prop="name">
        <el-input
          id="name"
          name="name"
          :data-vv-as="trans('entities.general.name')"
          v-model="form.name"
          v-validate="'required'"
          auto-complete="off">
        </el-input>
        <form-error name="name"></form-error>
      </el-form-item>
      <el-form-item :label="$tc('entities.general.organizational_units')" for="organizationalUnit" :class="['is-required', {'is-error': verrors.collect('organizationalUnit').length }]" prop="organizational_units">
        <el-select
          id="organizationalUnit"
          name="organizationalUnit"
          :data-vv-as="$tc('entities.general.organizational_units')"
          v-loading="isProjectInfoLoading"
          element-loading-spinner="el-icon-loading"
          v-validate="'required'"
          v-model="form.organizational_unit"
          valueKey="name">
          <el-option
            v-for="item in organizationalUnits"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
        <form-error name="organizationalUnit"></form-error>
      </el-form-item>

      <el-form-item class="form-footer">
        <el-button :disabled="isFormPristine || isFormDisabled" :loading="isSubmitting" type="primary" @click="onSubmit()">{{ trans('base.actions.create') }}</el-button>
        <el-button :disabled="isFormDisabled" @click="go(`/${language}/projects`)">{{ trans('base.actions.cancel') }}</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';

  import EventBus from '@/event-bus.js';
  import FormError from '@components/forms/error.vue';
  import FormUtils from '@mixins/form/utils.js';
  import PageUtils from '@mixins/page/utils.js';

  let namespace = 'projects';

  export default {
    name: 'project-create',

    inject: ['$validator'],

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
          name: '',
          organizational_unit: []
        }
      }
    },

    methods: {
      ...mapActions({
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        createProject: `${namespace}/create`,
        loadProjectCreateInfo: `${namespace}/loadProjectCreateInfo`
      }),

      // Form handlers
      async onSubmit() {
        this.submit(this.create);
      },

      async create() {
        try {
          let project = await this.createProject(this.form);
          this.$store.commit(`${namespace}/setViewing`, project);
          this.isSubmitting = false;
          this.notifySuccess({
            message: this.trans('components.notice.message.created', { name: this.form.name })
          });
          this.jumpToCreatedProject();
        } catch({ response }) {
          this.isSubmitting = false;
          this.manageBackendErrors(response.data.errors);
        }
      },

      // Navigation
      jumpToCreatedProject() {
        this.$helpers.throttleAction(() => {
          this.$router.push(`/${this.language}/projects/${this.viewingProject.id}`);
        });
      },

      async triggerLoadProjectCreateInfo() {
        this.isProjectInfoLoading = true;
        await this.loadProjectCreateInfo();
        this.isProjectInfoLoading = false;
      },

      async onLanguageUpdate() {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.resetErrors();

        await this.triggerLoadProjectCreateInfo();
      }
    },

    beforeRouteLeave(to, from, next) {
      // Destroy any events we might be listening
      // so that they do not get called while being on another page
      EventBus.$off('Store:languageUpdate', this.onLanguageUpdate);
      next();
    },

    async mounted() {
      EventBus.$emit('App:ready');
      EventBus.$on('Store:languageUpdate', this.onLanguageUpdate);

      await this.showMainLoading();
      this.triggerLoadProjectCreateInfo();
      await this.hideMainLoading();
      this.autofocus('name');
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  .project-create {
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
  .el-autocomplete-suggestion.name-autocomplete {
    li {
      line-height: 20px;
      padding: 7px 10px;

      .autocomplete-popper-inner-wrap {
        overflow: hidden;
        .value {
          text-overflow: ellipsis;
          overflow: hidden;
        }
        .link {
          font-size: 12px;
          color: #b4b4b4;
        }
      }
    }
  }
</style>
