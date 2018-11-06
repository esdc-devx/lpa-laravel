<template>
  <div class="learning-product-edit content">
    <el-card shadow="never">
      <span slot="header">
        <h2><i class="el-icon-lpa-learning-products"></i>{{ trans('pages.learning_product_edit.title') }}</h2>
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
            v-model="form.organizational_unit.id"
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
    {{ form.organizational_unit_id }}
  </div>
</template>

<script>
  import { mapActions } from 'vuex';

  import Page from '@components/page';
  import FormError from '@components/forms/error.vue';
  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import UserSearch from '@components/forms/user-search';
  import InputWrap from '@components/forms/input-wrap';

  import FormUtils from '@mixins/form/utils.js';

  import LearningProductAPI from '@api/learning-products';

  let namespace = 'learningProducts';

  export default {
    name: 'learning-product-edit',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElFormItemWrap, ElSelectWrap, InputWrap, FormError, UserSearch },

    data() {
      return {
        learningProductId: null,
        form: {
          name: '',
          organizational_unit: {},
          manager: {},
          primary_contact: {}
        },
        organizationalUnits: []
      }
    },

    methods: {
      ...mapActions({
        loadLearningProductEditInfo: `${namespace}/loadLearningProductEditInfo`
      }),

      async onSubmit() {
        this.submit(this.update);
      },

      async update() {
        await LearningProductAPI.update(this.form.id, {
          name: this.form.name,
          organizational_unit_id: this.form.organizational_unit.id,
          manager: this.form.manager.username,
          primary_contact: this.form.primary_contact.username
        });

        this.isSubmitting = false;
        this.notifySuccess({
          message: this.trans('components.notice.message.learning_product_updated')
        });

        this.goToParentPage();
      },

      async loadData() {
        try {
          // Load form information.
          let editInfo = await this.loadLearningProductEditInfo(this.learningProductId);
          this.form = _.cloneDeep(editInfo.learning_product);
          this.organizationalUnits = editInfo.organizational_units;
        } catch (e) {
          // Exception handled by interceptor
          if (!e.response) {
            throw e;
          }
        }
      },

      async onLanguageUpdate() {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.resetErrors();

        // Re-fetch organizational unit list and update the dropdown values.
        let response = await this.loadLearningProductEditInfo(this.learningProductId);
        this.organizationalUnits = response.organizational_units;
      }
    },

    beforeRouteEnter(to, from, next) {
      store.dispatch('learningProducts/canEdit', to.params.learningProductId)
        .then(allowed => {
          if (allowed) {
            next(vm => {
              vm.learningProductId = vm.$route.params.learningProductId;
              vm.loadData();
            });
          } else {
            router.replace({ name: 'forbidden', params: { '0': to.path } });
          }
        });
    },

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      this.onLanguageUpdate().then(next);
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .learning-product-edit {
    margin: 0 auto;
    h2 {
      position: relative;
      margin: 0;
      padding-left: 34px;
      display: inline-block;
      i {
        @include svg(learning-product, $--color-primary);
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
    .el-cascader {
      width: 35%;
    }
  }
</style>
