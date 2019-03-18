<template>
  <div>
    <el-card shadow="never">
      <span slot="header">
        <h2><i class="el-icon-lpa-edit"></i>{{ trans('pages.project_edit.title') }}</h2>
      </span>

      <el-form label-position="top" :disabled="isFormDisabled">
        <el-form-item-wrap
          :label="trans('entities.general.name')"
          prop="name"
          required
        >
          <input-wrap
            name="name"
            :data-vv-as="trans('entities.general.name')"
            v-model="form.name"
            maxlength="175"
            v-validate="'required'"
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
          <el-button :disabled="!isFormDirty || isFormDisabled" :loading="isSubmitting" type="primary" @click="onSubmit()">{{ trans('base.actions.save') }}</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
  import { mapState, mapActions } from 'vuex';

  import Page from '@components/page';
  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import InputWrap from '@components/forms/input-wrap';
  import FormError from '@components/forms/error.vue';

  import FormUtils from '@mixins/form/utils.js';

  import Project from '@/store/models/Project';

  const loadData = async function ({ to } = {}) {
    const { entityId } = to ? to.params : this;
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch('entities/projects/loadEditInfo', entityId)
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

    props: {
      entityId: {
        type: String,
        required: true
      }
    },

    computed: {
      ...mapState('entities/projects', [
        'organizationalUnits'
      ]),

      viewing() {
        return Project.find(this.entityId);
      }
    },

    data() {
      return {
        form: {
          name: '',
          organizational_unit_id: ''
        }
      };
    },

    methods: {
      ...mapActions('entities/projects', [
        '$$update'
      ]),

      loadData,

      onSubmit() {
        this.submit(this.handleUpdate);
      },

      async handleUpdate() {
        // @note: no try-catch required here
        // since we already do it in the form utils
        await this.$$update(this.form);
        this.isSubmitting = false;
        this.notifySuccess({
          message: this.trans('components.notice.message.project_updated')
        });
        this.goToParentPage();
      }
    },

    async beforeRouteEnter(to, from, next) {
      await store.dispatch('authorizations/projects/loadCanEdit', to.params.entityId);
      if (Project.find(to.params.entityId).permissions.canEdit) {
        await loadData({ to });
        next();
      } else {
        router.replace({ name: 'forbidden', params: { '0': to.path } });
      }
    },

    // called when url params change, e.g: language
    async beforeRouteUpdate(to, from, next) {
      await this.$store.dispatch('authorizations/projects/loadCanEdit', to.params.entityId);
      if (Project.find(to.params.entityId).permissions.canEdit) {
        next();
      } else {
        this.$router.replace({ name: 'forbidden', params: { '0': to.path } });
      }
    },

    created() {
      this.form = _.cloneDeep(this.viewing);
    }
  };
</script>

<style lang="scss">

</style>
