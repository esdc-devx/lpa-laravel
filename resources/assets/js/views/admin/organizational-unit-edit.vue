<template>
  <div>
    <el-card shadow="never">
      <span slot="header">
        <h2><i class="el-icon-lpa-edit"></i>{{ trans('base.navigation.admin_organizational_unit_edit') }}</h2>
      </span>

      <el-form label-position="top" :disabled="isFormDisabled">
        <el-form-item-wrap
          :label="trans('entities.general.name_en')"
          prop="name_en"
          required
        >
          <input-wrap
            name="name_en"
            :data-vv-as="trans('entities.general.name_en')"
            v-model="form.name_en"
            maxlength="150"
            v-validate="'required'"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="trans('entities.general.name_fr')"
          prop="name_fr"
          required
        >
          <input-wrap
            name="name_fr"
            :data-vv-as="trans('entities.general.name_fr')"
            v-model="form.name_fr"
            maxlength="150"
            v-validate="'required'"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="trans('entities.general.email')"
          prop="email"
          required
        >
          <input-wrap
            name="email"
            :data-vv-as="trans('entities.general.email')"
            v-model="form.email"
            maxlength="250"
            v-validate="'required|email'"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="trans('entities.organizational_unit.director')"
          prop="director"
          required
        >
          <user-search
            name="director"
            :label="trans('entities.organizational_unit.director')"
            v-bind:user.sync="form.director"
          />
          <form-error name="director" />
        </el-form-item-wrap>

        <el-form-item class="form-footer">
          <el-button
            :disabled="isFormDisabled"
            @click="goToParentPage()"
          >
            {{ trans('base.actions.cancel') }}
          </el-button>
          <el-button
            :disabled="!isFormDirty || isFormDisabled"
            :loading="isSubmitting"
            type="primary"
            @click="onSubmit()"
          >
            {{ trans('base.actions.save') }}
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
  import { mapState, mapActions } from 'vuex';

  import Page from '@components/page';

  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import InputWrap from '@components/forms/input-wrap';
  import UserSearch from '@components/forms/user-search';
  import FormError from '@components/forms/error.vue';

  import FormUtils from '@mixins/form/utils.js';

  import OrganizationalUnit from '@/store/models/Organizational-Unit';

  const loadData = async function ({ to } = {}) {
    const { entityId } = to ? to.params : this;
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch('entities/organizationalUnits/loadEditInfo', entityId)
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
    name: 'organizational-unit-edit',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElFormItemWrap, InputWrap, UserSearch, FormError },

    computed: {
      viewing() {
        return OrganizationalUnit.find(this.entityId);
      }
    },

    props: {
      entityId: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        form: {}
      }
    },

    methods: {
      ...mapActions('entities/organizationalUnits', [
        '$$update'
      ]),

      loadData,

      // Form handlers
      onSubmit() {
        this.submit(this.handleUpdate);
      },

      async handleUpdate() {
        // @note: no try-catch required here
        // since we already do it in the form utils
        await this.$$update(this.form);
        this.isSubmitting = false;
        this.notifySuccess({
          message: this.trans('components.notice.message.organizational_unit_updated')
        });
        this.goToParentPage();
      }
    },

    async beforeRouteEnter(to, from, next) {
      await loadData({ to });
      next();
    },

    created() {
      this.form = _.cloneDeep(this.viewing);
    }
  };
</script>

<style lang="scss">

</style>
