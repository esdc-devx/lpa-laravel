<template>
  <div class="project-create content">
    <el-card shadow="never">
      <span slot="header">
        <h2><i class="el-icon-lpa-projects"></i>{{ trans('pages.project_create.title') }}</h2>
      </span>

      <el-form ref="form" :model="form" label-position="top" @submit.native.prevent :disabled="isFormDisabled">
        <el-form-item-wrap
          :label="trans('entities.general.name')"
          prop="name"
          required>
          <input-wrap
            id="name"
            name="name"
            :data-vv-as="trans('entities.general.name')"
            v-model="form.name"
            maxlength="175"
            v-validate="'required'"
            auto-complete="off">
          </input-wrap>
        </el-form-item-wrap>
        <el-form-item-wrap
          :label="$tc('entities.general.organizational_units')"
          prop="organizational_unit"
          required>
          <el-select-wrap
            v-model="form.organizational_unit"
            name="organizational_unit"
            :data-vv-as="$tc('entities.general.organizational_units')"
            v-validate="'required'"
            :options="organizationalUnits"
          />
        </el-form-item-wrap>

        <el-form-item class="form-footer">
          <el-button :disabled="isFormDisabled" @click="goToParentPage()">{{ trans('base.actions.cancel') }}</el-button>
          <el-button :disabled="isFormPristine || isFormDisabled" :loading="isSubmitting" type="primary" @click="onSubmit()">{{ trans('base.actions.create') }}</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';

  import EventBus from '@/event-bus.js';
  import FormError from '@components/forms/error.vue';
  import FormUtils from '@mixins/form/utils.js';
  import PageUtils from '@mixins/page/utils.js';

  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import InputWrap from '@components/forms/input-wrap';

  let namespace = 'projects';

  export default {
    name: 'project-create',

    inject: ['$validator'],

    mixins: [ FormUtils, PageUtils ],

    components: { ElFormItemWrap, ElSelectWrap, InputWrap, FormError },

    computed: {
      ...mapGetters({
        language: 'language',
        viewingProject: `${namespace}/viewing`,
        organizationalUnits: `${namespace}/organizationalUnits`
      })
    },

    data() {
      return {
        form: {
          name: '',
          organizational_unit: null
        }
      }
    },

    methods: {
      ...mapActions({
        createProject: `${namespace}/create`,
        loadProjectCreateInfo: `${namespace}/loadProjectCreateInfo`
      }),

      // Form handlers
      async onSubmit() {
        this.submit(this.create);
      },

      async create() {
        // @note: no try-catch required here
        // since we already do it in the form utils
        await this.createProject(this.form);
        this.isSubmitting = false;
        this.notifySuccess({
          message: this.trans('components.notice.message.project_created')
        });
        this.$router.push(`/${this.language}/projects/${this.viewingProject.id}`);
      },

      async fetch() {
        await this.loadProjectCreateInfo();
      },

      async onLanguageUpdate() {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.resetErrors();

        await this.fetch();
      }
    },

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      this.onLanguageUpdate();
      next();
    },

    async mounted() {
      EventBus.$emit('App:ready');
      await this.fetch();
      this.autofocus('name');
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .project-create {
    margin: 0 auto;
    h2 {
      position: relative;
      margin: 0;
      padding-left: 34px;
      display: inline-block;
      i {
        @include svg(projects, $--color-primary);
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