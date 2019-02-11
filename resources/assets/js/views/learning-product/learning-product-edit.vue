<template>
  <div>
    <el-card shadow="never">
      <span slot="header">
        <h2><i class="el-icon-lpa-edit"></i>{{ trans('pages.learning_product_edit.title') }}</h2>
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
          prop="organizational_unit_id"
          required>
          <el-select-wrap
            v-model="form.organizational_unit_id"
            name="organizational_unit_id"
            :data-vv-as="$tc('entities.general.organizational_units')"
            v-validate="'required'"
            :options="organizationalUnits"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="trans('entities.learning_product.manager')"
          prop="manager"
          required>
          <user-search
            name="manager"
            :label="trans('entities.learning_product.manager')"
            v-bind:user.sync="form.manager">
          </user-search>
          <form-error name="manager"></form-error>
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="trans('entities.learning_product.primary_contact')"
          prop="primary_contact"
          required>
          <user-search
            name="primary_contact"
            :label="trans('entities.learning_product.primary_contact')"
            v-bind:user.sync="form.primary_contact">
          </user-search>
          <form-error name="primary_contact"></form-error>
        </el-form-item-wrap>

        <el-form-item class="form-footer">
          <el-button :disabled="isFormDisabled" @click="goToParentPage()">{{ trans('base.actions.cancel') }}</el-button>
          <el-button :disabled="isFormPristine || isFormDisabled" :loading="isSubmitting" type="primary" @click="onSubmit()">{{ trans('base.actions.save') }}</el-button>
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
  import UserSearch from '@components/forms/user-search';
  import InputWrap from '@components/forms/input-wrap';

  import FormUtils from '@mixins/form/utils.js';

  import LearningProduct from '@/store/models/Learning-Product';

  const loadData = async function ({ to, hasToLoadEntity = true } = {}) {
    const { entityId } = to ? to.params : this;
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];

    if (hasToLoadEntity) {
      requests.push(store.dispatch('entities/learningProducts/loadEditInfo', entityId));
    }

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
    name: 'learning-product-edit',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElFormItemWrap, ElSelectWrap, InputWrap, FormError, UserSearch },

    props: {
      entityId: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        form: {
          name: '',
          organizational_unit_id: null,
          manager: {},
          primary_contact: {}
        }
      }
    },

    computed: {
      ...mapState('entities/learningProducts', [
        'organizationalUnits'
      ]),

      viewing() {
        return LearningProduct.find(this.entityId);
      }
    },

    methods: {
      ...mapActions('entities/learningProducts', [
        '$$update'
      ]),

      async onSubmit() {
        this.submit(this.handleUpdate);
      },

      async handleUpdate() {
        // @note: no try-catch required here
        // since we already do it in the form utils
        await this.$$update({
          id: this.form.id,
          data: {
            name: this.form.name,
            organizational_unit_id: this.form.organizational_unit_id,
            manager: this.form.manager.username,
            primary_contact: this.form.primary_contact.username
          }
        });
        this.isSubmitting = false;
        this.notifySuccess({
          message: this.trans('components.notice.message.learning_product_updated')
        });
        this.goToParentPage();
      },

      async onLanguageUpdate() {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.resetErrors();
        await loadData.apply(this);
      }
    },

    async beforeRouteEnter(to, from, next) {
      await store.dispatch('authorizations/learningProducts/loadCanEdit', to.params.entityId);
      if (LearningProduct.find(to.params.entityId).permissions.canEdit) {
        await loadData({ to });
        next();
      } else {
        router.replace({ name: 'forbidden', params: { '0': to.path } });
      }
    },

    // called when url params change, e.g: language
    async beforeRouteUpdate(to, from, next) {
      await this.$store.dispatch('authorizations/learningProducts/loadCanEdit', to.params.entityId);
      if (LearningProduct.find(to.params.entityId).permissions.canEdit) {
        await this.onLanguageUpdate();
        next();
      } else {
        router.replace({ name: 'forbidden', params: { '0': to.path } });
      }
    },

    created() {
      this.form = _.cloneDeep(this.viewing);
    }
  };
</script>

<style lang="scss">

</style>
