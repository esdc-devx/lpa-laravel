<template>
  <div>
    <el-card shadow="never">
      <span slot="header">
        <h2><i class="el-icon-lpa-user"></i>{{ trans('base.navigation.admin_user_create') }}</h2>
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
  import { mapState, mapActions } from 'vuex';

  import Page from '@components/page';
  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import FormError from '@components/forms/error.vue';
  import UserSearch from '@components/forms/user-search';

  import FormUtils from '@mixins/form/utils.js';

  const loadData = async () => {
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch('entities/users/loadCreateInfo')
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
    name: 'user-create',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { FormError, ElSelectWrap, ElFormItemWrap, UserSearch },

    computed: {
      ...mapState('entities/users', [
        'organizationalUnits',
        'roles'
      ])
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
      ...mapActions('entities/users', [
        '$$create'
      ]),

      loadData,

      // Form handlers
      onSubmit() {
        this.submit(this.handleCreate);
      },

      async handleCreate() {
        let formData = Object.assign({}, this.form);
        formData.username = this.form.user.username;
        await this.$$create(formData);

        this.isSubmitting = false;
        this.notifySuccess({
          message: this.trans('components.notice.message.user_created')
        });
        this.goToParentPage();
      }
    },

    async beforeRouteEnter(to, from, next) {
      await loadData();
      next();
    },

    mounted() {
      this.autofocus('username');
    }
  };
</script>

<style lang="scss">

</style>
