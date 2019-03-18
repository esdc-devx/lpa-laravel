<template>
  <div>
    <el-card shadow="never">
      <span slot="header">
        <h2><i class="el-icon-lpa-pr"></i>{{ trans('pages.project_create.title') }}</h2>
      </span>

      <el-form ref="form" :model="form" label-position="top" @submit.native.prevent :disabled="isFormDisabled">
        <el-form-item-wrap
          :label="trans('entities.general.name')"
          prop="name"
          required
        >
          <input-wrap
            id="name"
            name="name"
            :data-vv-as="trans('entities.general.name')"
            v-model="form.name"
            maxlength="175"
            v-validate="'required'"
            auto-complete="off"
          />
        </el-form-item-wrap>
        <el-form-item-wrap
          :label="$tc('entities.general.organizational_units')"
          prop="organizational_unit_id"
          required
        >
          <el-select-wrap
            v-model="form.organizational_unit_id"
            name="organizational_unit_id"
            :data-vv-as="$tc('entities.general.organizational_units')"
            v-validate="'required'"
            :options="organizationalUnits"
          />
        </el-form-item-wrap>
        <el-form-item-wrap
          prop="outline"
          contextPath="entities.project.outline"
          required
        >
          <input-wrap
            v-model="form.outline"
            v-validate="'required'"
            :data-vv-as="trans('entities.project.outline.label')"
            name="outline"
            maxlength="1250"
            type="textarea"
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
  import { mapState, mapActions } from 'vuex';

  import Page from '@components/page';
  import FormError from '@components/forms/error.vue';
  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import InputWrap from '@components/forms/input-wrap';

  import FormUtils from '@mixins/form/utils.js';

  const loadData = async function () {
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch('entities/projects/loadCreateInfo')
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
    name: 'project-create',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElFormItemWrap, ElSelectWrap, InputWrap, FormError },

    computed: {
      ...mapState('entities/projects', [
        'organizationalUnits'
      ])
    },

    data() {
      return {
        form: {
          name: '',
          organizational_unit_id: null
        }
      }
    },

    methods: {
      ...mapActions('entities/projects', [
        '$$create'
      ]),

      loadData,

      // Form handlers
      async onSubmit() {
        this.submit(this.handleCreate);
      },

      async handleCreate() {
        // @note: no try-catch required here
        // since we already do it in the form utils
        let project = await this.$$create(this.form);
        this.isSubmitting = false;
        this.notifySuccess({
          message: this.trans('components.notice.message.project_created')
        });
        this.$router.push(`/${this.language}/projects/${project.id}`);
      }
    },

    async beforeRouteEnter(to, from, next) {
      await store.dispatch('authorizations/projects/loadCanCreate');
      if (store.state.authorizations.projects.permissions.canCreate) {
        await loadData();
        next();
      } else {
        router.replace({ name: 'forbidden', params: { '0': to.path } });
      }
    },

    // called when url params change, e.g: language
    async beforeRouteUpdate(to, from, next) {
      await this.$store.dispatch('authorizations/projects/loadCanCreate');
      if (this.$store.state.authorizations.projects.permissions.canCreate) {
        next();
      } else {
        this.$router.replace({ name: 'forbidden', params: { '0': to.path } });
      }
    },

    mounted() {
      this.autofocus('name');
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .project-create {
    h2 i {
      @include svg(projects, $--color-primary);
    }
  }
</style>
