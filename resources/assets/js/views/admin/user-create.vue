<template>
  <div class="user-create content">
    <el-card shadow="never">
      <span slot="header">
        <h2>{{ trans('base.navigation.admin_user_create') }}</h2>
      </span>

      <el-form ref="form" :model="form" label-position="top" @submit.native.prevent :disabled="isFormDisabled">
        <el-form-item-wrap
          :label="trans('entities.general.full_name')"
          prop="username"
          required>
          <user-search
            name="username"
            :label="trans('entities.general.full_name')"
            v-bind:user.sync="form.user">
          </user-search>
          <form-error name="username"></form-error>
        </el-form-item-wrap>

        <el-form-item :label="$tc('entities.general.organizational_units', 2)" for="organizationalUnits" prop="organizational_units">
          <el-select-wrap
            v-model="form.organizational_units"
            name="organizational_units"
            :data-vv-as="$tc('entities.general.organizational_units', 2)"
            v-validate="''"
            :options="organizationalUnits"
            multiple
          />
        </el-form-item>

        <el-form-item :label="trans('entities.user.roles')" for="roles" prop="roles">
          <el-select-wrap
            v-model="form.roles"
            name="roles"
            :data-vv-as="trans('entities.user.roles')"
            v-validate="''"
            :options="roles"
            multiple
          />
        </el-form-item>

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

  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import FormError from '@components/forms/error.vue';
  import UserSearch from '@components/forms/user-search';
  import FormUtils from '@mixins/form/utils.js';
  import PageUtils from '@mixins/page/utils.js';

  let namespace = 'users';

  export default {
    name: 'admin-user-create',

    inject: ['$validator'],

    mixins: [ FormUtils, PageUtils ],

    components: { FormError, ElSelectWrap, ElFormItemWrap, UserSearch },

    computed: {
      ...mapGetters({
        language: 'language',
        organizationalUnits: `${namespace}/organizationalUnits`,
        roles: `${namespace}/roles`
      })
    },

    data() {
      return {
        form: {
          user: {},
          organizational_units: [],
          roles: []
        }
      }
    },

    methods: {
      ...mapActions({
        searchUser: `${namespace}/search`,
        createUser: `${namespace}/create`,
        loadUserCreateInfo: `${namespace}/loadUserCreateInfo`
      }),

      // Form handlers
      onSubmit() {
        this.submit(this.create);
      },

      async create() {
        let formData = Object.assign({}, this.form);
        formData.username = this.form.user.username;
        await this.createUser(formData);

        this.isSubmitting = false;
        this.notifySuccess({
          message: this.trans('components.notice.message.user_created')
        });
        this.goToParentPage();
      },

      async triggerLoadUserCreateInfo() {
        await this.loadUserCreateInfo();
      },

      async onLanguageUpdate() {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.resetErrors();

        await this.triggerLoadUserCreateInfo();
      }
    },

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      this.onLanguageUpdate();
      next();
    },

    mounted() {
      EventBus.$emit('App:ready');

      this.triggerLoadUserCreateInfo();
      this.autofocus('name');
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  .user-create {
    margin: 0 auto;
    h2 {
      margin: 0;
      display: inline-block;
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
      .el-autocomplete, .el-select {
        width: 100%;
      }
    }
  }
</style>